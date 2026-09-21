<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Establishment;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeacherController extends Controller
{
    /**
     * Établissement actuellement sélectionné.
     */
    private function establishment(): Establishment
    {
        $establishmentId = session('establishment_id');

        abort_unless($establishmentId, 403);

        $establishment = Establishment::find($establishmentId);

        abort_unless($establishment, 403);

        return $establishment;
    }


    /**
     * Année scolaire active de l'établissement.
     */
    private function activeSchoolYear(): ?SchoolYear
    {
        return SchoolYear::query()
            ->where(
                'establishment_id',
                $this->establishment()->id
            )
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->first();
    }


    /**
     * Liste des enseignants.
     */
    public function index(Request $request): View
    {
        $establishment = $this->establishment();

        $search = trim(
            (string) $request->input('search')
        );

        $teachers = Teacher::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->with([
                'teacherSubjects.subject',
                'teacherSubjects.classRoom',
                'teacherSubjects.schoolYear',
            ])
            ->when(
                $search !== '',
                function ($query) use ($search) {

                    $query->where(function ($q) use ($search) {

                        $q->where(
                            'nom',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'prenom',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'telephone',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'email',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'specialite',
                            'like',
                            '%' . $search . '%'
                        );

                    });
                }
            )
            ->orderBy('nom')
            ->orderBy('prenom')
            ->paginate(10)
            ->withQueryString();

        return view(
            'teachers.index',
            compact(
                'teachers',
                'search'
            )
        );
    }


    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $establishment = $this->establishment();

        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        $activeSchoolYear = $this->activeSchoolYear();

        $selectedSchoolYearId =
            old(
                'school_year_id',
                optional($activeSchoolYear)->id
            );

        $classes = ClassRoom::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->when(
                $selectedSchoolYearId,
                function ($query) use ($selectedSchoolYearId) {
                    $query->where(
                        'school_year_id',
                        $selectedSchoolYearId
                    );
                }
            )
            ->orderBy('nom_classe')
            ->get();

        $subjects = Subject::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->when(
                $selectedSchoolYearId,
                function ($query) use ($selectedSchoolYearId) {

                    $query->where(function ($q) use ($selectedSchoolYearId) {

                        $q->whereNull('school_year_id')
                            ->orWhere(
                                'school_year_id',
                                $selectedSchoolYearId
                            );

                    });
                }
            )
            ->orderBy('nom')
            ->get();

        return view(
            'teachers.create',
            compact(
                'establishment',
                'schoolYears',
                'activeSchoolYear',
                'classes',
                'subjects'
            )
        );
    }


    /**
     * Enregistrement d'un enseignant.
     */
    public function store(Request $request): RedirectResponse
    {
        $establishment = $this->establishment();


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:100',
            ],

            'prenom' => [
                'required',
                'string',
                'max:100',
            ],

            'date_naissance' => [
                'required',
                'date',
            ],

            'lieu_naissance' => [
                'nullable',
                'string',
                'max:150',
            ],

            /*
             * IMPORTANT :
             *
             * La base utilise :
             * M = Masculin
             * F = Féminin
             */
            'sexe' => [
                'required',
                Rule::in([
                    'M',
                    'F',
                ]),
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:500',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'specialite' => [
                'nullable',
                'string',
                'max:150',
            ],

            'school_year_id' => [
                'required',
                'integer',
                Rule::exists(
                    'school_years',
                    'id'
                )->where(function ($query) use ($establishment) {

                    $query->where(
                        'establishment_id',
                        $establishment->id
                    );

                }),
            ],

            'affectations' => [
                'nullable',
                'array',
            ],

            'affectations.*' => [
                'nullable',
                'array',
            ],

            'affectations.*.*' => [
                'integer',
            ],

        ], [

            'nom.required' =>
                'Le nom est obligatoire.',

            'prenom.required' =>
                'Le prénom est obligatoire.',

            'date_naissance.required' =>
                'La date de naissance est obligatoire.',

            'date_naissance.date' =>
                'La date de naissance est invalide.',

            'sexe.required' =>
                'Le sexe est obligatoire.',

            'sexe.in' =>
                'Le sexe sélectionné est invalide. Veuillez sélectionner Masculin ou Féminin.',

            'email.email' =>
                'L’adresse email est invalide.',

            'school_year_id.required' =>
                'L’année scolaire est obligatoire.',

            'school_year_id.exists' =>
                'L’année scolaire sélectionnée est invalide.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Création de l'enseignant
        |--------------------------------------------------------------------------
        */

        $teacher = DB::transaction(function () use (
            $validated,
            $establishment
        ) {

            $teacher = Teacher::create([

                'establishment_id' =>
                    $establishment->id,

                'nom' =>
                    trim($validated['nom']),

                'prenom' =>
                    trim($validated['prenom']),

                'date_naissance' =>
                    $validated['date_naissance'],

                'lieu_naissance' =>
                    isset($validated['lieu_naissance'])
                        ? trim($validated['lieu_naissance'])
                        : null,

                /*
                 * Toujours M ou F.
                 */
                'sexe' =>
                    $validated['sexe'],

                'adresse' =>
                    isset($validated['adresse'])
                        ? trim($validated['adresse'])
                        : null,

                'telephone' =>
                    isset($validated['telephone'])
                        ? trim($validated['telephone'])
                        : null,

                'email' =>
                    isset($validated['email'])
                        ? trim($validated['email'])
                        : null,

                'specialite' =>
                    isset($validated['specialite'])
                        ? trim($validated['specialite'])
                        : null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Affectations
            |--------------------------------------------------------------------------
            */

            $this->syncTeacherSubjects(
                $teacher,
                $validated['school_year_id'],
                $validated['affectations'] ?? [],
                $establishment
            );


            return $teacher;
        });


        return redirect()
            ->route(
                'teachers.show',
                $teacher
            )
            ->with(
                'success',
                'L’enseignant a été ajouté avec succès.'
            );
    }


    /**
     * Affichage d'un enseignant.
     */
    public function show(Teacher $teacher): View
    {
        $this->authorizeEstablishment(
            $teacher
        );

        $teacher->load([
            'establishment',
            'teacherSubjects.subject',
            'teacherSubjects.classRoom',
            'teacherSubjects.schoolYear',
        ]);

        return view(
            'teachers.show',
            compact('teacher')
        );
    }


    /**
     * Formulaire de modification.
     */
    public function edit(Teacher $teacher): View
    {
        $this->authorizeEstablishment(
            $teacher
        );

        $establishment =
            $this->establishment();

        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Année sélectionnée
        |--------------------------------------------------------------------------
        */

        $firstAffectation =
            $teacher->teacherSubjects()
                ->orderByDesc('school_year_id')
                ->first();

        $activeSchoolYear =
            $this->activeSchoolYear();

        $selectedSchoolYearId =
            old(
                'school_year_id',
                optional(
                    $firstAffectation
                )->school_year_id
                ??
                optional(
                    $activeSchoolYear
                )->id
            );


        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */

        $classes = ClassRoom::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->when(
                $selectedSchoolYearId,
                function ($query) use (
                    $selectedSchoolYearId
                ) {

                    $query->where(
                        'school_year_id',
                        $selectedSchoolYearId
                    );

                }
            )
            ->orderBy('nom_classe')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Matières
        |--------------------------------------------------------------------------
        */

        $subjects = Subject::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->when(
                $selectedSchoolYearId,
                function ($query) use (
                    $selectedSchoolYearId
                ) {

                    $query->where(function ($q) use (
                        $selectedSchoolYearId
                    ) {

                        $q->whereNull(
                            'school_year_id'
                        )
                        ->orWhere(
                            'school_year_id',
                            $selectedSchoolYearId
                        );

                    });

                }
            )
            ->orderBy('nom')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Affectations existantes
        |--------------------------------------------------------------------------
        */

        $selectedAffectations = [];

        if ($selectedSchoolYearId) {

            $existingAffectations =
                $teacher
                    ->teacherSubjects()
                    ->where(
                        'school_year_id',
                        $selectedSchoolYearId
                    )
                    ->get();

            foreach (
                $existingAffectations
                as $affectation
            ) {

                if (
                    !isset(
                        $selectedAffectations[
                            $affectation->class_room_id
                        ]
                    )
                ) {

                    $selectedAffectations[
                        $affectation->class_room_id
                    ] = [];

                }

                $selectedAffectations[
                    $affectation->class_room_id
                ][] =
                    $affectation->subject_id;
            }
        }


        return view(
            'teachers.edit',
            compact(
                'teacher',
                'establishment',
                'schoolYears',
                'activeSchoolYear',
                'selectedSchoolYearId',
                'classes',
                'subjects',
                'selectedAffectations'
            )
        );
    }


    /**
     * Mise à jour d'un enseignant.
     */
    public function update(
        Request $request,
        Teacher $teacher
    ): RedirectResponse {

        $this->authorizeEstablishment(
            $teacher
        );

        $establishment =
            $this->establishment();


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'nom' => [
                'required',
                'string',
                'max:100',
            ],

            'prenom' => [
                'required',
                'string',
                'max:100',
            ],

            'date_naissance' => [
                'required',
                'date',
            ],

            'lieu_naissance' => [
                'nullable',
                'string',
                'max:150',
            ],

            /*
             * IMPORTANT :
             *
             * M = Masculin
             * F = Féminin
             */
            'sexe' => [
                'required',
                Rule::in([
                    'M',
                    'F',
                ]),
            ],

            'adresse' => [
                'nullable',
                'string',
                'max:500',
            ],

            'telephone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
            ],

            'specialite' => [
                'nullable',
                'string',
                'max:150',
            ],

            'school_year_id' => [
                'required',
                'integer',
                Rule::exists(
                    'school_years',
                    'id'
                )->where(function ($query) use (
                    $establishment
                ) {

                    $query->where(
                        'establishment_id',
                        $establishment->id
                    );

                }),
            ],

            'affectations' => [
                'nullable',
                'array',
            ],

            'affectations.*' => [
                'nullable',
                'array',
            ],

            'affectations.*.*' => [
                'integer',
            ],

        ], [

            'nom.required' =>
                'Le nom est obligatoire.',

            'prenom.required' =>
                'Le prénom est obligatoire.',

            'date_naissance.required' =>
                'La date de naissance est obligatoire.',

            'sexe.required' =>
                'Le sexe est obligatoire.',

            'sexe.in' =>
                'Le sexe sélectionné est invalide. Veuillez sélectionner Masculin ou Féminin.',

            'email.email' =>
                'L’adresse email est invalide.',

            'school_year_id.required' =>
                'L’année scolaire est obligatoire.',

            'school_year_id.exists' =>
                'L’année scolaire sélectionnée est invalide.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Mise à jour
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $teacher,
            $validated,
            $establishment
        ) {

            $teacher->update([

                'nom' =>
                    trim($validated['nom']),

                'prenom' =>
                    trim($validated['prenom']),

                'date_naissance' =>
                    $validated['date_naissance'],

                'lieu_naissance' =>
                    isset($validated['lieu_naissance'])
                        ? trim($validated['lieu_naissance'])
                        : null,

                /*
                 * Jamais NULL.
                 */
                'sexe' =>
                    $validated['sexe'],

                'adresse' =>
                    isset($validated['adresse'])
                        ? trim($validated['adresse'])
                        : null,

                'telephone' =>
                    isset($validated['telephone'])
                        ? trim($validated['telephone'])
                        : null,

                'email' =>
                    isset($validated['email'])
                        ? trim($validated['email'])
                        : null,

                'specialite' =>
                    isset($validated['specialite'])
                        ? trim($validated['specialite'])
                        : null,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Supprimer les anciennes affectations de cette année
            |--------------------------------------------------------------------------
            */

            TeacherSubject::query()
                ->where(
                    'teacher_id',
                    $teacher->id
                )
                ->where(
                    'school_year_id',
                    $validated['school_year_id']
                )
                ->delete();


            /*
            |--------------------------------------------------------------------------
            | Recréer les affectations
            |--------------------------------------------------------------------------
            */

            $this->syncTeacherSubjects(
                $teacher,
                $validated['school_year_id'],
                $validated['affectations'] ?? [],
                $establishment
            );
        });


        return redirect()
            ->route(
                'teachers.show',
                $teacher
            )
            ->with(
                'success',
                'Les informations de l’enseignant ont été modifiées avec succès.'
            );
    }


    /**
     * Suppression d'un enseignant.
     */
    public function destroy(
        Teacher $teacher
    ): RedirectResponse {

        $this->authorizeEstablishment(
            $teacher
        );

        DB::transaction(function () use (
            $teacher
        ) {

            TeacherSubject::query()
                ->where(
                    'teacher_id',
                    $teacher->id
                )
                ->delete();

            $teacher->delete();
        });


        return redirect()
            ->route('teachers.index')
            ->with(
                'success',
                'L’enseignant a été supprimé avec succès.'
            );
    }


    /**
     * Synchronisation des matières/classes de l'enseignant.
     */
    private function syncTeacherSubjects(
        Teacher $teacher,
        int|string $schoolYearId,
        array $affectations,
        Establishment $establishment
    ): void {

        $schoolYearId =
            (int) $schoolYearId;


        /*
        |--------------------------------------------------------------------------
        | Vérifier l'année scolaire
        |--------------------------------------------------------------------------
        */

        $schoolYear = SchoolYear::query()
            ->where(
                'id',
                $schoolYearId
            )
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Parcours des classes
        |--------------------------------------------------------------------------
        */

        foreach (
            $affectations
            as $classRoomId => $subjectIds
        ) {

            if (
                !is_array($subjectIds)
            ) {
                continue;
            }

            $classRoom = ClassRoom::query()
                ->where(
                    'id',
                    $classRoomId
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
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Matières sélectionnées
            |--------------------------------------------------------------------------
            */

            foreach (
                $subjectIds
                as $subjectId
            ) {

                $subjectId =
                    (int) $subjectId;

                if (
                    $subjectId <= 0
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Vérifier la matière
                |--------------------------------------------------------------------------
                */

                $subject = Subject::query()
                    ->where(
                        'id',
                        $subjectId
                    )
                    ->where(
                        'establishment_id',
                        $establishment->id
                    )
                    ->where(function ($query) use (
                        $schoolYearId
                    ) {

                        $query
                            ->whereNull(
                                'school_year_id'
                            )
                            ->orWhere(
                                'school_year_id',
                                $schoolYearId
                            );

                    })
                    ->first();

                if (!$subject) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Créer l'affectation
                |--------------------------------------------------------------------------
                */

                TeacherSubject::firstOrCreate(
                    [
                        'teacher_id' =>
                            $teacher->id,

                        'subject_id' =>
                            $subject->id,

                        'class_room_id' =>
                            $classRoom->id,

                        'school_year_id' =>
                            $schoolYear->id,
                    ]
                );
            }
        }
    }


    /**
     * Vérification de l'établissement.
     */
    private function authorizeEstablishment(
        Teacher $teacher
    ): void {

        $establishmentId =
            session('establishment_id');

        abort_unless(
            $establishmentId &&
            (int) $teacher->establishment_id ===
            (int) $establishmentId,
            403
        );
    }
}

