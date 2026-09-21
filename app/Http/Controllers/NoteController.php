<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\ClassRoomSubject;
use App\Models\Establishment;
use App\Models\Inscription;
use App\Models\Note;
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
     * Retourne l'établissement courant de l'utilisateur.
     */
    private function currentEstablishment()
    {
        if (!Auth::check()) {
            abort(403, 'Vous devez être connecté.');
        }

        /*
        |--------------------------------------------------------------------------
        | Établissement déjà présent en session
        |--------------------------------------------------------------------------
        */
        $sessionId = session('establishment_id');

        if ($sessionId) {
            $establishment = Establishment::where('id', $sessionId)
                ->where('user_id', Auth::id())
                ->first();

            if ($establishment) {
                session([
                    'establishment_id' => $establishment->id,
                    'establishment_name' => $establishment->nom,
                ]);

                return $establishment;
            }

            session()->forget([
                'establishment_id',
                'establishment_name',
                'school_year_id',
                'school_year_name',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Établissements de l'utilisateur
        |--------------------------------------------------------------------------
        */
        $establishments = Establishment::where(
            'user_id',
            Auth::id()
        )
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Aucun établissement
        |--------------------------------------------------------------------------
        */
        if ($establishments->isEmpty()) {
            return redirect()->route('establishments.create');
        }

        /*
        |--------------------------------------------------------------------------
        | Plusieurs établissements
        |--------------------------------------------------------------------------
        */
        if ($establishments->count() > 1) {
            return redirect()->route('establishments.choose');
        }

        /*
        |--------------------------------------------------------------------------
        | Un seul établissement
        |--------------------------------------------------------------------------
        */
        $establishment = $establishments->first();

        session([
            'establishment_id' => $establishment->id,
            'establishment_name' => $establishment->nom,
        ]);

        return $establishment;
    }

    /**
     * Établissement courant.
     */
    private function getEstablishment()
    {
        return $this->currentEstablishment();
    }

    /**
     * Vérifie qu'un établissement appartient bien à l'utilisateur.
     */
    private function checkEstablishment($establishmentId)
    {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
        }

        if ((int) $establishment->id !== (int) $establishmentId) {
            abort(
                403,
                'Accès interdit à cet établissement.'
            );
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
        }

        $notes = Note::with([
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
            ->latest('id')
            ->paginate(20);

        return view(
            'notes.index',
            compact(
                'notes',
                'establishment'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
        }

        /*
        |--------------------------------------------------------------------------
        | Années scolaires
        |--------------------------------------------------------------------------
        */
        $schoolYears = SchoolYear::where(
            'establishment_id',
            $establishment->id
        )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        if ($schoolYears->isEmpty()) {
            return redirect()
                ->route('school-years.index')
                ->with(
                    'error',
                    'Veuillez créer une année scolaire avant de saisir des notes.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Déterminer l'année scolaire
        |--------------------------------------------------------------------------
        |
        | Priorité :
        | 1. ?school_year_id
        | 2. session
        | 3. année active
        | 4. première année
        |
        */
        $selectedYearId = $request->integer(
            'school_year_id'
        );

        if (!$selectedYearId) {
            $selectedYearId = session(
                'school_year_id'
            );
        }

        $selectedYear = null;

        if ($selectedYearId) {
            $selectedYear = $schoolYears->firstWhere(
                'id',
                $selectedYearId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Année active
        |--------------------------------------------------------------------------
        */
        if (!$selectedYear) {
            $selectedYear = $schoolYears->firstWhere(
                'is_active',
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Première année si aucune année active
        |--------------------------------------------------------------------------
        */
        if (!$selectedYear) {
            $selectedYear = $schoolYears->first();
        }

        /*
        |--------------------------------------------------------------------------
        | ID utilisé par Blade
        |--------------------------------------------------------------------------
        */
        $selectedSchoolYearId = $selectedYear->id;

        /*
        |--------------------------------------------------------------------------
        | Sauvegarder en session
        |--------------------------------------------------------------------------
        */
        session([
            'school_year_id' => $selectedYear->id,
            'school_year_name' => $selectedYear->name,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Classes de l'année sélectionnée
        |--------------------------------------------------------------------------
        */
        $classes = ClassRoom::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $selectedYear->id
            )
            ->orderBy('niveau')
            ->orderBy('nom_classe')
            ->get();

        return view(
            'notes.create',
            compact(
                'establishment',
                'schoolYears',
                'selectedYear',
                'selectedSchoolYearId',
                'classes'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASSES PAR ANNÉE
    |--------------------------------------------------------------------------
    */

    public function classesBySchoolYear(Request $request)
    {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
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

        $classes = ClassRoom::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->orderBy('niveau')
            ->orderBy('nom_classe')
            ->get([
                'id',
                'nom_classe',
                'niveau',
                'school_year_id',
                'establishment_id',
            ]);

        return response()->json($classes);
    }

    /*
    |--------------------------------------------------------------------------
    | ÉTUDIANTS PAR CLASSE
    |--------------------------------------------------------------------------
    */

    public function studentsByClass(Request $request)
    {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
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

        /*
        |--------------------------------------------------------------------------
        | Vérifier l'année
        |--------------------------------------------------------------------------
        */
        $schoolYear = SchoolYear::where(
            'id',
            $validated['school_year_id']
        )
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Vérifier la classe
        |--------------------------------------------------------------------------
        */
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
        |--------------------------------------------------------------------------
        | IDs étudiants inscrits dans cette classe
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Étudiants
        |--------------------------------------------------------------------------
        */
        $students = Student::where(
            'establishment_id',
            $establishment->id
        )
            ->whereIn(
                'id',
                $studentIds
            )
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

    /*
    |--------------------------------------------------------------------------
    | MATIÈRES PAR CLASSE
    |--------------------------------------------------------------------------
    */

    public function subjectsByClass(Request $request)
    {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
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

        /*
        |--------------------------------------------------------------------------
        | Vérifier classe
        |--------------------------------------------------------------------------
        */
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
        |--------------------------------------------------------------------------
        | Matières via TeacherSubject
        |--------------------------------------------------------------------------
        */
        $teacherSubjects = TeacherSubject::with([
            'subject',
        ])
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'school_year_id',
                $validated['school_year_id']
            )
            ->whereHas(
                'teacher',
                function ($query) use ($establishment) {
                    $query->where(
                        'establishment_id',
                        $establishment->id
                    );
                }
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Matières via ClassRoomSubject
        |--------------------------------------------------------------------------
        */
        $classSubjects = ClassRoomSubject::with([
            'subject',
        ])
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Fusion des deux sources
        |--------------------------------------------------------------------------
        */
        $subjects = collect();

        foreach ($teacherSubjects as $teacherSubject) {
            if (!$teacherSubject->subject) {
                continue;
            }

            $subject = $teacherSubject->subject;

            $coefficient = $classSubjects->firstWhere(
                'subject_id',
                $subject->id
            );

            $subjects->put(
                $subject->id,
                [
                    'id' => $subject->id,
                    'code' => $subject->code,
                    'nom' => $subject->nom,
                    'coefficient' => $coefficient
                        ? (float) $coefficient->coefficient
                        : 1,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ClassRoomSubject non présent dans TeacherSubject
        |--------------------------------------------------------------------------
        */
        foreach ($classSubjects as $classSubject) {
            if (!$classSubject->subject) {
                continue;
            }

            $subject = $classSubject->subject;

            if (!$subjects->has($subject->id)) {
                $subjects->put(
                    $subject->id,
                    [
                        'id' => $subject->id,
                        'code' => $subject->code,
                        'nom' => $subject->nom,
                        'coefficient' => (float) (
                            $classSubject->coefficient ?? 1
                        ),
                    ]
                );
            }
        }

        return response()->json(
            $subjects
                ->values()
                ->sortBy('nom')
                ->values()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ENSEIGNANTS PAR MATIÈRE
    |--------------------------------------------------------------------------
    */

    public function teachersBySubject(Request $request)
    {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
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
            'school_year_id' => [
                'required',
                'integer',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Vérifier la classe
        |--------------------------------------------------------------------------
        */
        ClassRoom::where(
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
        |--------------------------------------------------------------------------
        | Enseignants affectés
        |--------------------------------------------------------------------------
        */
        $teachers = TeacherSubject::with([
            'teacher',
        ])
            ->where(
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
            ->whereHas(
                'teacher',
                function ($query) use ($establishment) {
                    $query->where(
                        'establishment_id',
                        $establishment->id
                    );
                }
            )
            ->get()
            ->filter(
                fn ($item) => $item->teacher !== null
            )
            ->map(
                function ($item) {
                    return [
                        'id' => $item->teacher->id,
                        'nom' => $item->teacher->nom,
                        'prenom' => $item->teacher->prenom,
                    ];
                }
            )
            ->unique('id')
            ->values();

        return response()->json($teachers);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
        }

        $validated = $request->validate([
            'school_year_id' => [
                'required',
                'integer',
                Rule::exists('school_years', 'id')
                    ->where(
                        'establishment_id',
                        $establishment->id
                    ),
            ],

            'class_room_id' => [
                'required',
                'integer',
                Rule::exists('class_rooms', 'id')
                    ->where(
                        'establishment_id',
                        $establishment->id
                    ),
            ],

            'student_id' => [
                'required',
                'integer',
                Rule::exists('students', 'id')
                    ->where(
                        'establishment_id',
                        $establishment->id
                    ),
            ],

            'subject_id' => [
                'required',
                'integer',
                Rule::exists('subjects', 'id')
                    ->where(
                        'establishment_id',
                        $establishment->id
                    ),
            ],

            'teacher_id' => [
                'nullable',
                'integer',
                Rule::exists('teachers', 'id')
                    ->where(
                        'establishment_id',
                        $establishment->id
                    ),
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
                'string',
                'max:100',
            ],

            'date_evaluation' => [
                'nullable',
                'date',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Vérifier l'année
        |--------------------------------------------------------------------------
        */
        $schoolYear = SchoolYear::where(
            'id',
            $validated['school_year_id']
        )
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Vérifier classe + année
        |--------------------------------------------------------------------------
        */
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
            ->first();

        if (!$classRoom) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        'La classe sélectionnée ne correspond pas à l’année scolaire sélectionnée.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier étudiant inscrit
        |--------------------------------------------------------------------------
        */
        $inscription = Inscription::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'student_id',
                $validated['student_id']
            )
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->first();

        if (!$inscription) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'Cet étudiant n’est pas inscrit dans cette classe pour cette année scolaire.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier matière affectée à la classe
        |--------------------------------------------------------------------------
        */
        $teacherSubjectQuery = TeacherSubject::where(
            'class_room_id',
            $classRoom->id
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->whereHas(
                'teacher',
                function ($query) use ($establishment) {
                    $query->where(
                        'establishment_id',
                        $establishment->id
                    );
                }
            );

        $hasTeacherSubject = $teacherSubjectQuery->exists();

        $hasClassSubject = ClassRoomSubject::where(
            'class_room_id',
            $classRoom->id
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->exists();

        if (!$hasTeacherSubject && !$hasClassSubject) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'Cette matière n’est pas affectée à cette classe pour cette année scolaire.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier enseignant
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['teacher_id'])) {
            $teacherAssigned = TeacherSubject::where(
                'teacher_id',
                $validated['teacher_id']
            )
                ->where(
                    'subject_id',
                    $validated['subject_id']
                )
                ->where(
                    'class_room_id',
                    $classRoom->id
                )
                ->where(
                    'school_year_id',
                    $schoolYear->id
                )
                ->whereHas(
                    'teacher',
                    function ($query) use ($establishment) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }
                )
                ->exists();

            if (!$teacherAssigned) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Cet enseignant n’est pas affecté à cette matière dans cette classe.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Éviter un doublon exact
        |--------------------------------------------------------------------------
        */
        $duplicate = Note::where(
            'establishment_id',
            $establishment->id
        )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->where(
                'class_room_id',
                $classRoom->id
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
                $validated['type_evaluation'] ?? null
            )
            ->where(
                'date_evaluation',
                $validated['date_evaluation'] ?? null
            )
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'note' =>
                        'Une note existe déjà pour cet étudiant, cette matière, cette évaluation et cette date.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Créer la note
        |--------------------------------------------------------------------------
        */
        Note::create([
            'establishment_id' => $establishment->id,
            'teacher_id' => $validated['teacher_id'] ?? null,
            'student_id' => $validated['student_id'],
            'subject_id' => $validated['subject_id'],
            'class_room_id' => $classRoom->id,
            'school_year_id' => $schoolYear->id,
            'note' => $validated['note'],
            'coefficient' => $validated['coefficient'],
            'type_evaluation' => $validated['type_evaluation'] ?? null,
            'date_evaluation' => $validated['date_evaluation'] ?? null,
        ]);

        return redirect()
            ->route('notes.index')
            ->with(
                'success',
                'La note a été enregistrée avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(Note $note)
    {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
        }

        if (
            (int) $note->establishment_id !==
            (int) $establishment->id
        ) {
            abort(
                403,
                'Accès interdit à cette note.'
            );
        }

        $note->load([
            'student',
            'subject',
            'teacher',
            'classRoom',
            'schoolYear',
        ]);

        return view(
            'notes.show',
            compact(
                'note',
                'establishment'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Note $note)
    {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier que la note appartient à l'établissement courant
        |--------------------------------------------------------------------------
        */
        if (
            (int) $note->establishment_id !==
            (int) $establishment->id
        ) {
            abort(
                403,
                'Accès interdit à cette note.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Charger les relations
        |--------------------------------------------------------------------------
        */
        $note->load([
            'student',
            'subject',
            'teacher',
            'classRoom',
            'schoolYear',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Années scolaires
        |--------------------------------------------------------------------------
        */
        $schoolYears = SchoolYear::where(
            'establishment_id',
            $establishment->id
        )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Année scolaire sélectionnée
        |--------------------------------------------------------------------------
        */
        $selectedYear = $schoolYears->firstWhere(
            'id',
            $note->school_year_id
        );

        if (!$selectedYear) {
            $selectedYear = $schoolYears->first();
        }

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT :
        | Variable utilisée par notes/edit.blade.php
        |--------------------------------------------------------------------------
        */
        $selectedSchoolYearId = $selectedYear
            ? $selectedYear->id
            : null;

        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */
        $classes = collect();

        if ($selectedYear) {
            $classes = ClassRoom::where(
                'establishment_id',
                $establishment->id
            )
                ->where(
                    'school_year_id',
                    $selectedYear->id
                )
                ->orderBy('niveau')
                ->orderBy('nom_classe')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Étudiants inscrits dans la classe
        |--------------------------------------------------------------------------
        */
        $students = collect();

        if (
            $note->class_room_id &&
            $note->school_year_id
        ) {
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
                ->whereIn(
                    'id',
                    $studentIds
                )
                ->orderBy('nom')
                ->orderBy('prenom')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Garder l'étudiant actuel s'il n'est plus dans l'inscription
            |--------------------------------------------------------------------------
            */
            if (
                $note->student &&
                !$students->contains(
                    'id',
                    $note->student_id
                )
            ) {
                $students->push(
                    $note->student
                );

                $students = $students
                    ->sortBy(
                        fn ($student) =>
                            $student->nom . ' ' . $student->prenom
                    )
                    ->values();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Matières
        |--------------------------------------------------------------------------
        */
        $subjects = collect();

        if (
            $note->class_room_id &&
            $note->school_year_id
        ) {
            /*
            | Matières affectées avec enseignant
            */
            $teacherSubjects = TeacherSubject::with(
                'subject'
            )
                ->where(
                    'class_room_id',
                    $note->class_room_id
                )
                ->where(
                    'school_year_id',
                    $note->school_year_id
                )
                ->whereHas(
                    'teacher',
                    function ($query) use ($establishment) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }
                )
                ->get();

            /*
            | Matières directement affectées à la classe
            */
            $classSubjects = ClassRoomSubject::with(
                'subject'
            )
                ->where(
                    'class_room_id',
                    $note->class_room_id
                )
                ->get();

            /*
            | TeacherSubject
            */
            foreach ($teacherSubjects as $teacherSubject) {
                if ($teacherSubject->subject) {
                    $subjects->put(
                        $teacherSubject->subject->id,
                        $teacherSubject->subject
                    );
                }
            }

            /*
            | ClassRoomSubject
            */
            foreach ($classSubjects as $classSubject) {
                if ($classSubject->subject) {
                    $subjects->put(
                        $classSubject->subject->id,
                        $classSubject->subject
                    );
                }
            }

            /*
            | Garder la matière actuelle
            */
            if (
                $note->subject &&
                !$subjects->has($note->subject_id)
            ) {
                $subjects->put(
                    $note->subject->id,
                    $note->subject
                );
            }

            $subjects = $subjects
                ->sortBy('nom')
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Enseignants
        |--------------------------------------------------------------------------
        */
        $teachers = collect();

        if (
            $note->class_room_id &&
            $note->subject_id &&
            $note->school_year_id
        ) {
            $teachers = TeacherSubject::with(
                'teacher'
            )
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
                ->whereHas(
                    'teacher',
                    function ($query) use ($establishment) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }
                )
                ->get()
                ->filter(
                    fn ($item) =>
                        $item->teacher !== null
                )
                ->map(
                    fn ($item) =>
                        $item->teacher
                )
                ->unique('id')
                ->sortBy(
                    fn ($teacher) =>
                        $teacher->nom . ' ' . $teacher->prenom
                )
                ->values();

            /*
            |--------------------------------------------------------------------------
            | Garder l'enseignant actuel
            |--------------------------------------------------------------------------
            */
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

                $teachers = $teachers
                    ->sortBy(
                        fn ($teacher) =>
                            $teacher->nom . ' ' . $teacher->prenom
                    )
                    ->values();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Retour vue
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        | selectedSchoolYearId est maintenant transmis à Blade.
        |
        */
        return view(
            'notes.edit',
            compact(
                'note',
                'establishment',
                'schoolYears',
                'selectedYear',
                'selectedSchoolYearId',
                'classes',
                'students',
                'subjects',
                'teachers'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Note $note
    ) {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier établissement
        |--------------------------------------------------------------------------
        */
        if (
            (int) $note->establishment_id !==
            (int) $establishment->id
        ) {
            abort(
                403,
                'Accès interdit à cette note.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([
            'school_year_id' => [
                'required',
                'integer',
                Rule::exists('school_years', 'id')
                    ->where(
                        'establishment_id',
                        $establishment->id
                    ),
            ],

            'class_room_id' => [
                'required',
                'integer',
                Rule::exists('class_rooms', 'id')
                    ->where(
                        'establishment_id',
                        $establishment->id
                    ),
            ],

            'student_id' => [
                'required',
                'integer',
                Rule::exists('students', 'id')
                    ->where(
                        'establishment_id',
                        $establishment->id
                    ),
            ],

            'subject_id' => [
                'required',
                'integer',
                Rule::exists('subjects', 'id')
                    ->where(
                        'establishment_id',
                        $establishment->id
                    ),
            ],

            'teacher_id' => [
                'nullable',
                'integer',
                Rule::exists('teachers', 'id')
                    ->where(
                        'establishment_id',
                        $establishment->id
                    ),
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
                'string',
                'max:100',
            ],

            'date_evaluation' => [
                'nullable',
                'date',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Vérifier inscription
        |--------------------------------------------------------------------------
        */
        $validInscription = Inscription::where(
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

        if (!$validInscription) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'Cet étudiant n’est pas inscrit dans cette classe pour cette année scolaire.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier classe
        |--------------------------------------------------------------------------
        */
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
            ->first();

        if (!$classRoom) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        'La classe sélectionnée ne correspond pas à l’année scolaire.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier matière
        |--------------------------------------------------------------------------
        */
        $hasTeacherSubject = TeacherSubject::where(
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
            ->whereHas(
                'teacher',
                function ($query) use ($establishment) {
                    $query->where(
                        'establishment_id',
                        $establishment->id
                    );
                }
            )
            ->exists();

        $hasClassSubject = ClassRoomSubject::where(
            'class_room_id',
            $classRoom->id
        )
            ->where(
                'subject_id',
                $validated['subject_id']
            )
            ->exists();

        if (
            !$hasTeacherSubject &&
            !$hasClassSubject
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'Cette matière n’est pas affectée à cette classe pour cette année scolaire.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Vérifier enseignant
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['teacher_id'])) {
            $teacherAssigned = TeacherSubject::where(
                'teacher_id',
                $validated['teacher_id']
            )
                ->where(
                    'subject_id',
                    $validated['subject_id']
                )
                ->where(
                    'class_room_id',
                    $classRoom->id
                )
                ->where(
                    'school_year_id',
                    $validated['school_year_id']
                )
                ->whereHas(
                    'teacher',
                    function ($query) use ($establishment) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    }
                )
                ->exists();

            if (!$teacherAssigned) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Cet enseignant n’est pas affecté à cette matière dans cette classe.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Mise à jour
        |--------------------------------------------------------------------------
        */
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

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(Note $note)
    {
        $establishment = $this->getEstablishment();

        if ($establishment instanceof \Illuminate\Http\RedirectResponse) {
            return $establishment;
        }

        if (
            (int) $note->establishment_id !==
            (int) $establishment->id
        ) {
            abort(
                403,
                'Accès interdit à cette note.'
            );
        }

        $note->delete();

        return redirect()
            ->route('notes.index')
            ->with(
                'success',
                'La note a été supprimée avec succès.'
            );
    }
}