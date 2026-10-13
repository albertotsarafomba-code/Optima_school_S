<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\ParentModel;
use App\Models\EmploiDuTemps;
use App\Models\Establishment;
use App\Models\Salle;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmploiDuTempsController extends Controller
{
    /**
     * Retourne l'établissement actuellement sélectionné
     * et vérifie que l'utilisateur y a accès.
     */
    private function currentEstablishment(): Establishment
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        if ($user->role === 'admin') {
            $establishments = $user->establishments()
                ->with('schoolType')
                ->get();
        } else {
            $establishments = $user->assignedEstablishments()
                ->with('schoolType')
                ->get();
        }

        if ($establishments->isEmpty()) {
            abort(403, 'Aucun établissement accessible.');
        }

        $sessionId = session('establishment_id');

        $establishment = $establishments->firstWhere('id', $sessionId);

        if (!$establishment) {
            if ($establishments->count() === 1) {
                $establishment = $establishments->first();

                session([
                    'establishment_id' => $establishment->id,
                    'establishment_name' => $establishment->nom,
                ]);
            } else {
                abort(
                    redirect()
                        ->route('establishments.choose')
                        ->with('warning', 'Veuillez sélectionner un établissement.')
                );
            }
        }

        return $establishment;
    }

    /**
     * Retourne l'année scolaire sélectionnée,
     * strictement liée à l'établissement courant.
     */
    private function currentSchoolYear(
        Request $request,
        Establishment $establishment
    ): SchoolYear {
        $schoolYears = SchoolYear::query()
            ->where('establishment_id', $establishment->id)
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        if ($schoolYears->isEmpty()) {
            abort(404, 'Aucune année scolaire disponible pour cet établissement.');
        }

        $requestedId = $request->integer(
            'school_year_id',
            (int) session('school_year_id')
        );

        $schoolYear = $schoolYears->firstWhere('id', $requestedId);

        if (!$schoolYear) {
            $schoolYear = $schoolYears->firstWhere('is_active', true)
                ?? $schoolYears->first();

            session([
                'school_year_id' => $schoolYear->id,
                'school_year_name' => $schoolYear->name,
            ]);
        } else {
            session([
                'school_year_id' => $schoolYear->id,
                'school_year_name' => $schoolYear->name,
            ]);
        }

        return $schoolYear;
    }

    /**
     * Liste de l'emploi du temps.
     */
    public function index(Request $request)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Années scolaires disponibles uniquement pour
         * l'établissement actuellement sélectionné.
         */
        $schoolYears = SchoolYear::query()
            ->where('establishment_id', $establishment->id)
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        $schoolYear = $this->currentSchoolYear(
            $request,
            $establishment
        );

        $selectedSchoolYearId = $schoolYear->id;

        $classes = ClassRoom::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->orderBy('nom_classe')
            ->get();

        $classRooms = $classes;

        $selectedClassId = $request->integer('class_room_id');
        $selectedClassRoomId = $selectedClassId;

        $selectedClass = $classes->firstWhere(
            'id',
            $selectedClassId
        );

        $jours = [
            'Lundi',
            'Mardi',
            'Mercredi',
            'Jeudi',
            'Vendredi',
            'Samedi',
            'Dimanche',
        ];

        $selectedJour = $request->string('jour')->toString();

        if (!in_array($selectedJour, $jours, true)) {
            $selectedJour = '';
        }

        $emplois = EmploiDuTemps::query()
            ->with([
                'classRoom',
                'subject',
                'teacher',
                'salle',
            ])
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->when(
                $selectedClass,
                fn ($query) =>
                    $query->where(
                        'class_room_id',
                        $selectedClass->id
                    )
            )
            ->when(
                $selectedJour !== '',
                fn ($query) =>
                    $query->where('jour', $selectedJour)
            )
            ->orderByRaw(
                "FIELD(
                    jour,
                    'Lundi',
                    'Mardi',
                    'Mercredi',
                    'Jeudi',
                    'Vendredi',
                    'Samedi',
                    'Dimanche'
                )"
            )
            ->orderBy('heure_debut')
            ->get();

        return view(
            'emploi_du_temps.index',
            compact(
                'establishment',
                'schoolYears',
                'schoolYear',
                'selectedSchoolYearId',
                'classes',
                'classRooms',
                'selectedClass',
                'selectedClassRoomId',
                'jours',
                'selectedJour',
                'emplois'
            )
        );
    }

    /**
     * Formulaire de création.
     */
    public function create(Request $request)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Toutes les années scolaires de cet établissement uniquement.
         * On conserve la séparation stricte par établissement.
         */
        $schoolYears = SchoolYear::query()
            ->where('establishment_id', $establishment->id)
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        /*
         * Année scolaire actuellement sélectionnée.
         * La méthode currentSchoolYear() vérifie déjà qu'elle appartient
         * bien à l'établissement courant.
         */
        $schoolYear = $this->currentSchoolYear(
            $request,
            $establishment
        );

        $selectedSchoolYearId = $schoolYear->id;

        /*
         * Les classes sont strictement limitées à l'établissement
         * et à l'année scolaire sélectionnée.
         */
        $classes = ClassRoom::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->orderBy('nom_classe')
            ->get();

        $classRooms = $classes;

        /*
         * Les matières sont strictement limitées à l'établissement
         * et à l'année scolaire sélectionnée.
         */
        $subjects = Subject::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->orderBy('nom')
            ->get();

        /*
         * Les enseignants restent liés à l'établissement courant.
         */
        $teachers = Teacher::query()
            ->where('establishment_id', $establishment->id)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        /*
         * Les salles restent liées à l'établissement courant.
         */
        $salles = Salle::query()
            ->where('establishment_id', $establishment->id)
            ->orderBy('nom')
            ->get();

        $jours = [
            'Lundi',
            'Mardi',
            'Mercredi',
            'Jeudi',
            'Vendredi',
            'Samedi',
            'Dimanche',
        ];

        return view(
            'emploi_du_temps.create',
            compact(
                'establishment',
                'schoolYears',
                'schoolYear',
                'selectedSchoolYearId',
                'classes',
                'classRooms',
                'subjects',
                'teachers',
                'salles',
                'jours'
            )
        );
    }

    /**
     * Enregistre un créneau.
     */
    public function store(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->currentSchoolYear(
            $request,
            $establishment
        );

        $validated = $request->validate([
            'class_room_id' => [
                'required',
                'integer',
            ],
            'subject_id' => [
                'required',
                'integer',
            ],
            'teacher_id' => [
                'required',
                'integer',
            ],
            'salle_id' => [
                'required',
                'integer',
            ],
            'jour' => [
                'required',
                'string',
                'in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche',
            ],
            'heure_debut' => [
                'required',
                'date_format:H:i',
            ],
            'heure_fin' => [
                'required',
                'date_format:H:i',
                'after:heure_debut',
            ],
        ]);

        $classRoom = ClassRoom::query()
            ->where('id', $validated['class_room_id'])
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->firstOrFail();

        $subject = Subject::query()
            ->where('id', $validated['subject_id'])
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->firstOrFail();

        $teacher = Teacher::query()
            ->where('id', $validated['teacher_id'])
            ->where('establishment_id', $establishment->id)
            ->firstOrFail();

        $salle = Salle::query()
            ->where('id', $validated['salle_id'])
            ->where('establishment_id', $establishment->id)
            ->firstOrFail();

        /*
         * Vérifie que la matière est bien associée à la classe.
         */
        $subjectAssigned = DB::table('teacher_subjects')
            ->where('subject_id', $subject->id)
            ->where('class_room_id', $classRoom->id)
            ->where('school_year_id', $schoolYear->id)
            ->exists();

        if (!$subjectAssigned) {
            throw ValidationException::withMessages([
                'subject_id' =>
                    'Cette matière n\'est pas associée à cette classe.',
            ]);
        }

        /*
         * Vérifie que l'enseignant enseigne bien cette matière
         * dans cette classe et cette année scolaire.
         */
        $teacherAssigned = $teacher->teacherSubjects()
            ->where('subject_id', $subject->id)
            ->where('class_room_id', $classRoom->id)
            ->where('school_year_id', $schoolYear->id)
            ->exists();

        if (!$teacherAssigned) {
            throw ValidationException::withMessages([
                'teacher_id' =>
                    'Cet enseignant n\'est pas affecté à cette matière et cette classe.',
            ]);
        }

        /*
         * Empêche deux cours de la même classe
         * au même moment.
         */
        $classConflict = EmploiDuTemps::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('class_room_id', $classRoom->id)
            ->where('jour', $validated['jour'])
            ->where('heure_debut', '<', $validated['heure_fin'])
            ->where('heure_fin', '>', $validated['heure_debut'])
            ->exists();

        if ($classConflict) {
            throw ValidationException::withMessages([
                'heure_debut' =>
                    'Cette classe possède déjà un cours pendant ce créneau.',
            ]);
        }

        /*
         * Empêche un enseignant d'avoir deux cours
         * au même moment.
         */
        $teacherConflict = EmploiDuTemps::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('teacher_id', $teacher->id)
            ->where('jour', $validated['jour'])
            ->where('heure_debut', '<', $validated['heure_fin'])
            ->where('heure_fin', '>', $validated['heure_debut'])
            ->exists();

        if ($teacherConflict) {
            throw ValidationException::withMessages([
                'teacher_id' =>
                    'Cet enseignant possède déjà un cours pendant ce créneau.',
            ]);
        }

        /*
         * Empêche une salle d'être utilisée par deux cours
         * au même moment.
         */
        $roomConflict = EmploiDuTemps::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('salle_id', $salle->id)
            ->where('jour', $validated['jour'])
            ->where('heure_debut', '<', $validated['heure_fin'])
            ->where('heure_fin', '>', $validated['heure_debut'])
            ->exists();

        if ($roomConflict) {
            throw ValidationException::withMessages([
                'salle_id' =>
                    'Cette salle est déjà utilisée pendant ce créneau.',
            ]);
        }

        EmploiDuTemps::create([
            'establishment_id' => $establishment->id,
            'school_year_id' => $schoolYear->id,
            'class_room_id' => $classRoom->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'salle_id' => $salle->id,
            'jour' => $validated['jour'],
            'heure_debut' => $validated['heure_debut'],
            'heure_fin' => $validated['heure_fin'],
        ]);

        return redirect()
            ->route('emploi-du-temps.index', [
                'school_year_id' => $schoolYear->id,
                'class_room_id' => $classRoom->id,
            ])
            ->with(
                'success',
                'Le créneau a été ajouté avec succès.'
            );
    }

    /**
     * Formulaire de modification.
     */
    public function edit(
        Request $request,
        EmploiDuTemps $emploiDuTemps
    ) {
        $establishment = $this->currentEstablishment();

        $schoolYears = SchoolYear::query()
            ->where('establishment_id', $establishment->id)
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        $schoolYear = $this->currentSchoolYear(
            $request,
            $establishment
        );

        $selectedSchoolYearId = $schoolYear->id;

        /*
         * Protection établissement + année scolaire.
         */
        if (
            $emploiDuTemps->establishment_id !== $establishment->id
            || $emploiDuTemps->school_year_id !== $schoolYear->id
        ) {
            abort(404);
        }

        $classes = ClassRoom::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->orderBy('nom_classe')
            ->get();

        $classRooms = $classes;

        $subjects = Subject::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->orderBy('nom')
            ->get();

        $teachers = Teacher::query()
            ->where('establishment_id', $establishment->id)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $salles = Salle::query()
            ->where('establishment_id', $establishment->id)
            ->orderBy('nom')
            ->get();

        $jours = [
            'Lundi',
            'Mardi',
            'Mercredi',
            'Jeudi',
            'Vendredi',
            'Samedi',
            'Dimanche',
        ];

        return view(
            'emploi_du_temps.edit',
            compact(
                'establishment',
                'schoolYears',
                'schoolYear',
                'selectedSchoolYearId',
                'emploiDuTemps',
                'classes',
                'classRooms',
                'subjects',
                'teachers',
                'salles',
                'jours'
            )
        );
    }

    /**
     * Met à jour un créneau.
     */
    public function update(
        Request $request,
        EmploiDuTemps $emploiDuTemps
    ) {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->currentSchoolYear(
            $request,
            $establishment
        );

        if (
            $emploiDuTemps->establishment_id !== $establishment->id
            || $emploiDuTemps->school_year_id !== $schoolYear->id
        ) {
            abort(404);
        }

        $validated = $request->validate([
            'class_room_id' => [
                'required',
                'integer',
            ],
            'subject_id' => [
                'required',
                'integer',
            ],
            'teacher_id' => [
                'required',
                'integer',
            ],
            'salle_id' => [
                'required',
                'integer',
            ],
            'jour' => [
                'required',
                'string',
                'in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche',
            ],
            'heure_debut' => [
                'required',
                'date_format:H:i',
            ],
            'heure_fin' => [
                'required',
                'date_format:H:i',
                'after:heure_debut',
            ],
        ]);

        $classRoom = ClassRoom::query()
            ->where('id', $validated['class_room_id'])
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->firstOrFail();

        $subject = Subject::query()
            ->where('id', $validated['subject_id'])
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->firstOrFail();

        $teacher = Teacher::query()
            ->where('id', $validated['teacher_id'])
            ->where('establishment_id', $establishment->id)
            ->firstOrFail();

        $salle = Salle::query()
            ->where('id', $validated['salle_id'])
            ->where('establishment_id', $establishment->id)
            ->firstOrFail();

        $subjectAssigned = $classRoom->subjects()
            ->where('subjects.id', $subject->id)
            ->exists();

        if (!$subjectAssigned) {
            throw ValidationException::withMessages([
                'subject_id' =>
                    'Cette matière n\'est pas associée à cette classe.',
            ]);
        }

        $teacherAssigned = $teacher->teacherSubjects()
            ->where('subject_id', $subject->id)
            ->where('class_room_id', $classRoom->id)
            ->where('school_year_id', $schoolYear->id)
            ->exists();

        if (!$teacherAssigned) {
            throw ValidationException::withMessages([
                'teacher_id' =>
                    'Cet enseignant n\'est pas affecté à cette matière et cette classe.',
            ]);
        }

        $baseConflictQuery = EmploiDuTemps::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('jour', $validated['jour'])
            ->where('heure_debut', '<', $validated['heure_fin'])
            ->where('heure_fin', '>', $validated['heure_debut'])
            ->where('id', '!=', $emploiDuTemps->id);

        if (
            $baseConflictQuery
                ->clone()
                ->where('class_room_id', $classRoom->id)
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'heure_debut' =>
                    'Cette classe possède déjà un cours pendant ce créneau.',
            ]);
        }

        if (
            $baseConflictQuery
                ->clone()
                ->where('teacher_id', $teacher->id)
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'teacher_id' =>
                    'Cet enseignant possède déjà un cours pendant ce créneau.',
            ]);
        }

        if (
            $baseConflictQuery
                ->clone()
                ->where('salle_id', $salle->id)
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'salle_id' =>
                    'Cette salle est déjà utilisée pendant ce créneau.',
            ]);
        }

        $emploiDuTemps->update([
            'class_room_id' => $classRoom->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
            'salle_id' => $salle->id,
            'jour' => $validated['jour'],
            'heure_debut' => $validated['heure_debut'],
            'heure_fin' => $validated['heure_fin'],
        ]);

        return redirect()
            ->route('emploi-du-temps.index', [
                'school_year_id' => $schoolYear->id,
                'class_room_id' => $classRoom->id,
            ])
            ->with(
                'success',
                'Le créneau a été modifié avec succès.'
            );
    }

    /**
     * Supprime un créneau.
     */
    public function destroy(
        Request $request,
        EmploiDuTemps $emploiDuTemps
    ) {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->currentSchoolYear(
            $request,
            $establishment
        );

        if (
            $emploiDuTemps->establishment_id !== $establishment->id
            || $emploiDuTemps->school_year_id !== $schoolYear->id
        ) {
            abort(404);
        }

        $emploiDuTemps->delete();

        return redirect()
            ->route('emploi-du-temps.index', [
                'school_year_id' => $schoolYear->id,
            ])
            ->with(
                'success',
                'Le créneau a été supprimé avec succès.'
            );
    }
}
