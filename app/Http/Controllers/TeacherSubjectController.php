<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Establishment;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TeacherSubjectController extends Controller
{
    /**
     * Vérifier que l'utilisateur est administrateur.
     */
    private function ensureAdmin(): void
    {
        abort_unless(
            Auth::check() &&
            Auth::user()->role === 'admin',
            403,
            'Accès réservé aux administrateurs.'
        );
    }

    /**
     * ID de l'établissement actuellement sélectionné.
     */
    private function establishmentId(): int
    {
        return (int) session('establishment_id');
    }

    /**
     * Vérifier qu'un établissement est sélectionné.
     */
    private function checkEstablishment(): void
    {
        if (!$this->establishmentId()) {
            abort(
                403,
                'Aucun établissement sélectionné.'
            );
        }
    }

    /**
     * Récupérer l'établissement courant.
     */
    private function establishment(): Establishment
    {
        $establishment = Establishment::query()
            ->with('schoolType')
            ->find($this->establishmentId());

        abort_unless(
            $establishment,
            403,
            'Établissement introuvable.'
        );

        return $establishment;
    }

    /**
     * Nom normalisé du type d'établissement.
     */
    private function schoolTypeName(
        Establishment $establishment
    ): string {
        return strtolower(
            trim(
                (string) (
                    $establishment->schoolType?->name
                    ?? $establishment->schoolType?->nom
                    ?? ''
                )
            )
        );
    }

    /**
     * Année scolaire active/courante.
     */
    private function activeSchoolYear(): ?SchoolYear
    {
        $establishmentId = $this->establishmentId();

        $schoolYearId = session('school_year_id');

        if ($schoolYearId) {
            $schoolYear = SchoolYear::query()
                ->where('id', $schoolYearId)
                ->where(
                    'establishment_id',
                    $establishmentId
                )
                ->first();

            if ($schoolYear) {
                return $schoolYear;
            }
        }

        return SchoolYear::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->first();
    }

    /**
     * Liste des affectations.
     */
    public function index()
    {
        $this->ensureAdmin();
        $this->checkEstablishment();

        $establishmentId = $this->establishmentId();

        $assignments = TeacherSubject::query()
            ->with([
                'teacher',
                'subject.schoolType',
                'classRoom',
                'schoolYear',
            ])
            ->whereHas(
                'teacher',
                function ($query) use ($establishmentId) {
                    $query->where(
                        'establishment_id',
                        $establishmentId
                    );
                }
            )
            ->whereHas(
                'subject',
                function ($query) use ($establishmentId) {
                    $query->where(
                        'establishment_id',
                        $establishmentId
                    );
                }
            )
            ->whereHas(
                'classRoom',
                function ($query) use ($establishmentId) {
                    $query->where(
                        'establishment_id',
                        $establishmentId
                    );
                }
            )
            ->whereHas(
                'schoolYear',
                function ($query) use ($establishmentId) {
                    $query->where(
                        'establishment_id',
                        $establishmentId
                    );
                }
            )
            ->orderByDesc('school_year_id')
            ->orderBy('teacher_id')
            ->orderBy('class_room_id')
            ->get();

        return view(
            'teacher_subjects.index',
            compact('assignments')
        );
    }

    /**
     * Formulaire de création d'une affectation.
     */
    public function create()
    {
        $this->ensureAdmin();
        $this->checkEstablishment();

        $establishment = $this->establishment();

        $establishmentId = $establishment->id;
        $schoolTypeId = (int) $establishment->school_type_id;

        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        $activeSchoolYear = $this->activeSchoolYear();

        $teachers = Teacher::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $subjects = collect();
        $classRooms = collect();

        if ($activeSchoolYear) {
            /**
             * Matières :
             * uniquement celles de l'établissement,
             * de l'année scolaire et du même type.
             */
            $subjects = Subject::query()
                ->where(
                    'establishment_id',
                    $establishmentId
                )
                ->where(
                    'school_year_id',
                    $activeSchoolYear->id
                )
                ->where(
                    'school_type_id',
                    $schoolTypeId
                )
                ->orderBy('nom')
                ->get();

            /**
             * Classes :
             * uniquement celles de l'établissement,
             * de l'année scolaire et du même type.
             */
            $classRooms = ClassRoom::query()
                ->where(
                    'establishment_id',
                    $establishmentId
                )
                ->where(
                    'school_year_id',
                    $activeSchoolYear->id
                )
                ->whereHas(
                    'establishment',
                    function ($query) use ($schoolTypeId) {
                        $query->where(
                            'school_type_id',
                            $schoolTypeId
                        );
                    }
                )
                ->orderBy('niveau')
                ->orderBy('nom_classe')
                ->get();
        }

        return view(
            'teacher_subjects.create',
            compact(
                'teachers',
                'subjects',
                'classRooms',
                'schoolYears',
                'activeSchoolYear'
            )
        );
    }

    /**
     * Enregistrer une affectation.
     */
    public function store(Request $request)
    {
        $this->ensureAdmin();
        $this->checkEstablishment();

        $establishment = $this->establishment();

        $establishmentId = $establishment->id;
        $schoolTypeId = (int) $establishment->school_type_id;

        $validated = $request->validate([
            'teacher_id' => [
                'required',
                'integer',
                Rule::exists(
                    'teachers',
                    'id'
                )->where(
                    fn ($query) =>
                        $query->where(
                            'establishment_id',
                            $establishmentId
                        )
                ),
            ],

            'subject_id' => [
                'required',
                'integer',
                Rule::exists(
                    'subjects',
                    'id'
                )->where(
                    function ($query) use (
                        $establishmentId,
                        $schoolTypeId
                    ) {
                        $query
                            ->where(
                                'establishment_id',
                                $establishmentId
                            )
                            ->where(
                                'school_type_id',
                                $schoolTypeId
                            );
                    }
                ),
            ],

            'class_room_id' => [
                'required',
                'integer',
                Rule::exists(
                    'class_rooms',
                    'id'
                )->where(
                    function ($query) use (
                        $establishmentId
                    ) {
                        $query->where(
                            'establishment_id',
                            $establishmentId
                        );
                    }
                ),
            ],

            'school_year_id' => [
                'required',
                'integer',
                Rule::exists(
                    'school_years',
                    'id'
                )->where(
                    function ($query) use (
                        $establishmentId
                    ) {
                        $query->where(
                            'establishment_id',
                            $establishmentId
                        );
                    }
                ),
            ],
        ]);

        /**
         * Vérification année scolaire.
         */
        $schoolYear = SchoolYear::query()
            ->where(
                'id',
                $validated['school_year_id']
            )
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->first();

        if (!$schoolYear) {
            return back()
                ->withInput()
                ->withErrors([
                    'school_year_id' =>
                        'L’année scolaire sélectionnée est invalide.',
                ]);
        }

        /**
         * Vérification enseignant.
         */
        $teacher = Teacher::query()
            ->with('establishment.schoolType')
            ->where(
                'id',
                $validated['teacher_id']
            )
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->first();

        if (!$teacher) {
            return back()
                ->withInput()
                ->withErrors([
                    'teacher_id' =>
                        'L’enseignant sélectionné n’appartient pas à cet établissement.',
                ]);
        }

        /**
         * Vérification du type de l'enseignant.
         */
        $teacherSchoolTypeId = (int) (
            $teacher->establishment?->school_type_id
        );

        if (
            $teacherSchoolTypeId !==
            $schoolTypeId
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'teacher_id' =>
                        'L’enseignant ne correspond pas au type de cet établissement.',
                ]);
        }

        /**
         * Vérification classe.
         */
        $classRoom = ClassRoom::query()
            ->with('establishment.schoolType')
            ->where(
                'id',
                $validated['class_room_id']
            )
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->first();

        if (!$classRoom) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        'La classe sélectionnée n’appartient pas à cet établissement.',
                ]);
        }

        /**
         * La classe doit appartenir à l'année sélectionnée.
         */
        if (
            (int) $classRoom->school_year_id !==
            (int) $schoolYear->id
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        'Cette classe n’appartient pas à l’année scolaire sélectionnée.',
                ]);
        }

        /**
         * La classe doit correspondre au type
         * de l'établissement.
         */
        $classSchoolTypeId = (int) (
            $classRoom
                ->establishment
                ?->school_type_id
        );

        if (
            $classSchoolTypeId !==
            $schoolTypeId
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        'Cette classe ne correspond pas au type de l’établissement.',
                ]);
        }

        /**
         * Vérification matière.
         */
        $subject = Subject::query()
            ->with('schoolType')
            ->where(
                'id',
                $validated['subject_id']
            )
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->first();

        if (!$subject) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'Cette matière n’appartient pas à l’année scolaire sélectionnée.',
                ]);
        }

        /**
         * La matière doit obligatoirement correspondre
         * au type de l'établissement.
         */
        if (
            (int) $subject->school_type_id !==
            $schoolTypeId
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'Cette matière ne correspond pas au type de l’établissement.',
                ]);
        }

        /**
         * La matière et la classe doivent être
         * réellement liées dans class_room_subjects.
         */
        $classSubjectExists = DB::table(
            'class_room_subjects'
        )
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'subject_id',
                $subject->id
            )
            ->exists();

        if (!$classSubjectExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'Cette matière n’est pas affectée à la classe sélectionnée.',
                ]);
        }

        /**
         * Vérification d'un doublon.
         */
        $alreadyExists = TeacherSubject::query()
            ->where(
                'teacher_id',
                $teacher->id
            )
            ->where(
                'subject_id',
                $subject->id
            )
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'teacher_id' =>
                        'Cette affectation existe déjà pour cet enseignant, cette matière, cette classe et cette année scolaire.',
                ]);
        }

        /**
         * Création de l'affectation.
         */
        TeacherSubject::create([
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'class_room_id' => $classRoom->id,
            'school_year_id' => $schoolYear->id,
        ]);

        return redirect()
            ->route('teacher_subjects.index')
            ->with(
                'success',
                'Enseignant affecté avec succès.'
            );
    }

    /**
     * Supprimer une affectation.
     */
    public function destroy(
        TeacherSubject $teacherSubject
    ) {
        $this->ensureAdmin();
        $this->checkEstablishment();

        $establishment = $this->establishment();

        $establishmentId = $establishment->id;
        $schoolTypeId = (int) $establishment->school_type_id;

        /**
         * Vérification enseignant.
         */
        $authorizedTeacher = Teacher::query()
            ->where(
                'id',
                $teacherSubject->teacher_id
            )
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->exists();

        /**
         * Vérification matière.
         */
        $authorizedSubject = Subject::query()
            ->where(
                'id',
                $teacherSubject->subject_id
            )
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->where(
                'school_type_id',
                $schoolTypeId
            )
            ->exists();

        /**
         * Vérification classe.
         */
        $authorizedClass = ClassRoom::query()
            ->where(
                'id',
                $teacherSubject->class_room_id
            )
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->exists();

        /**
         * Vérification année scolaire.
         */
        $authorizedYear = SchoolYear::query()
            ->where(
                'id',
                $teacherSubject->school_year_id
            )
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->exists();

        abort_unless(
            $authorizedTeacher &&
            $authorizedSubject &&
            $authorizedClass &&
            $authorizedYear,
            403,
            'Cette affectation n’appartient pas à votre établissement.'
        );

        $teacherSubject->delete();

        return redirect()
            ->route('teacher_subjects.index')
            ->with(
                'success',
                'Affectation supprimée avec succès.'
            );
    }
}