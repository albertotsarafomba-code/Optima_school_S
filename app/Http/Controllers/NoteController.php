<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\ClassRoomSubject;
use App\Models\Establishment;
use App\Models\Inscription;
use App\Models\Note;
use App\Models\ParentModel;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NoteController extends Controller
{
    /**
     * Types d'évaluation disponibles.
     */
    private function evaluationTypes(): array
    {
        return [
            'Devoir',
            'Interrogation',
            'Contrôle',
            'Examen',
            'TP',
            'Évaluation continue',
            'Autre',
        ];
    }

        /**
 * Retourne l'établissement courant accessible par l'utilisateur.
 */
private function currentEstablishment()
{
    if (!Auth::check()) {
        abort(403, 'Vous devez être connecté.');
    }

    $user = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | Établissements accessibles
    |--------------------------------------------------------------------------
    */
    if ($user->role === 'admin') {
        $establishments = $user
            ->establishments()
            ->with('schoolType')
            ->orderBy('id')
            ->get();
    } else {
        $establishments = $user
            ->assignedEstablishments()
            ->with('schoolType')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Enseignant : son profil Teacher peut définir directement
        | son établissement.
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'enseignant') {

            $teacher = $user->teacher()->first();

            if ($teacher && $teacher->establishment_id) {

                $teacherEstablishment = Establishment::with('schoolType')
                    ->find($teacher->establishment_id);

                if (
                    $teacherEstablishment &&
                    !$establishments->contains(
                        'id',
                        $teacherEstablishment->id
                    )
                ) {
                    $establishments->push(
                        $teacherEstablishment
                    );
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Aucun établissement
    |--------------------------------------------------------------------------
    */
    if ($establishments->isEmpty()) {

        session()->forget([
            'establishment_id',
            'establishment_name',
            'school_year_id',
            'school_year_name',
        ]);

        if ($user->role === 'admin') {
            return redirect()
                ->route('establishments.create')
                ->with(
                    'error',
                    'Vous devez d’abord créer un établissement.'
                );
        }

        return redirect()
            ->route('establishments.choose')
            ->with(
                'error',
                'Aucun établissement ne vous a été affecté.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Établissement déjà sélectionné en session
    |--------------------------------------------------------------------------
    */
    $sessionId = session('establishment_id');

    if ($sessionId) {

        $establishment = $establishments->firstWhere(
            'id',
            (int) $sessionId
        );

        if ($establishment) {

            session([
                'establishment_id' => $establishment->id,
                'establishment_name' => $establishment->nom,
            ]);

            return $establishment;
        }

        /*
        | L'établissement en session n'est plus accessible.
        */
        session()->forget([
            'establishment_id',
            'establishment_name',
            'school_year_id',
            'school_year_name',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Un seul établissement accessible
    |--------------------------------------------------------------------------
    */
    if ($establishments->count() === 1) {

        $establishment = $establishments->first();

        session([
            'establishment_id' => $establishment->id,
            'establishment_name' => $establishment->nom,
        ]);

        return $establishment;
    }

    /*
    |--------------------------------------------------------------------------
    | Plusieurs établissements et aucun choix en session
    |--------------------------------------------------------------------------
    |
    | On redirige vers le sélecteur.
    | IMPORTANT : le contrôleur appelant doit gérer cette réponse.
    |
    */
    return redirect()
        ->route('establishments.choose');
}
    /**
     * Alias.
     */
    private function getEstablishment()
    {
        return $this->currentEstablishment();
    }

    /**
     * Retourne le profil Teacher du compte connecté.
     *
     * Un enseignant doit obligatoirement être lié à un
     * profil Teacher via teachers.user_id.
     */
    private function currentTeacher(): ?Teacher
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'enseignant') {
            return null;
        }

        $teacher = $user->teacher()->first();

        if (!$teacher) {
            abort(
                403,
                'Votre compte enseignant n’est pas encore lié à un profil enseignant.'
            );
        }

        return $teacher;
    }

    /**
     * Vérifie qu'une note appartient bien à l'enseignant connecté.
     *
     * Les administrateurs peuvent travailler avec toutes les notes.
     * Un enseignant ne peut travailler qu'avec ses propres notes.
     */
    private function authorizeTeacherNote(Note $note): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Vous devez être connecté.');
        }

        if ($user->role !== 'enseignant') {
            return;
        }

        $teacher = $this->currentTeacher();

        if (
            (int) $note->teacher_id !==
            (int) $teacher->id
        ) {
            abort(
                403,
                'Vous n’avez pas l’autorisation d’accéder à cette note.'
            );
        }
    }

    /**
     * Retourne les IDs des classes réellement enseignées
     * par l'enseignant pour une année scolaire donnée.
     */
    private function teacherClassIds(
        int $teacherId,
        int $schoolYearId
    ) {
        return TeacherSubject::query()
            ->where('teacher_id', $teacherId)
            ->where('school_year_id', $schoolYearId)
            ->pluck('class_room_id')
            ->filter()
            ->unique()
            ->values();
    }

    /**
     * Vérifie qu'un enseignant est réellement affecté
     * à une classe + matière + année scolaire.
     */
    private function teacherAssignmentExists(
        int $teacherId,
        int $classRoomId,
        int $subjectId,
        int $schoolYearId,
        int $establishmentId
    ): bool {
        return TeacherSubject::query()
            ->where('teacher_id', $teacherId)
            ->where('class_room_id', $classRoomId)
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $schoolYearId)
            ->whereHas('teacher', function ($query) use (
                $establishmentId
            ) {
                $query->where(
                    'establishment_id',
                    $establishmentId
                );
            })
            ->exists();
    }

    /**
     * Vérifier l'établissement courant.
     */
    private function checkEstablishment($establishmentId): void
    {
        $establishment = $this->currentEstablishment();

        if (
            !$establishment ||
            (int) $establishment->id !== (int) $establishmentId
        ) {
            abort(
                403,
                'Vous n’avez pas accès à cet établissement.'
            );
        }
    }

    /**
     * Liste des notes.
     */
    public function index(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYears = SchoolYear::where(
            'establishment_id',
            $establishment->id
        )
            ->orderByDesc('id')
            ->get();

        /*
         * Année scolaire sélectionnée :
         *
         * 1. paramètre school_year_id
         * 2. session school_year_id
         */
        $schoolYearId = $request->input('school_year_id');

        if ($schoolYearId === null || $schoolYearId === '') {
            $schoolYearId = session('school_year_id');
        }

        if ($schoolYearId === null || $schoolYearId === '') {
            return redirect()
                ->route('school_years.index')
                ->with('error', 'Veuillez sélectionner une année scolaire.');
        }

        $schoolYear = SchoolYear::query()
            ->where('id', $schoolYearId)
            ->where('establishment_id', $establishment->id)
            ->first();

        if (!$schoolYear) {
            session()->forget([
                'school_year_id',
                'school_year_name',
            ]);

            return redirect()
                ->route('school_years.index')
                ->with('error', 'L’année scolaire sélectionnée est invalide.');
        }

        $schoolYearId = $schoolYear->id;

        /*
         * Enseignant connecté :
         * ses notes seront limitées à son propre teacher_id.
         */
        $teacher = null;

        if ($request->user()->role === 'enseignant') {
            $teacher = $this->currentTeacher();
        }

        /*
         * Synchronisation de la session.
         */
        session([
            'school_year_id' => $schoolYear->id,
            'school_year_name' => $schoolYear->name,
        ]);

        /*
         * Élèves autorisés pour le parent.
         *
         * IMPORTANT :
         * le parent ne peut consulter que les enfants réellement
         * rattachés à son compte dans parent_student.
         */
        $allowedStudentIds = null;

        if ($request->user()->role === 'parent') {

            $parentProfiles = ParentModel::query()
                ->with('students')
                ->where('user_id', $request->user()->id)
                ->where(
                    'establishment_id',
                    $establishment->id
                )
                ->get();

            if ($parentProfiles->isEmpty()) {
                abort(
                    403,
                    'Compte parent introuvable pour cet établissement.'
                );
            }

            $allowedStudentIds = $parentProfiles
                ->flatMap(function ($parent) {
                    return $parent->students->pluck('id');
                })
                ->unique()
                ->values();

            if ($allowedStudentIds->isEmpty()) {
                abort(
                    403,
                    'Aucun enfant n’est associé à votre compte parent.'
                );
            }

            /*
             * On garde uniquement les enfants inscrits
             * dans l'année scolaire sélectionnée.
             */
            $allowedStudentIds = Inscription::query()
                ->where(
                    'establishment_id',
                    $establishment->id
                )
                ->where(
                    'school_year_id',
                    $schoolYearId
                )
                ->whereIn(
                    'student_id',
                    $allowedStudentIds
                )
                ->pluck('student_id')
                ->unique()
                ->values();
        }

        /*
         * Classes de l'établissement et de l'année scolaire.
         *
         * Pour un parent, seules les classes dans lesquelles
         * un de ses élèves est inscrit sont affichées.
         */
        $classesQuery = ClassRoom::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYearId);

        if ($allowedStudentIds !== null) {
            $classesQuery->whereHas('inscriptions', function ($query) use (
                $allowedStudentIds,
                $schoolYearId,
                $establishment
            ) {
                $query
                    ->where('establishment_id', $establishment->id)
                    ->where('school_year_id', $schoolYearId)
                    ->whereIn('student_id', $allowedStudentIds);
            });
        }

        /*
         * Sécurité enseignant :
         * uniquement les classes qui lui sont réellement affectées.
         */
        if ($teacher) {
            $teacherClassIds = $this->teacherClassIds(
                $teacher->id,
                $schoolYearId
            );

            $classesQuery->whereIn(
                'id',
                $teacherClassIds
            );
        }

        $classes = $classesQuery
            ->orderBy('nom_classe')
            ->get();

        $subjectsQuery = Subject::where(function ($query) use (
            $establishment
        ) {
            $query
                ->where(
                    'establishment_id',
                    $establishment->id
                )
                ->orWhereNull('establishment_id');
        })
            ->where(
                'school_year_id',
                $schoolYearId
            );

        /*
         * Sécurité enseignant :
         * uniquement les matières de ses affectations.
         */
        if ($teacher) {
            $teacherSubjectIds = TeacherSubject::query()
                ->where('teacher_id', $teacher->id)
                ->where('school_year_id', $schoolYearId)
                ->whereIn(
                    'class_room_id',
                    $this->teacherClassIds(
                        $teacher->id,
                        $schoolYearId
                    )
                )
                ->pluck('subject_id')
                ->filter()
                ->unique()
                ->values();

            $subjectsQuery->whereIn(
                'id',
                $teacherSubjectIds
            );
        }

        $subjects = $subjectsQuery
            ->orderBy('nom')
            ->get();

        $types = $this->evaluationTypes();

        $typesEvaluation = $types;

        $query = Note::with([
            'student',
            'subject',
            'teacher',
            'classRoom',
            'schoolYear',
        ])
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $schoolYearId
            );

        /*
         * Sécurité Enseignant :
         * un enseignant ne voit que ses propres notes.
         */
        if ($teacher) {
            $query->where(
                'teacher_id',
                $teacher->id
            );
        }

        /*
         * Sécurité Parent :
         * les notes sont limitées aux élèves rattachés
         * au compte parent dans parent_student.
         */
        if ($allowedStudentIds !== null) {
            $query->whereIn(
                'student_id',
                $allowedStudentIds
            );
        }

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->whereHas('student', function ($q) use (
                $search
            ) {
                $q->where(function ($studentQuery) use (
                    $search
                ) {
                    $studentQuery
                        ->where(
                            'nom',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'prenom',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'matricule',
                            'like',
                            "%{$search}%"
                        );
                });
            });
        }

        if ($request->filled('class_room_id')) {
            $query->where(
                'class_room_id',
                $request->class_room_id
            );
        }

        if ($request->filled('subject_id')) {
            $query->where(
                'subject_id',
                $request->subject_id
            );
        }

        if ($request->filled('type_evaluation')) {
            $query->where(
                'type_evaluation',
                $request->type_evaluation
            );
        }

        $statsQuery = clone $query;

        $totalNotes = (clone $statsQuery)->count();

        $averageNote = (clone $statsQuery)
            ->whereNotNull('note')
            ->avg('note');

        $studentsCount = (clone $statsQuery)
            ->distinct('student_id')
            ->count('student_id');

        $subjectsCount = (clone $statsQuery)
            ->distinct('subject_id')
            ->count('subject_id');

        $moyenne = $averageNote ?? 0;

        $totalEtudiants = $studentsCount;

        $totalMatieres = $subjectsCount;

        $notes = $query
            ->orderByDesc('date_evaluation')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('notes.index', compact(
            'notes',
            'schoolYears',
            'classes',
            'subjects',
            'types',
            'typesEvaluation',
            'totalNotes',
            'averageNote',
            'moyenne',
            'studentsCount',
            'totalEtudiants',
            'subjectsCount',
            'totalMatieres',
            'establishment'
        ));
    }

    /**
 * Formulaire de création.
 */
public function create(Request $request)
{
    $establishment = $this->currentEstablishment();

    /*
    |--------------------------------------------------------------------------
    | currentEstablishment() peut retourner une redirection
    |--------------------------------------------------------------------------
    */
    if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
        return $establishment;
    }

    /*
    |--------------------------------------------------------------------------
    | Années scolaires de l'établissement courant
    |--------------------------------------------------------------------------
    */
    $schoolYears = SchoolYear::where(
        'establishment_id',
        $establishment->id
    )
        ->orderByDesc('id')
        ->get();

    if ($schoolYears->isEmpty()) {
        return redirect()
            ->route('school-years.index')
            ->with(
                'error',
                'Aucune année scolaire n’est disponible pour cet établissement.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Année scolaire sélectionnée
    |--------------------------------------------------------------------------
    */
    $selectedSchoolYearId = $request->integer(
        'school_year_id'
    );

    if (!$selectedSchoolYearId) {
        $selectedSchoolYearId = session(
            'school_year_id'
        );
    }

    if (
        !$selectedSchoolYearId ||
        !$schoolYears->contains(
            'id',
            $selectedSchoolYearId
        )
    ) {
        $selectedSchoolYearId =
            $schoolYears->first()->id;
    }

    $selectedYear = $schoolYears->firstWhere(
        'id',
        $selectedSchoolYearId
    );

    /*
    |--------------------------------------------------------------------------
    | Mémoriser l'année scolaire
    |--------------------------------------------------------------------------
    */
    session([
        'school_year_id' => $selectedYear->id,
        'school_year_name' => $selectedYear->name,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Classes de l'établissement + année
    |--------------------------------------------------------------------------
    */
    $classesQuery = ClassRoom::where(
        'establishment_id',
        $establishment->id
    )
        ->where(
            'school_year_id',
            $selectedYear->id
        );

    /*
     * Sécurité enseignant :
     * uniquement les classes réellement enseignées.
     */
    if ($request->user()->role === 'enseignant') {
        $teacher = $this->currentTeacher();

        $teacherClassIds = $this->teacherClassIds(
            $teacher->id,
            $selectedYear->id
        );

        $classesQuery->whereIn(
            'id',
            $teacherClassIds
        );
    }

    $classes = $classesQuery
        ->orderBy('nom_classe')
        ->get();

    return view('notes.create', [
        'establishment' => $establishment,
        'schoolYears' => $schoolYears,
        'selectedYear' => $selectedYear,
        'selectedSchoolYearId' => $selectedSchoolYearId,
        'classes' => $classes,
    ]);
}

    /**
     * Classes par année scolaire.
     */
    public function classesBySchoolYear(Request $request)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Sécurité enseignant :
         *
         * L'enseignant connecté ne peut jamais fournir
         * librement le teacher_id d'un autre enseignant.
         */
        $teacher = null;

        if ($request->user()->role === 'enseignant') {
            $teacher = $this->currentTeacher();

            if (!$teacher) {
                abort(403, 'Aucun profil enseignant associé à ce compte.');
            }
        }

        $validated = $request->validate([
            'school_year_id' => [
                'required',
                'integer',
            ],
        ]);

        $schoolYear = SchoolYear::where(
            'id',
            $validated['school_year_id']
        )
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->firstOrFail();

        session([
            'school_year_id' => $schoolYear->id,
            'school_year_name' => $schoolYear->name,
        ]);

        $classesQuery = ClassRoom::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $schoolYear->id
            );

        /*
         * Sécurité enseignant :
         * l'AJAX ne retourne que ses classes.
         */
        if ($request->user()->role === 'enseignant') {
            $teacher = $this->currentTeacher();

            $classesQuery->whereIn(
                'id',
                $this->teacherClassIds(
                    $teacher->id,
                    $schoolYear->id
                )
            );
        }

        $classes = $classesQuery
            ->orderBy('nom_classe')
            ->get([
                'id',
                'nom_classe',
                'niveau',
            ]);

        return response()->json($classes);
    }

    /**
     * Étudiants par classe.
     */
    public function studentsByClass(Request $request)
    {
        $establishment = $this->currentEstablishment();

        // Initialiser le profil enseignant avant toute utilisation.
        $teacher = null;

        if ($request->user()->role === 'enseignant') {
            $teacher = $this->currentTeacher();

            if (!$teacher) {
                abort(
                    403,
                    'Aucun profil enseignant associé à ce compte.'
                );
            }
        }

        $validated = $request->validate([
            'class_room_id' => [
                'required',
                'integer',
            ],
            'school_year_id' => [
                'required',
                'integer',
            ],
        ]);

        $schoolYear = SchoolYear::where(
            'id',
            $validated['school_year_id']
        )
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->firstOrFail();

        if ($teacher) {
            $validated['teacher_id'] = $teacher->id;
        }

        $classRoom = ClassRoom::where(
            'id',
            $validated['class_room_id']
        )
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->firstOrFail();

        /*
         * Sécurité enseignant :
         * impossible de charger les élèves d'une classe
         * qui ne lui est pas affectée.
         */
        if ($request->user()->role === 'enseignant') {
            $teacher = $this->currentTeacher();

            $teacherClassIds = $this->teacherClassIds(
                $teacher->id,
                $schoolYear->id
            );

            if (!$teacherClassIds->contains(
                (int) $classRoom->id
            )) {
                abort(
                    403,
                    'Vous n’êtes pas affecté à cette classe.'
                );
            }
        }

        $studentIds = Inscription::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->pluck('student_id');

        $students = Student::where(
            'establishment_id',
            $establishment->id
        )
            ->whereIn('id', $studentIds)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get([
                'id',
                'matricule',
                'nom',
                'prenom',
            ]);

        return response()->json($students);
    }

    /**
     * Matières par classe.
     */
    public function subjectsByClass(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $validated = $request->validate([
            'class_room_id' => [
                'required',
                'integer',
            ],
            'school_year_id' => [
                'required',
                'integer',
            ],
        ]);

        $classRoom = ClassRoom::where(
            'id',
            $validated['class_room_id']
        )
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->firstOrFail();

        /*
         * Sécurité enseignant :
         * la classe sélectionnée doit appartenir à ses affectations.
         */
        $teacher = null;

        if ($request->user()->role === 'enseignant') {
            $teacher = $this->currentTeacher();

            $teacherClassIds = $this->teacherClassIds(
                $teacher->id,
                $validated['school_year_id']
            );

            if (!$teacherClassIds->contains(
                (int) $classRoom->id
            )) {
                abort(
                    403,
                    'Vous n’êtes pas affecté à cette classe.'
                );
            }
        }

        $teacherSubjectRowsQuery = TeacherSubject::with('subject')
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            );

        /*
         * Pour un enseignant, uniquement ses propres affectations.
         */
        if ($teacher) {
            $teacherSubjectRowsQuery->where(
                'teacher_id',
                $teacher->id
            );
        }

        $teacherSubjectRows = $teacherSubjectRowsQuery
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->whereHas('teacher', function ($query) use (
                $establishment
            ) {
                $query->where(
                    'establishment_id',
                    $establishment->id
                );
            })
            ->get();

        $classRoomSubjectRows = ClassRoomSubject::with('subject')
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->get();

        $subjects = collect();

        foreach ($teacherSubjectRows as $row) {

            if (!$row->subject) {
                continue;
            }

            $subjects->put(
                $row->subject->id,
                [
                    'id' => $row->subject->id,
                    'code' => $row->subject->code ?? null,
                    'nom' => $row->subject->nom,
                    'coefficient' =>
                        $row->coefficient
                        ?? $row->subject->coefficient
                        ?? 1,
                ]
            );
        }

        /*
         * Les matières génériques ClassRoomSubject restent disponibles
         * pour Admin/Parent.
         *
         * Pour un enseignant, on conserve uniquement les matières
         * issues de ses propres TeacherSubject.
         */
        if (!$teacher) {
            foreach ($classRoomSubjectRows as $row) {

                if (!$row->subject) {
                    continue;
                }

                if (!$subjects->has($row->subject->id)) {

                    $subjects->put(
                        $row->subject->id,
                        [
                            'id' => $row->subject->id,
                            'code' => $row->subject->code ?? null,
                            'nom' => $row->subject->nom,
                            'coefficient' =>
                                $row->coefficient
                                ?? $row->subject->coefficient
                                ?? 1,
                        ]
                    );
                }
            }
        }

        return response()->json(
            $subjects
                ->values()
                ->sortBy('nom')
                ->values()
        );
    }

    /**
     * Enseignants par matière.
     */
    public function teachersBySubject(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $validated = $request->validate([
            'class_room_id' => [
                'required',
                'integer',
            ],
            'subject_id' => [
                'required',
                'integer',
            ],
            'school_year_id' => [
                'required',
                'integer',
            ],
        ]);

        $classRoom = ClassRoom::where(
            'id',
            $validated['class_room_id']
        )
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->firstOrFail();

        /*
         * Sécurité enseignant :
         * il ne peut consulter que sa propre affectation.
         */
        $teacherQuery = TeacherSubject::with('teacher')
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->whereHas('teacher', function ($query) use (
                $establishment
            ) {
                $query->where(
                    'establishment_id',
                    $establishment->id
                );
            });

        if ($request->user()->role === 'enseignant') {
            $teacher = $this->currentTeacher();

            $teacherQuery->where(
                'teacher_id',
                $teacher->id
            );
        }

        $teachers = $teacherQuery
            ->get()
            ->map(function ($row) {

                if (!$row->teacher) {
                    return null;
                }

                return [
                    'id' => $row->teacher->id,
                    'nom' => $row->teacher->nom,
                    'prenom' => $row->teacher->prenom,
                ];
            })
            ->filter()
            ->unique('id')
            ->values();

        return response()->json($teachers);
    }

    /**
     * Enregistrer une note.
     */
    public function store(Request $request)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Enseignant connecté.
         *
         * Pour un enseignant, teacher_id sera toujours remplacé
         * par son propre profil, même si une autre valeur est
         * envoyée manuellement dans la requête.
         */
        $teacher = null;

        if ($request->user()->role === 'enseignant') {
            $teacher = $this->currentTeacher();

            if (!$teacher) {
                abort(
                    403,
                    'Aucun profil enseignant associé à ce compte.'
                );
            }

            // Sécurité : utiliser systématiquement le profil
            // de l'enseignant connecté, jamais un autre teacher_id.
            $request->merge([
                'teacher_id' => $teacher->id,
            ]);
        }

        $validated = $request->validate([
            'school_year_id' => [
                'required',
                'integer',
                Rule::exists('school_years', 'id')
                    ->where(function ($query) use (
                        $establishment
                    ) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }),
            ],

            'class_room_id' => [
                'required',
                'integer',
                Rule::exists('class_rooms', 'id')
                    ->where(function ($query) use (
                        $establishment
                    ) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }),
            ],

            'student_id' => [
                'required',
                'integer',
                Rule::exists('students', 'id')
                    ->where(function ($query) use (
                        $establishment
                    ) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }),
            ],

            'subject_id' => [
                'required',
                'integer',
                Rule::exists('subjects', 'id')
                    ->where(function ($query) use (
                        $establishment
                    ) {
                        $query->where(function ($q) use (
                            $establishment
                        ) {
                            $q->where(
                                'establishment_id',
                                $establishment->id
                            )
                                ->orWhereNull(
                                    'establishment_id'
                                );
                        });
                    }),
            ],

            'teacher_id' => [
                'required',
                'integer',
                Rule::exists('teachers', 'id')
                    ->where(function ($query) use (
                        $establishment
                    ) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }),
            ],

            'note' => [
                'required',
                'numeric',
                'min:0',
                'max:20',
            ],

            'coefficient' => [
                'required',
                'numeric',
                'min:0.01',
                'max:100',
            ],

            'type_evaluation' => [
                'required',
                Rule::in(
                    $this->evaluationTypes()
                ),
            ],

            'date_evaluation' => [
                'required',
                'date',
            ],
        ]);

        /*
         * Sécurité critique :
         * un enseignant ne peut jamais choisir un autre teacher_id.
         */
        if ($teacher) {
            $validated['teacher_id'] = $teacher->id;
        }

        $classRoom = ClassRoom::where(
            'id',
            $validated['class_room_id']
        )
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->firstOrFail();

        if (
            (int) $classRoom->school_year_id !==
            (int) $validated['school_year_id']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        'La classe sélectionnée ne correspond pas à l’année scolaire.'
                ]);
        }

        $inscriptionExists = Inscription::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'student_id',
                $validated['student_id']
            )
            ->where(
                'class_room_id',
                $validated['class_room_id']
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->exists();

        if (!$inscriptionExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'Cet étudiant n’est pas inscrit dans cette classe pour cette année scolaire.'
                ]);
        }

        $subjectExists = ClassRoomSubject::where(
            'class_room_id',
            $validated['class_room_id']
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->exists();

        $teacherSubjectExists = TeacherSubject::where(
            'class_room_id',
            $validated['class_room_id']
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->whereHas('teacher', function ($query) use (
                $establishment
            ) {
                $query->where(
                    'establishment_id',
                    $establishment->id
                );
            })
            ->exists();

        if (
            !$subjectExists &&
            !$teacherSubjectExists
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'Cette matière n’est pas affectée à cette classe.'
                ]);
        }

        $teacherAssigned = $this->teacherAssignmentExists(
            (int) $validated['teacher_id'],
            (int) $validated['class_room_id'],
            (int) $validated['subject_id'],
            (int) $validated['school_year_id'],
            (int) $establishment->id
        );

        if (!$teacherAssigned) {
            return back()
                ->withInput()
                ->withErrors([
                    'teacher_id' =>
                        'Cet enseignant n’est pas affecté à cette matière dans cette classe pour cette année scolaire.'
                ]);
        }

        $duplicate = Note::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->where(
                'class_room_id',
                $validated['class_room_id']
            )
            ->where(
                'student_id',
                $validated['student_id']
            )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'type_evaluation',
                $validated['type_evaluation']
            )
            ->where(
                'date_evaluation',
                $validated['date_evaluation']
            )
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'note' =>
                        'Une note existe déjà pour cet étudiant, cette matière, ce type et cette date.'
                ]);
        }

        Note::create([
            'establishment_id' =>
                $establishment->id,

            'teacher_id' =>
                $validated['teacher_id'],

            'student_id' =>
                $validated['student_id'],

            'subject_id' =>
                $validated['subject_id'],

            'class_room_id' =>
                $validated['class_room_id'],

            'school_year_id' =>
                $validated['school_year_id'],

            'note' =>
                $validated['note'],

            'coefficient' =>
                $validated['coefficient'],

            'type_evaluation' =>
                $validated['type_evaluation'],

            'date_evaluation' =>
                $validated['date_evaluation'],
        ]);

        return redirect()
            ->route('notes.index')
            ->with(
                'success',
                'La note a été enregistrée avec succès.'
            );
    }

    /**
     * Afficher une note.
     */
    public function show(Note $note)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Sécurité établissement + année scolaire.
         */
        $noteQuery = Note::with([
            'student',
            'subject',
            'teacher',
            'classRoom',
            'schoolYear',
        ])
            ->where(
                'establishment_id',
                $establishment->id
            );

        /*
         * Sécurité Parent :
         *
         * Un parent ne peut consulter que les notes
         * des élèves inscrits dans l'établissement et
         * l'année scolaire sélectionnée.
         */
        if (request()->user()->role === 'parent') {
            $sessionSchoolYearId = session('school_year_id');

            /*
             * Securite Parent :
             * seuls les enfants rattaches au compte parent
             * dans l'etablissement courant sont autorises.
             */
            $parentProfiles = ParentModel::query()
                ->with('students')
                ->where('user_id', Auth::id())
                ->where('establishment_id', $establishment->id)
                ->get();

            if ($parentProfiles->isEmpty()) {
                abort(
                    403,
                    'Compte parent introuvable pour cet etablissement.'
                );
            }

            $allowedStudentIds = $parentProfiles
                ->flatMap(function ($parent) {
                    return $parent->students->pluck('id');
                })
                ->unique()
                ->values();

            if ($allowedStudentIds->isEmpty()) {
                abort(
                    403,
                    'Aucun enfant n’est associe a votre compte parent.'
                );
            }

            $noteQuery->whereIn(
                'student_id',
                $allowedStudentIds
            );
        }

        /*
         * La note est récupérée uniquement après
         * application de toutes les restrictions.
         */
        $note = $noteQuery->findOrFail($note->id);

        /*
         * Sécurité enseignant :
         *
         * Un enseignant ne peut consulter que ses propres notes.
         */
        $this->authorizeTeacherNote($note);

        /*
         * Vérification supplémentaire de l'année scolaire
         * par rapport à la session courante.
         */
        $sessionSchoolYearId = session('school_year_id');

        if (
            $sessionSchoolYearId &&
            (int) $note->school_year_id !== (int) $sessionSchoolYearId
        ) {
            abort(
                404,
                'Cette note n’appartient pas à l’année scolaire sélectionnée.'
            );
        }

        return view('notes.show', compact(
            'note',
            'establishment'
        ));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Note $note)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Sécurité Parent :
         *
         * Un parent peut consulter ses notes,
         * mais ne peut jamais modifier une note.
         */
        if (request()->user()->role === 'parent') {
            abort(
                403,
                'Les comptes parents peuvent consulter les notes, mais ne peuvent pas les modifier.'
            );
        }

        $note = Note::with([
            'student',
            'subject',
            'teacher',
            'classRoom',
            'schoolYear',
        ])
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->findOrFail($note->id);

        /*
         * Sécurité enseignant :
         *
         * Un enseignant ne peut modifier que ses propres notes.
         */
        $this->authorizeTeacherNote($note);

        $schoolYears = SchoolYear::where(
            'establishment_id',
            $establishment->id
        )
            ->orderByDesc('id')
            ->get();

        $selectedYear = $schoolYears->firstWhere(
            'id',
            $note->school_year_id
        );

        $classesQuery = ClassRoom::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $note->school_year_id
            );

        if (request()->user()->role === 'enseignant') {
            $teacher = $this->currentTeacher();

            $classesQuery->whereIn(
                'id',
                $this->teacherClassIds(
                    $teacher->id,
                    (int) $note->school_year_id
                )
            );
        }

        $classes = $classesQuery
            ->orderBy('nom_classe')
            ->get();

        $studentIds = Inscription::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'class_room_id',
                $note->class_room_id
            )
            ->where(
                'school_year_id',
                $note->school_year_id
            )
            ->pluck('student_id');

        $students = Student::where(
            'establishment_id',
            $establishment->id
        )
            ->whereIn('id', $studentIds)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        if (
            $note->student &&
            !$students->contains(
                'id',
                $note->student_id
            )
        ) {
            $students->push($note->student);
        }

        $teacherSubjectRowsQuery = TeacherSubject::with('subject')
            ->where(
                'class_room_id',
                $note->class_room_id
            )
            ->where(
                'school_year_id',
                $note->school_year_id
            )
            ->whereHas('teacher', function ($query) use (
                $establishment
            ) {
                $query->where(
                    'establishment_id',
                    $establishment->id
                );
            });

        if (request()->user()->role === 'enseignant') {
            $teacher = $this->currentTeacher();

            $teacherSubjectRowsQuery->where(
                'teacher_id',
                $teacher->id
            );
        }

        $teacherSubjectRows = $teacherSubjectRowsQuery->get();

        $classRoomSubjectRows = ClassRoomSubject::with('subject')
            ->where(
                'class_room_id',
                $note->class_room_id
            )
            ->get();

        $subjects = collect();

        foreach ($teacherSubjectRows as $row) {

            if (!$row->subject) {
                continue;
            }

            $subjects->put(
                $row->subject->id,
                [
                    'id' =>
                        $row->subject->id,
                    'code' =>
                        $row->subject->code ?? null,
                    'nom' =>
                        $row->subject->nom,
                    'coefficient' =>
                        $row->coefficient
                        ?? $row->subject->coefficient
                        ?? 1,
                ]
            );
        }

        if (request()->user()->role !== 'enseignant') {
            foreach ($classRoomSubjectRows as $row) {

                if (!$row->subject) {
                    continue;
                }

                if (!$subjects->has($row->subject->id)) {

                    $subjects->put(
                        $row->subject->id,
                        [
                            'id' =>
                                $row->subject->id,
                            'code' =>
                                $row->subject->code
                                ?? null,
                            'nom' =>
                                $row->subject->nom,
                            'coefficient' =>
                                $row->coefficient
                                ?? $row->subject->coefficient
                                ?? 1,
                        ]
                    );
                }
            }
        }

        if (
            $note->subject &&
            !$subjects->has($note->subject_id)
        ) {
            $subjects->put(
                $note->subject_id,
                [
                    'id' =>
                        $note->subject_id,
                    'code' =>
                        $note->subject->code
                        ?? null,
                    'nom' =>
                        $note->subject->nom,
                    'coefficient' =>
                        $note->coefficient
                        ?? 1,
                ]
            );
        }

        $subjects = $subjects
            ->values()
            ->sortBy('nom')
            ->values();

        $teachersQuery = TeacherSubject::with('teacher')
            ->where(
                'class_room_id',
                $note->class_room_id
            )
            ->where(
                'subject_id',
                $note->subject_id
            )
            ->where(
                'school_year_id',
                $note->school_year_id
            )
            ->whereHas('teacher', function ($query) use (
                $establishment
            ) {
                $query->where(
                    'establishment_id',
                    $establishment->id
                );
            });

        if (request()->user()->role === 'enseignant') {
            $teacher = $this->currentTeacher();

            $teachersQuery->where(
                'teacher_id',
                $teacher->id
            );
        }

        $teachers = $teachersQuery
            ->get()
            ->map(function ($row) {

                if (!$row->teacher) {
                    return null;
                }

                return $row->teacher;
            })
            ->filter()
            ->unique('id')
            ->values();

        if (
            $note->teacher &&
            !$teachers->contains(
                'id',
                $note->teacher_id
            )
        ) {
            $teachers->push(
                $note->teacher
            );
        }

        return view('notes.edit', [
            'note' => $note,
            'establishment' => $establishment,
            'schoolYears' => $schoolYears,
            'selectedYear' => $selectedYear,
            'selectedSchoolYearId' => $note->school_year_id,
            'classes' => $classes,
            'students' => $students,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'types' => $this->evaluationTypes(),
        ]);
    }

    /**
     * Modifier une note.
     */
    public function update(Request $request, Note $note)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Sécurité Parent :
         *
         * Blocage côté serveur indispensable.
         * Même si un parent tente d'appeler directement
         * la route PUT/PATCH de modification d'une note,
         * aucune modification ne doit être effectuée.
         */
        if ($request->user()->role === 'parent') {
            abort(
                403,
                'Les comptes parents ne peuvent pas modifier les notes.'
            );
        }

        $note = Note::where(
            'establishment_id',
            $establishment->id
        )
            ->findOrFail($note->id);

        $validated = $request->validate([
            'school_year_id' => [
                'required',
                'integer',
                Rule::exists('school_years', 'id')
                    ->where(function ($query) use (
                        $establishment
                    ) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }),
            ],

            'class_room_id' => [
                'required',
                'integer',
                Rule::exists('class_rooms', 'id')
                    ->where(function ($query) use (
                        $establishment
                    ) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }),
            ],

            'student_id' => [
                'required',
                'integer',
                Rule::exists('students', 'id')
                    ->where(function ($query) use (
                        $establishment
                    ) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }),
            ],

            'subject_id' => [
                'required',
                'integer',
                Rule::exists('subjects', 'id')
                    ->where(function ($query) use (
                        $establishment
                    ) {
                        $query->where(function ($q) use (
                            $establishment
                        ) {
                            $q->where(
                                'establishment_id',
                                $establishment->id
                            )
                                ->orWhereNull(
                                    'establishment_id'
                                );
                        });
                    }),
            ],

            'teacher_id' => [
                'nullable',
                'integer',
                Rule::exists('teachers', 'id')
                    ->where(function ($query) use (
                        $establishment
                    ) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }),
            ],

            'note' => [
                'required',
                'numeric',
                'min:0',
                'max:20',
            ],

            'coefficient' => [
                'required',
                'numeric',
                'min:0.01',
                'max:100',
            ],

            'type_evaluation' => [
                'nullable',
                Rule::in(
                    $this->evaluationTypes()
                ),
            ],

            'date_evaluation' => [
                'nullable',
                'date',
            ],
        ]);

        $classRoom = ClassRoom::where(
            'id',
            $validated['class_room_id']
        )
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->firstOrFail();

        if (
            (int) $classRoom->school_year_id !==
            (int) $validated['school_year_id']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        'La classe sélectionnée ne correspond pas à l’année scolaire.'
                ]);
        }

        $inscriptionExists = Inscription::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'student_id',
                $validated['student_id']
            )
            ->where(
                'class_room_id',
                $validated['class_room_id']
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->exists();

        if (!$inscriptionExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'Cet étudiant n’est pas inscrit dans cette classe pour cette année scolaire.'
                ]);
        }

        $subjectExists = ClassRoomSubject::where(
            'class_room_id',
            $validated['class_room_id']
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->exists();

        $teacherSubjectExists = TeacherSubject::where(
            'class_room_id',
            $validated['class_room_id']
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->whereHas('teacher', function ($query) use (
                $establishment
            ) {
                $query->where(
                    'establishment_id',
                    $establishment->id
                );
            })
            ->exists();

        if (
            !$subjectExists &&
            !$teacherSubjectExists
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'Cette matière n’est pas affectée à cette classe.'
                ]);
        }

        if (!empty($validated['teacher_id'])) {

            $teacherAssigned = $this->teacherAssignmentExists(
                (int) $validated['teacher_id'],
                (int) $validated['class_room_id'],
                (int) $validated['subject_id'],
                (int) $validated['school_year_id'],
                (int) $establishment->id
            );

            if (!$teacherAssigned) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Cet enseignant n’est pas affecté à cette matière dans cette classe pour cette année scolaire.'
                    ]);
            }
        }

        $duplicateQuery = Note::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->where(
                'class_room_id',
                $validated['class_room_id']
            )
            ->where(
                'student_id',
                $validated['student_id']
            )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'type_evaluation',
                $validated['type_evaluation']
            )
            ->where(
                'date_evaluation',
                $validated['date_evaluation']
            )
            ->where(
                'id',
                '!=',
                $note->id
            );

        if ($duplicateQuery->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'note' =>
                        'Une autre note existe déjà avec les mêmes informations.'
                ]);
        }

        if ($teacher && empty($validated['teacher_id'])) {
            abort(
                403,
                'Votre compte enseignant doit être associé à cette note.'
            );
        }

        $note->update([
            'teacher_id' =>
                $validated['teacher_id'] ?? null,

            'student_id' =>
                $validated['student_id'],

            'subject_id' =>
                $validated['subject_id'],

            'class_room_id' =>
                $validated['class_room_id'],

            'school_year_id' =>
                $validated['school_year_id'],

            'note' =>
                $validated['note'],

            'coefficient' =>
                $validated['coefficient'],

            'type_evaluation' =>
                $validated['type_evaluation'] ?? null,

            'date_evaluation' =>
                $validated['date_evaluation'] ?? null,
        ]);

        return redirect()
            ->route('notes.index')
            ->with(
                'success',
                'La note a été modifiée avec succès.'
            );
    }

    /**
     * Supprimer une note.
     */
    public function destroy(Note $note)
    {
        $establishment = $this->currentEstablishment();

        /*
         * Sécurité Parent :
         *
         * Un parent ne peut jamais supprimer une note.
         * La protection est effectuée côté serveur avant
         * toute récupération ou suppression de la note.
         */
        if ($request = request()) {
            if ($request->user()->role === 'parent') {
                abort(
                    403,
                    'Les comptes parents ne peuvent pas supprimer les notes.'
                );
            }
        }

        $note = Note::where(
            'establishment_id',
            $establishment->id
        )
            ->findOrFail($note->id);

        /*
         * Sécurité enseignant :
         *
         * Un enseignant ne peut supprimer que ses propres notes.
         */
        $this->authorizeTeacherNote($note);

        $note->delete();

        return redirect()
            ->route('notes.index')
            ->with(
                'success',
                'La note a été supprimée avec succès.'
            );
    }
}