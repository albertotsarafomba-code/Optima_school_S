<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\ClassRoomSubject;
use App\Models\Establishment;
use App\Models\Salle;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClassRoomController extends Controller
{
    /**
     * Liste des classes de l'établissement actuellement sélectionné.
     */
    public function index(Request $request): View
    {
        $establishmentId = session('establishment_id');

        abort_unless($establishmentId, 403);

        $search = trim((string) $request->input('search'));

        $establishment = Establishment::query()
            ->with('schoolType')
            ->findOrFail($establishmentId);

        $schoolYears = SchoolYear::query()
            ->where('establishment_id', $establishmentId)
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
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
            ->where('establishment_id', $establishmentId)
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

        $classRoomsQuery = ClassRoom::query()
            ->where('establishment_id', $establishmentId)
            ->where('school_year_id', $schoolYearId)
            ->with([
                'teacher',
                'room',
                'schoolYear',
            ])
            ->withCount([
                'inscriptions as effectif_actuel' => function ($query) {
                    $query->where('statut', 'Actif');
                },
                'subjects as subjects_count',
            ]);

        if ($search !== '') {
            $classRoomsQuery->where(function ($query) use ($search) {
                $query
                    ->where(
                        'nom_classe',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'niveau',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'filiere',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'salle',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        $classRooms = $classRoomsQuery
            ->orderBy('niveau')
            ->orderBy('nom_classe')
            ->get();

        return view('classes.index', [
            'establishment' => $establishment,
            'schoolYears' => $schoolYears,
            'classes' => $classRooms,
            'search' => $search,
            'schoolYearId' => $schoolYearId,
        ]);
    }

    /**
     * Formulaire de création des classes.
     */
    public function create(): View
    {
        $establishmentId = session('establishment_id');

        abort_unless($establishmentId, 403);

        $establishment = Establishment::query()
            ->with('schoolType')
            ->findOrFail($establishmentId);

        $typeName = $this->getTypeName($establishment);

        $niveaux = $this->getNiveauxForType($typeName);

        $schoolYears = SchoolYear::query()
            ->where('establishment_id', $establishmentId)
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        $teachers = Teacher::query()
            ->where('establishment_id', $establishmentId)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $salles = Salle::query()
            ->where('establishment_id', $establishmentId)
            ->orderBy('nom')
            ->get();

        $subjects = Subject::query()
            ->where(
                'school_type_id',
                $establishment->school_type_id
            )
            ->where(function ($query) use ($establishmentId) {
                $query
                    ->whereNull('establishment_id')
                    ->orWhere(
                        'establishment_id',
                        $establishmentId
                    );
            })
            ->orderBy('nom')
            ->get();

        return view('classes.create', [
            'establishment' => $establishment,
            'typeName' => $typeName,
            'niveaux' => $niveaux,
            'schoolYears' => $schoolYears,
            'teachers' => $teachers,
            'salles' => $salles,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Enregistre une ou plusieurs classes.
     */
    public function store(Request $request): RedirectResponse
    {
        $establishmentId = session('establishment_id');

        abort_unless($establishmentId, 403);

        $validated = $request->validate([
            'school_year_id' => [
                'required',
                'integer',
                'exists:school_years,id',
            ],

            'annee_scolaire' => [
                'nullable',
                'string',
                'max:255',
            ],

            'classes' => [
                'required',
                'array',
                'min:1',
            ],

            'classes.*.niveau' => [
                'required',
                'string',
                'max:255',
            ],

            'classes.*.nom' => [
                'required',
                'string',
                'max:150',
            ],

            'classes.*.filiere' => [
                'nullable',
                'string',
                'max:150',
            ],

            'classes.*.teacher_id' => [
                'nullable',
                'integer',
                'exists:teachers,id',
            ],

            'classes.*.salle_id' => [
                'nullable',
                'integer',
                'exists:salles,id',
            ],

            'classes.*.capacite' => [
                'nullable',
                'integer',
                'min:1',
                'max:10000',
            ],

            'classes.*.effectif_minimum' => [
                'nullable',
                'integer',
                'min:0',
                'max:10000',
            ],

            'classes.*.effectif_maximum' => [
                'nullable',
                'integer',
                'min:1',
                'max:10000',
            ],
        ]);

        $schoolYear = SchoolYear::query()
            ->where('id', $validated['school_year_id'])
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->first();

        abort_unless($schoolYear, 403);

        $anneeScolaire = trim(
            (string) ($validated['annee_scolaire'] ?? '')
        );

        if ($anneeScolaire === '') {
            $anneeScolaire =
                $schoolYear->name
                ?? $schoolYear->nom
                ?? $schoolYear->annee_scolaire
                ?? $schoolYear->libelle
                ?? '';
        }

        $establishment = Establishment::query()
            ->with('schoolType')
            ->findOrFail($establishmentId);

        $typeName = $this->getTypeName($establishment);

        $niveauxAutorises =
            $this->getNiveauxForType($typeName);

        $currentClassNames = [];

        foreach ($validated['classes'] as $index => $classe) {
            $niveau = trim(
                (string) $classe['niveau']
            );

            $nom = trim(
                (string) $classe['nom']
            );

            $filiere = trim(
                (string) ($classe['filiere'] ?? '')
            );

            if (
                !empty($niveauxAutorises)
                && !in_array(
                    $niveau,
                    $niveauxAutorises,
                    true
                )
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "classes.$index.niveau" =>
                            'Le niveau sélectionné n’est pas valide pour ce type d’établissement.',
                    ]);
            }

            $isUniversity = in_array(
                $typeName,
                ['université', 'universite'],
                true
            );

            if (
                $isUniversity
                && $filiere === ''
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "classes.$index.filiere" =>
                            'La filière est obligatoire pour une classe universitaire.',
                    ]);
            }

            if ($isUniversity) {
                $nomClasse = implode(
                    ' - ',
                    array_filter([
                        $niveau,
                        $filiere,
                        $nom,
                    ])
                );
            } else {
                $nomClasse = trim(
                    $niveau . ' ' . $nom
                );
            }

            $normalizedName = mb_strtolower(
                trim($nomClasse)
            );

            if (
                in_array(
                    $normalizedName,
                    $currentClassNames,
                    true
                )
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "classes.$index.nom" =>
                            'La classe « ' .
                            $nomClasse .
                            ' » est présente plusieurs fois dans ce formulaire.',
                    ]);
            }

            $currentClassNames[] =
                $normalizedName;

            $duplicateExists = ClassRoom::query()
                ->where(
                    'establishment_id',
                    $establishmentId
                )
                ->where(
                    'school_year_id',
                    $validated['school_year_id']
                )
                ->whereRaw(
                    'LOWER(TRIM(nom_classe)) = ?',
                    [$normalizedName]
                )
                ->exists();

            if ($duplicateExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "classes.$index.nom" =>
                            'La classe « ' .
                            $nomClasse .
                            ' » existe déjà pour cette année scolaire.',
                    ]);
            }

            if (!empty($classe['teacher_id'])) {
                $teacherBelongsToEstablishment =
                    Teacher::query()
                        ->where(
                            'id',
                            $classe['teacher_id']
                        )
                        ->where(
                            'establishment_id',
                            $establishmentId
                        )
                        ->exists();

                abort_unless(
                    $teacherBelongsToEstablishment,
                    403
                );
            }

            if (!empty($classe['salle_id'])) {
                $salleBelongsToEstablishment =
                    Salle::query()
                        ->where(
                            'id',
                            $classe['salle_id']
                        )
                        ->where(
                            'establishment_id',
                            $establishmentId
                        )
                        ->exists();

                abort_unless(
                    $salleBelongsToEstablishment,
                    403
                );
            }

            if (
                isset($classe['effectif_minimum'])
                && isset($classe['effectif_maximum'])
                && $classe['effectif_minimum']
                    > $classe['effectif_maximum']
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "classes.$index.effectif_maximum" =>
                            'L’effectif maximum doit être supérieur ou égal à l’effectif minimum.',
                    ]);
            }

            if (
                isset($classe['capacite'])
                && isset($classe['effectif_maximum'])
                && $classe['capacite']
                    < $classe['effectif_maximum']
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        "classes.$index.capacite" =>
                            'La capacité doit être supérieure ou égale à l’effectif maximum.',
                    ]);
            }
        }

        DB::transaction(function () use (
            $validated,
            $establishmentId,
            $anneeScolaire,
            $typeName
        ) {
            foreach ($validated['classes'] as $classe) {
                $niveau = trim(
                    (string) $classe['niveau']
                );

                $nom = trim(
                    (string) $classe['nom']
                );

                $filiere = trim(
                    (string) ($classe['filiere'] ?? '')
                );

                $isUniversity = in_array(
                    $typeName,
                    ['université', 'universite'],
                    true
                );

                if ($isUniversity) {
                    $nomClasse = implode(
                        ' - ',
                        array_filter([
                            $niveau,
                            $filiere,
                            $nom,
                        ])
                    );
                } else {
                    $nomClasse = trim(
                        $niveau . ' ' . $nom
                    );
                }

                $capacite =
                    $classe['capacite']
                    ?? null;

                $effectifMinimum =
                    $classe['effectif_minimum']
                    ?? null;

                $effectifMaximum =
                    $classe['effectif_maximum']
                    ?? null;

                if (
                    $capacite === null
                    && $effectifMaximum !== null
                ) {
                    $capacite =
                        $effectifMaximum;
                }

                $salleNom = null;

                if (!empty($classe['salle_id'])) {
                    $salle = Salle::query()
                        ->where(
                            'id',
                            $classe['salle_id']
                        )
                        ->where(
                            'establishment_id',
                            $establishmentId
                        )
                        ->first();

                    if ($salle) {
                        $salleNom =
                            $salle->nom;
                    }
                }

                ClassRoom::create([
                    'establishment_id' =>
                        $establishmentId,

                    'nom_classe' =>
                        $nomClasse,

                    'niveau' =>
                        $niveau,

                    'filiere' =>
                        $filiere !== ''
                            ? $filiere
                            : null,

                    'school_year_id' =>
                        $validated['school_year_id'],

                    'teacher_id' =>
                        $classe['teacher_id']
                        ?? null,

                    'salle_id' =>
                        $classe['salle_id']
                        ?? null,

                    'annee_scolaire' =>
                        $anneeScolaire !== ''
                            ? $anneeScolaire
                            : null,

                    'salle' =>
                        $salleNom,

                    'capacite' =>
                        $capacite,

                    'effectif_minimum' =>
                        $effectifMinimum,

                    'effectif_maximum' =>
                        $effectifMaximum,
                ]);
            }
        });

        $nombreClasses = count(
            $validated['classes']
        );

        return redirect()
            ->route('class_rooms.index')
            ->with(
                'success',
                $nombreClasses .
                ' classe(s) créée(s) avec succès.'
            );
    }

    /**
     * Affiche une classe et ses étudiants.
     */
    public function show(
        ClassRoom $classRoom
    ): View {
        $establishmentId =
            session('establishment_id');

        abort_unless(
            $establishmentId,
            403
        );

        abort_unless(
            (int) $classRoom->establishment_id
                === (int) $establishmentId,
            403
        );

        $classRoom->load([
            'establishment.schoolType',
            'teacher',
            'salle',
            'schoolYear',
            'subjects',
        ]);

        $students = $classRoom
            ->inscriptions()
            ->with('student')
            ->where(
                'statut',
                'Actif'
            )
            ->where(
                'school_year_id',
                $classRoom->school_year_id
            )
            ->get()
            ->map(function ($inscription) {
                return $inscription->student;
            })
            ->filter()
            ->values();

        $effectifActuel =
            $students->count();

        return view(
            'classes.show',
            [
                'classRoom' =>
                    $classRoom,

                'students' =>
                    $students,

                'effectifActuel' =>
                    $effectifActuel,
            ]
        );
    }

    /**
     * Formulaire de modification.
     */
    public function edit(
        ClassRoom $classRoom
    ): View {
        $establishmentId =
            session('establishment_id');

        abort_unless(
            $establishmentId,
            403
        );

        abort_unless(
            (int) $classRoom->establishment_id
                === (int) $establishmentId,
            403
        );

        $establishment = Establishment::query()
            ->with('schoolType')
            ->findOrFail(
                $establishmentId
            );

        $typeName =
            $this->getTypeName(
                $establishment
            );

        $niveaux =
            $this->getNiveauxForType(
                $typeName
            );

        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->get();

        $teachers = Teacher::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $salles = Salle::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderBy('nom')
            ->get();

        $subjects = Subject::query()
            ->where(
                'school_type_id',
                $establishment->school_type_id
            )
            ->where(function ($query) use ($establishmentId) {
                $query
                    ->whereNull('establishment_id')
                    ->orWhere(
                        'establishment_id',
                        $establishmentId
                    );
            })
            ->where(function ($query) use ($classRoom) {
                $query
                    ->whereNull('school_year_id')
                    ->orWhere(
                        'school_year_id',
                        $classRoom->school_year_id
                    );
            })
            ->orderBy('nom')
            ->get();

        $classRoom->load([
            'teacher',
            'room',
            'salle',
            'schoolYear',
            'subjects',
        ]);

        return view(
            'classes.edit',
            [
                'classRoom' =>
                    $classRoom,

                'establishment' =>
                    $establishment,

                'typeName' =>
                    $typeName,

                'niveaux' =>
                    $niveaux,

                'schoolYears' =>
                    $schoolYears,

                'teachers' =>
                    $teachers,

                'salles' =>
                    $salles,

                'subjects' =>
                    $subjects,
            ]
        );
    }

    /**
     * Met à jour une classe.
     */
    public function update(
        Request $request,
        ClassRoom $classRoom
    ): RedirectResponse {
        $establishmentId =
            session('establishment_id');

        abort_unless(
            $establishmentId,
            403
        );

        abort_unless(
            (int) $classRoom->establishment_id
                === (int) $establishmentId,
            403
        );

        $validated = $request->validate([
            'nom_classe' => [
                'required',
                'string',
                'max:255',
            ],

            'niveau' => [
                'nullable',
                'string',
                'max:255',
            ],

            'filiere' => [
                'nullable',
                'string',
                'max:255',
            ],

            'school_year_id' => [
                'nullable',
                'integer',
                'exists:school_years,id',
            ],

            'teacher_id' => [
                'nullable',
                'integer',
                'exists:teachers,id',
            ],

            'salle_id' => [
                'nullable',
                'integer',
                'exists:salles,id',
            ],

            'annee_scolaire' => [
                'nullable',
                'string',
                'max:255',
            ],

            'salle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'capacite' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'effectif_minimum' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'effectif_maximum' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $establishment = Establishment::query()
            ->with('schoolType')
            ->findOrFail(
                $establishmentId
            );

        $typeName =
            $this->getTypeName(
                $establishment
            );

        $niveauxAutorises =
            $this->getNiveauxForType(
                $typeName
            );

        $niveau = trim(
            (string) ($validated['niveau'] ?? '')
        );

        if (
            $niveau !== ''
            && !empty($niveauxAutorises)
            && !in_array(
                $niveau,
                $niveauxAutorises,
                true
            )
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'niveau' =>
                        'Le niveau sélectionné n’est pas valide pour ce type d’établissement.',
                ]);
        }

        $isUniversity = in_array(
            $typeName,
            ['université', 'universite'],
            true
        );

        $filiere = trim(
            (string) ($validated['filiere'] ?? '')
        );

        if (
            $isUniversity
            && $filiere === ''
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'filiere' =>
                        'La filière est obligatoire pour une classe universitaire.',
                ]);
        }

        if (!empty($validated['school_year_id'])) {
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

            abort_unless(
                $schoolYear,
                403
            );

            if (
                empty(
                    $validated['annee_scolaire']
                )
            ) {
                $validated['annee_scolaire'] =
                    $schoolYear->name
                    ?? $schoolYear->nom
                    ?? $schoolYear->annee_scolaire
                    ?? $schoolYear->libelle
                    ?? null;
            }
        }

        if (!empty($validated['teacher_id'])) {
            $teacherBelongsToEstablishment =
                Teacher::query()
                    ->where(
                        'id',
                        $validated['teacher_id']
                    )
                    ->where(
                        'establishment_id',
                        $establishmentId
                    )
                    ->exists();

            abort_unless(
                $teacherBelongsToEstablishment,
                403
            );
        }

        if (!empty($validated['salle_id'])) {
            $salle = Salle::query()
                ->where(
                    'id',
                    $validated['salle_id']
                )
                ->where(
                    'establishment_id',
                    $establishmentId
                )
                ->first();

            abort_unless(
                $salle,
                403
            );

            $validated['salle'] =
                $salle->nom;
        } else {
            $validated['salle'] = null;
        }

        if (
            isset($validated['effectif_minimum'])
            && isset($validated['effectif_maximum'])
            && $validated['effectif_minimum']
                > $validated['effectif_maximum']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'effectif_maximum' =>
                        'L’effectif maximum doit être supérieur ou égal à l’effectif minimum.',
                ]);
        }

        if (
            !isset($validated['capacite'])
            && isset($validated['effectif_maximum'])
        ) {
            $validated['capacite'] =
                $validated['effectif_maximum'];
        }

        if (
            isset($validated['capacite'])
            && isset($validated['effectif_maximum'])
            && $validated['capacite']
                < $validated['effectif_maximum']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'capacite' =>
                        'La capacité doit être supérieure ou égale à l’effectif maximum.',
                ]);
        }

        $schoolYearId =
            $validated['school_year_id']
            ?? $classRoom->school_year_id;

        $nomClasse = trim(
            (string) $validated['nom_classe']
        );

        $duplicateExists = ClassRoom::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->where(
                'school_year_id',
                $schoolYearId
            )
            ->whereRaw(
                'LOWER(TRIM(nom_classe)) = ?',
                [
                    mb_strtolower(
                        $nomClasse
                    ),
                ]
            )
            ->where(
                'id',
                '!=',
                $classRoom->id
            )
            ->exists();

        if ($duplicateExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'nom_classe' =>
                        'La classe « ' .
                        $nomClasse .
                        ' » existe déjà pour cette année scolaire.',
                ]);
        }

        $classRoom->update(
            $validated
        );

        return redirect()
            ->route(
                'class_rooms.show',
                $classRoom
            )
            ->with(
                'success',
                'La classe a été modifiée avec succès.'
            );
    }

    /**
     * Supprime une classe.
     */
    public function destroy(
        ClassRoom $classRoom
    ): RedirectResponse {
        $establishmentId =
            session('establishment_id');

        abort_unless(
            $establishmentId,
            403
        );

        abort_unless(
            (int) $classRoom->establishment_id
                === (int) $establishmentId,
            403
        );

        $classRoom->delete();

        return redirect()
            ->route('class_rooms.index')
            ->with(
                'success',
                'La classe a été supprimée avec succès.'
            );
    }

    /**
     * Affiche les matières affectées à une classe.
     */
    public function subjects(
        ClassRoom $classRoom
    ): View {
        $establishmentId =
            session('establishment_id');

        abort_unless(
            $establishmentId,
            403
        );

        abort_unless(
            (int) $classRoom->establishment_id
                === (int) $establishmentId,
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Charger la classe et ses matières
        |--------------------------------------------------------------------------
        */

        $classRoom->load([
            'schoolYear',
            'subjects' => function ($query) {
                $query
                    ->orderBy('nom')
                    ->withPivot('coefficient');
            },
        ]);

        $establishment = Establishment::query()
            ->with('schoolType')
            ->findOrFail(
                $establishmentId
            );

        /*
        |--------------------------------------------------------------------------
        | Matières disponibles
        |--------------------------------------------------------------------------
        */

        $subjects = Subject::query()
            ->where(
                'school_type_id',
                $establishment->school_type_id
            )
            ->where(function ($query) use ($establishmentId) {
                $query
                    ->whereNull('establishment_id')
                    ->orWhere(
                        'establishment_id',
                        $establishmentId
                    );
            })
            ->where(function ($query) use ($classRoom) {
                $query
                    ->whereNull('school_year_id')
                    ->orWhere(
                        'school_year_id',
                        $classRoom->school_year_id
                    );
            })
            ->orderBy('nom')
            ->get();

        return view(
            'classes.subjects',
            compact(
                'classRoom',
                'subjects'
            )
        )->with(
            'class',
            $classRoom
        );
    }

    /**
     * Affecte une matière à une classe.
     */
    public function attachSubject(
        Request $request,
        ClassRoom $classRoom
    ): RedirectResponse {
        $establishmentId =
            session('establishment_id');

        abort_unless(
            $establishmentId,
            403
        );

        abort_unless(
            (int) $classRoom->establishment_id
                === (int) $establishmentId,
            403
        );

        $establishment = Establishment::query()
            ->with('schoolType')
            ->findOrFail(
                $establishmentId
            );

        $validated = $request->validate([
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],

            'coefficient' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],
        ]);

        $subject = Subject::query()
            ->where(
                'id',
                $validated['subject_id']
            )
            ->where(
                'school_type_id',
                $establishment->school_type_id
            )
            ->where(function ($query) use ($establishmentId) {
                $query
                    ->whereNull('establishment_id')
                    ->orWhere(
                        'establishment_id',
                        $establishmentId
                    );
            })
            ->where(function ($query) use ($classRoom) {
                $query
                    ->whereNull('school_year_id')
                    ->orWhere(
                        'school_year_id',
                        $classRoom->school_year_id
                    );
            })
            ->first();

        if (!$subject) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'Cette matière ne correspond pas à l’établissement, à l’année scolaire ou au type de cette classe.',
                ]);
        }

        $alreadyExists = ClassRoomSubject::query()
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'subject_id',
                $subject->id
            )
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'subject_id' =>
                        'Cette matière est déjà affectée à cette classe.',
                ]);
        }

        ClassRoomSubject::create([
            'class_room_id' =>
                $classRoom->id,

            'subject_id' =>
                $subject->id,

            'coefficient' =>
                $validated['coefficient'],
        ]);

        return redirect()
            ->route(
                'class_rooms.subjects',
                $classRoom
            )
            ->with(
                'success',
                'La matière « ' .
                $subject->nom .
                ' » a été affectée à la classe avec succès.'
            );
    }

    /**
     * Modifie le coefficient d'une matière affectée.
     */
    public function updateSubject(
        Request $request,
        ClassRoom $classRoom,
        Subject $subject
    ): RedirectResponse {
        $establishmentId =
            session('establishment_id');

        abort_unless(
            $establishmentId,
            403
        );

        abort_unless(
            (int) $classRoom->establishment_id
                === (int) $establishmentId,
            403
        );

        $establishment = Establishment::query()
            ->with('schoolType')
            ->findOrFail(
                $establishmentId
            );

        $subjectBelongsToContext = Subject::query()
            ->where(
                'id',
                $subject->id
            )
            ->where(
                'school_type_id',
                $establishment->school_type_id
            )
            ->where(function ($query) use ($establishmentId) {
                $query
                    ->whereNull('establishment_id')
                    ->orWhere(
                        'establishment_id',
                        $establishmentId
                    );
            })
            ->where(function ($query) use ($classRoom) {
                $query
                    ->whereNull('school_year_id')
                    ->orWhere(
                        'school_year_id',
                        $classRoom->school_year_id
                    );
            })
            ->exists();

        abort_unless(
            $subjectBelongsToContext,
            403
        );

        $validated = $request->validate([
            'coefficient' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],
        ]);

        $classRoomSubject = ClassRoomSubject::query()
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'subject_id',
                $subject->id
            )
            ->first();

        if (!$classRoomSubject) {
            return back()
                ->withErrors([
                    'coefficient' =>
                        'Cette matière n’est pas affectée à cette classe.',
                ]);
        }

        $classRoomSubject->update([
            'coefficient' =>
                $validated['coefficient'],
        ]);

        return redirect()
            ->route(
                'class_rooms.subjects',
                $classRoom
            )
            ->with(
                'success',
                'Le coefficient de « ' .
                $subject->nom .
                ' » a été modifié avec succès.'
            );
    }

    /**
     * Retire une matière d'une classe.
     */
    public function detachSubject(
        ClassRoom $classRoom,
        Subject $subject
    ): RedirectResponse {
        $establishmentId =
            session('establishment_id');

        abort_unless(
            $establishmentId,
            403
        );

        abort_unless(
            (int) $classRoom->establishment_id
                === (int) $establishmentId,
            403
        );

        $establishment = Establishment::query()
            ->with('schoolType')
            ->findOrFail(
                $establishmentId
            );

        $subjectBelongsToContext = Subject::query()
            ->where(
                'id',
                $subject->id
            )
            ->where(
                'school_type_id',
                $establishment->school_type_id
            )
            ->where(function ($query) use ($establishmentId) {
                $query
                    ->whereNull('establishment_id')
                    ->orWhere(
                        'establishment_id',
                        $establishmentId
                    );
            })
            ->where(function ($query) use ($classRoom) {
                $query
                    ->whereNull('school_year_id')
                    ->orWhere(
                        'school_year_id',
                        $classRoom->school_year_id
                    );
            })
            ->exists();

        abort_unless(
            $subjectBelongsToContext,
            403
        );

        $classRoomSubject = ClassRoomSubject::query()
            ->where(
                'class_room_id',
                $classRoom->id
            )
            ->where(
                'subject_id',
                $subject->id
            )
            ->first();

        if (!$classRoomSubject) {
            return back()
                ->with(
                    'error',
                    'Cette matière n’est pas affectée à cette classe.'
                );
        }

        $classRoomSubject->delete();

        return redirect()
            ->route(
                'class_rooms.subjects',
                $classRoom
            )
            ->with(
                'success',
                'La matière « ' .
                $subject->nom .
                ' » a été retirée de la classe.'
            );
    }

    /**
     * Retourne le nom normalisé du type d'établissement.
     */
    private function getTypeName(
        Establishment $establishment
    ): string {
        return strtolower(
            trim(
                (string) (
                    $establishment
                        ->schoolType
                        ?->name
                    ?? $establishment
                        ->schoolType
                        ?->nom
                    ?? ''
                )
            )
        );
    }

    /**
     * Retourne les niveaux autorisés selon le type.
     */
    private function getNiveauxForType(
        string $typeName
    ): array {
        return match ($typeName) {

            'préscolaire',
            'prescolaire' => [
                'TPS',
                'PS',
                'MS',
                'GS',
            ],

            'primaire' => [
                'CP1',
                'CP2',
                'CE1',
                'CE2',
                'CM1',
                'CM2',
            ],

            'collège',
            'college' => [
                '6ème',
                '5ème',
                '4ème',
                '3ème',
            ],

            'lycée',
            'lycee' => [
                'Seconde',
                'Première',
                'Terminale',
            ],

            'université',
            'universite' => [
                'L1',
                'L2',
                'L3',
                'M1',
                'M2',
                'Doctorat',
            ],

            default => [],
        };
    }
}
