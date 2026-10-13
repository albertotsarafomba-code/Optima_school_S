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

        abort_unless(
            $establishmentId,
            403,
            'Aucun établissement sélectionné.'
        );

        $establishment = Establishment::find($establishmentId);

        abort_unless(
            $establishment,
            403,
            'Établissement introuvable.'
        );

        return $establishment;
    }

    /**
     * Année scolaire active de l'établissement courant.
     */
    private function activeSchoolYear(): ?SchoolYear
    {
        return SchoolYear::query()
            ->where(
                'establishment_id',
                $this->establishment()->id
            )
            ->where(
                'is_active',
                true
            )
            ->orderByDesc('start_date')
            ->first();
    }

    /**
     * Vérifier qu'une année scolaire appartient
     * bien à l'établissement courant.
     */
    private function resolveSchoolYear(
        mixed $schoolYearId,
        Establishment $establishment
    ): ?SchoolYear {
        if (
            $schoolYearId === null ||
            $schoolYearId === ''
        ) {
            return null;
        }

        return SchoolYear::query()
            ->where(
                'id',
                (int) $schoolYearId
            )
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->first();
    }

    public function index(Request $request): View|RedirectResponse
{
    $establishment = $this->establishment();

    /**
     * Année scolaire sélectionnée.
     */
    $schoolYearId = session('school_year_id');

    if (!$schoolYearId) {
        return redirect()
            ->route('school_years.index')
            ->with(
                'error',
                'Veuillez sélectionner une année scolaire.'
            );
    }

    /**
     * Vérifier que l'année appartient
     * bien à l'établissement courant.
     */
    $schoolYear = $this->resolveSchoolYear(
        $schoolYearId,
        $establishment
    );

    if (!$schoolYear) {
        session()->forget([
            'school_year_id',
            'school_year_name',
        ]);

        return redirect()
            ->route('school_years.index')
            ->with(
                'error',
                'L’année scolaire sélectionnée est invalide.'
            );
    }

    /**
     * Synchroniser la session avec l'année valide.
     */
    session([
        'school_year_id' => $schoolYear->id,
        'school_year_name' => $schoolYear->name,
    ]);

    $search = trim(
        (string) $request->input('search')
    );

    /**
     * Les enseignants appartiennent à l'établissement.
     *
     * Seules leurs affectations sont filtrées
     * par l'année scolaire sélectionnée.
     */
    $teachers = Teacher::query()
        ->where(
            'establishment_id',
            $establishment->id
        )
        ->with([
            'teacherSubjects' => function ($query) use (
                $schoolYear
            ) {
                $query
                    ->where(
                        'school_year_id',
                        $schoolYear->id
                    )
                    ->with([
                        'subject',
                        'classRoom',
                        'schoolYear',
                    ]);
            },
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
            'search',
            'schoolYear'
        )
    );
}



    /**
     * Formulaire de création.
     */
    public function create(Request $request): View
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

        $requestedSchoolYearId = $request->query(
            'school_year_id'
        );

        $sessionSchoolYearId =
            session('school_year_id');

        $selectedSchoolYearId = old(
            'school_year_id',
            $requestedSchoolYearId
                ?: (
                    $sessionSchoolYearId
                    ??
                    optional($activeSchoolYear)->id
                )
        );

        /**
         * Vérifier l'année sélectionnée.
         */
        $selectedSchoolYear = $this->resolveSchoolYear(
            $selectedSchoolYearId,
            $establishment
        );

        if (!$selectedSchoolYear) {
            $selectedSchoolYear =
                $activeSchoolYear
                ?? $schoolYears->first();

            $selectedSchoolYearId =
                optional($selectedSchoolYear)->id;
        }

        /**
         * Classes de l'année sélectionnée.
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

        /**
         * Matières disponibles.
         *
         * Une matière peut être générale
         * (establishment_id NULL)
         * ou appartenir à l'établissement courant.
         */
        $subjects = Subject::query()
            ->where(
                'school_type_id',
                $establishment->school_type_id
            )
            ->where(function ($query) use ($establishment) {
                $query
                    ->whereNull('establishment_id')
                    ->orWhere(
                        'establishment_id',
                        $establishment->id
                    );
            })
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

        return view(
            'teachers.create',
            compact(
                'establishment',
                'schoolYears',
                'activeSchoolYear',
                'selectedSchoolYearId',
                'selectedSchoolYear',
                'classes',
                'subjects'
            )
        );
    }

    /**
     * Enregistrer un enseignant.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $establishment =
            $this->establishment();

        /**
         * Normalisation téléphone.
         */
        $request->merge([
            'telephone' => $this->normalizeTelephone(
                $request->input('telephone')
            ),
        ]);

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
                'regex:/^0[2-9][0-9]{8}$/',
            ],

            'email' => [
                'nullable',
                'string',
                'email:rfc',
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

            'date_naissance.date' =>
                'La date de naissance est invalide.',

            'sexe.required' =>
                'Le sexe est obligatoire.',

            'sexe.in' =>
                'Le sexe sélectionné est invalide. Veuillez sélectionner Masculin ou Féminin.',

            'telephone.regex' =>
                'Veuillez saisir un numéro de téléphone malgache valide. Exemple : 034 12 345 67.',

            'email.email' =>
                'L’adresse email est invalide.',

            'email.max' =>
                'L’adresse email ne doit pas dépasser 150 caractères.',

            'school_year_id.required' =>
                'L’année scolaire est obligatoire.',

            'school_year_id.exists' =>
                'L’année scolaire sélectionnée est invalide.',
        ]);

        /**
         * Normalisation email.
         */
        $validated['email'] = !empty($validated['email'])
            ? strtolower(trim($validated['email']))
            : null;

        $validated['telephone'] =
            $validated['telephone'] ?? null;

        /**
         * Création + affectations dans une transaction.
         */
        $teacher = DB::transaction(
            function () use (
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
                            ? trim(
                                $validated['lieu_naissance']
                            )
                            : null,

                    'sexe' =>
                        $validated['sexe'],

                    'adresse' =>
                        isset($validated['adresse'])
                            ? trim(
                                $validated['adresse']
                            )
                            : null,

                    'telephone' =>
                        $validated['telephone'],

                    'email' =>
                        $validated['email'],

                    'specialite' =>
                        isset($validated['specialite'])
                            ? trim(
                                $validated['specialite']
                            )
                            : null,
                ]);

                $this->syncTeacherSubjects(
                    $teacher,
                    $validated['school_year_id'],
                    $validated['affectations'] ?? [],
                    $establishment
                );

                return $teacher;
            }
        );

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
    public function show(
        Teacher $teacher
    ): View|RedirectResponse {
        $establishment = $this->establishment();

        $this->authorizeEstablishment(
            $teacher
        );

        /**
         * Année scolaire sélectionnée.
         */
        $schoolYearId = session('school_year_id');

        if (!$schoolYearId) {
            return redirect()
                ->route('school_years.index')
                ->with(
                    'error',
                    'Veuillez sélectionner une année scolaire.'
                );
        }

        /**
         * Vérifier que l'année appartient
         * à l'établissement courant.
         */
        $schoolYear = $this->resolveSchoolYear(
            $schoolYearId,
            $establishment
        );

        if (!$schoolYear) {
            session()->forget([
                'school_year_id',
                'school_year_name',
            ]);

            return redirect()
                ->route('school_years.index')
                ->with(
                    'error',
                    'L’année scolaire sélectionnée est invalide.'
                );
        }

        /**
         * Synchroniser la session.
         */
        session([
            'school_year_id' => $schoolYear->id,
            'school_year_name' => $schoolYear->name,
        ]);

        /**
         * Charger uniquement les affectations
         * de l'année scolaire sélectionnée.
         */
        $teacher->load([
            'establishment',
            'teacherSubjects' => function ($query) use (
                $schoolYear
            ) {
                $query
                    ->where(
                        'school_year_id',
                        $schoolYear->id
                    )
                    ->with([
                        'subject',
                        'classRoom',
                        'schoolYear',
                    ]);
            },
        ]);

        return view(
            'teachers.show',
            compact(
                'teacher',
                'schoolYear'
            )
        );
    }

    /**
     * Formulaire de modification.
     */
    public function edit(
        Request $request,
        Teacher $teacher
    ): View {
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

        /**
         * Première affectation existante.
         */
        $firstAffectation = $teacher
            ->teacherSubjects()
            ->whereNotNull('school_year_id')
            ->orderByDesc('school_year_id')
            ->first();

        $activeSchoolYear =
            $this->activeSchoolYear();

        $requestedSchoolYearId = $request->query(
            'school_year_id'
        );

        /**
         * Détermination de l'année sélectionnée.
         */
        $sessionSchoolYearId =
            session('school_year_id');

        $selectedSchoolYearId = old(
            'school_year_id',
            $requestedSchoolYearId
                ?: (
                    $sessionSchoolYearId
                    ??
                    optional($firstAffectation)
                        ->school_year_id
                    ??
                    optional($activeSchoolYear)
                        ->id
                )
        );

        /**
         * Vérification de l'année sélectionnée.
         */
        $selectedSchoolYear = $this->resolveSchoolYear(
            $selectedSchoolYearId,
            $establishment
        );

        if (!$selectedSchoolYear) {
            $selectedSchoolYear =
                $activeSchoolYear
                ?? $schoolYears->first();

            $selectedSchoolYearId =
                optional($selectedSchoolYear)->id;
        }

        /**
         * Classes de l'année sélectionnée.
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

        /**
         * Matières de l'année sélectionnée.
         */
        $subjects = Subject::query()
            ->where(
                'school_type_id',
                $establishment->school_type_id
            )
            ->where(function ($query) use ($establishment) {
                $query
                    ->whereNull('establishment_id')
                    ->orWhere(
                        'establishment_id',
                        $establishment->id
                    );
            })
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

        /**
         * Affectations déjà enregistrées.
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
                $existingAffectations as $affectation
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
                'selectedSchoolYear',
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

        /**
         * Normalisation téléphone.
         */
        $request->merge([
            'telephone' => $this->normalizeTelephone(
                $request->input('telephone')
            ),
        ]);

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
                'regex:/^0[2-9][0-9]{8}$/',
            ],

            'email' => [
                'nullable',
                'string',
                'email:rfc',
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

            'date_naissance.date' =>
                'La date de naissance est invalide.',

            'sexe.required' =>
                'Le sexe est obligatoire.',

            'sexe.in' =>
                'Le sexe sélectionné est invalide. Veuillez sélectionner Masculin ou Féminin.',

            'telephone.regex' =>
                'Veuillez saisir un numéro de téléphone malgache valide. Exemple : 034 12 345 67.',

            'email.email' =>
                'L’adresse email est invalide.',

            'email.max' =>
                'L’adresse email ne doit pas dépasser 150 caractères.',

            'school_year_id.required' =>
                'L’année scolaire est obligatoire.',

            'school_year_id.exists' =>
                'L’année scolaire sélectionnée est invalide.',
        ]);

        /**
         * Normalisation email.
         */
        $validated['email'] = !empty($validated['email'])
            ? strtolower(trim($validated['email']))
            : null;

        $validated['telephone'] =
            $validated['telephone'] ?? null;

        DB::transaction(
            function () use (
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
                            ? trim(
                                $validated['lieu_naissance']
                            )
                            : null,

                    'sexe' =>
                        $validated['sexe'],

                    'adresse' =>
                        isset($validated['adresse'])
                            ? trim(
                                $validated['adresse']
                            )
                            : null,

                    'telephone' =>
                        $validated['telephone'],

                    'email' =>
                        $validated['email'],

                    'specialite' =>
                        isset($validated['specialite'])
                            ? trim(
                                $validated['specialite']
                            )
                            : null,
                ]);

                /**
                 * Supprimer uniquement les affectations
                 * de l'année sélectionnée.
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

                /**
                 * Recréer les affectations.
                 */
                $this->syncTeacherSubjects(
                    $teacher,
                    $validated['school_year_id'],
                    $validated['affectations'] ?? [],
                    $establishment
                );
            }
        );

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

        DB::transaction(
            function () use ($teacher) {
                TeacherSubject::query()
                    ->where(
                        'teacher_id',
                        $teacher->id
                    )
                    ->delete();

                $teacher->delete();
            }
        );

        return redirect()
            ->route('teachers.index')
            ->with(
                'success',
                'L’enseignant a été supprimé avec succès.'
            );
    }

    /**
     * Synchroniser les affectations :
     *
     * Enseignant
     *      ↓
     * Année scolaire
     *      ↓
     * Classe
     *      ↓
     * Matière
     *
     * L'enseignant sélectionne une matière existante
     * et la classe dans laquelle il l'enseigne.
     */
    private function syncTeacherSubjects(
        Teacher $teacher,
        int|string $schoolYearId,
        array $affectations,
        Establishment $establishment
    ): void {
        $schoolYearId = (int) $schoolYearId;

        /**
         * Vérifier l'année scolaire.
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

        /**
         * Vérifier l'enseignant.
         */
        abort_unless(
            (int) $teacher->establishment_id ===
                (int) $establishment->id,
            403,
            'Cet enseignant n’appartient pas à l’établissement sélectionné.'
        );

        /**
         * Parcourir les classes sélectionnées.
         */
        foreach (
            $affectations as $classRoomId => $subjectIds
        ) {
            if (!is_array($subjectIds)) {
                continue;
            }

            /**
             * Vérifier la classe.
             */
            $classRoom = ClassRoom::query()
                ->where(
                    'id',
                    (int) $classRoomId
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

            /**
             * Parcourir les matières sélectionnées.
             */
            foreach ($subjectIds as $subjectId) {
                $subjectId = (int) $subjectId;

                if ($subjectId <= 0) {
                    continue;
                }

                /**
                 * Vérifier la matière.
                 *
                 * La matière peut être générale
                 * ou appartenir à l'établissement courant.
                 */
                $subject = Subject::query()
                    ->where(
                        'id',
                        $subjectId
                    )
                    ->where(function ($query) use (
                        $establishment
                    ) {
                        $query
                            ->whereNull(
                                'establishment_id'
                            )
                            ->orWhere(
                                'establishment_id',
                                $establishment->id
                            );
                    })
                    ->where(
                        'school_type_id',
                        $establishment->school_type_id
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

                /**
                 * Créer l'affectation.
                 *
                 * IMPORTANT :
                 * aucune vérification de class_room_subjects
                 * ici.
                 */
                TeacherSubject::firstOrCreate([
                    'teacher_id' =>
                        $teacher->id,

                    'subject_id' =>
                        $subject->id,

                    'class_room_id' =>
                        $classRoom->id,

                    'school_year_id' =>
                        $schoolYear->id,
                ]);
            }
        }
    }

    /**
     * Vérifier que l'enseignant appartient
     * à l'établissement actuellement sélectionné.
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
            403,
            'Cet enseignant n’appartient pas à l’établissement sélectionné.'
        );
    }

    /**
     * Normaliser un téléphone malgache.
     *
     * Exemples :
     *
     * 034 12 345 67
     * 034-12-345-67
     * +261 34 12 345 67
     * 261341234567
     *
     * deviennent :
     *
     * 0341234567
     */
    private function normalizeTelephone(
        mixed $telephone
    ): ?string {
        if (
            $telephone === null ||
            trim((string) $telephone) === ''
        ) {
            return null;
        }

        $telephone = trim(
            (string) $telephone
        );

        $telephone = preg_replace(
            '/[\s\-\(\)]/',
            '',
            $telephone
        );

        if (
            str_starts_with(
                $telephone,
                '+261'
            )
        ) {
            $telephone =
                '0' .
                substr(
                    $telephone,
                    4
                );
        } elseif (
            str_starts_with(
                $telephone,
                '261'
            )
        ) {
            $telephone =
                '0' .
                substr(
                    $telephone,
                    3
                );
        }

        return $telephone;
    }
}