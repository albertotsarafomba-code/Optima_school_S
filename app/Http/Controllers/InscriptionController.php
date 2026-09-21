<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Establishment;
use App\Models\Inscription;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class InscriptionController extends Controller
{
    /**
     * Retourne l'établissement actuellement sélectionné.
     */
    private function currentEstablishment(): Establishment
    {
        $user = auth()->user();

        if (!$user) {
            abort(
                Response::HTTP_UNAUTHORIZED,
                'Utilisateur non authentifié.'
            );
        }

        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            abort(
                Response::HTTP_FORBIDDEN,
                'Aucun établissement sélectionné.'
            );
        }

        /*
         * Admin :
         * l'établissement doit appartenir à l'utilisateur.
         */
        if ($user->role === 'admin') {
            $establishment = $user
                ->establishments()
                ->with('schoolType')
                ->find($establishmentId);
        } else {
            /*
             * Secrétaire / autres utilisateurs :
             * l'établissement doit être affecté à l'utilisateur.
             */
            $establishment = $user
                ->assignedEstablishments()
                ->with('schoolType')
                ->find($establishmentId);
        }

        if (!$establishment) {
            abort(
                Response::HTTP_FORBIDDEN,
                'Vous n’avez pas accès à cet établissement.'
            );
        }

        return $establishment;
    }

    /**
     * Retourne l'année scolaire autorisée dans l'établissement courant.
     */
    private function currentSchoolYear(int $schoolYearId): SchoolYear
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = SchoolYear::query()
            ->where('id', $schoolYearId)
            ->where('establishment_id', $establishment->id)
            ->first();

        if (!$schoolYear) {
            abort(
                Response::HTTP_FORBIDDEN,
                'Cette année scolaire n’appartient pas à l’établissement courant.'
            );
        }

        return $schoolYear;
    }

    /**
     * Retourne la classe autorisée dans l'établissement courant.
     */
    private function currentClassRoom(int $classRoomId): ClassRoom
    {
        $establishment = $this->currentEstablishment();

        $classRoom = ClassRoom::query()
            ->where('id', $classRoomId)
            ->where('establishment_id', $establishment->id)
            ->first();

        if (!$classRoom) {
            abort(
                Response::HTTP_FORBIDDEN,
                'Cette classe n’appartient pas à l’établissement courant.'
            );
        }

        return $classRoom;
    }

    /**
     * Retourne l'étudiant autorisé dans l'établissement courant.
     */
    private function currentStudent(int $studentId): Student
    {
        $establishment = $this->currentEstablishment();

        $student = Student::query()
            ->where('id', $studentId)
            ->where('establishment_id', $establishment->id)
            ->first();

        if (!$student) {
            abort(
                Response::HTTP_FORBIDDEN,
                'Cet étudiant n’appartient pas à l’établissement courant.'
            );
        }

        return $student;
    }

    /**
     * Génère automatiquement un matricule étudiant.
     */
    private function generateMatricule(
        Establishment $establishment
    ): string {
        do {
            $matricule =
                'MAT-' .
                str_pad(
                    (string) $establishment->id,
                    3,
                    '0',
                    STR_PAD_LEFT
                ) .
                '-' .
                now()->format('YmdHis') .
                '-' .
                random_int(100, 999);

        } while (
            Student::where('matricule', $matricule)->exists()
        );

        return $matricule;
    }

    /**
     * Liste des inscriptions de l'établissement courant.
     */
    public function index()
    {
        $establishment = $this->currentEstablishment();

        $inscriptions = Inscription::query()
            ->with([
                'student',
                'classRoom',
                'schoolYear',
            ])
            ->where('establishment_id', $establishment->id)
            ->orderByDesc('id')
            ->paginate(15);

        return view(
            'inscriptions.index',
            compact(
                'establishment',
                'inscriptions'
            )
        );
    }

    /**
     * Formulaire de création d'une inscription.
     */
    public function create(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolTypeName =
            $establishment->schoolType?->name
            ?? 'Type non défini';

        /*
         * Années scolaires de l'établissement courant.
         */
        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderByDesc('id')
            ->get();

        /*
         * Année sélectionnée :
         *
         * 1. paramètre school_year_id
         * 2. session school_year_id
         * 3. année active
         * 4. première année disponible
         */
        $selectedSchoolYearId =
            $request->integer('school_year_id')
            ?: session('school_year_id');

        $selectedSchoolYear = null;

        if ($selectedSchoolYearId) {
            $selectedSchoolYear = $schoolYears
                ->firstWhere(
                    'id',
                    (int) $selectedSchoolYearId
                );
        }

        if (!$selectedSchoolYear) {
            $selectedSchoolYear = $schoolYears
                ->firstWhere(
                    'active',
                    true
                );
        }

        if (!$selectedSchoolYear) {
            $selectedSchoolYear =
                $schoolYears->first();
        }

        $selectedSchoolYearId =
            $selectedSchoolYear?->id;

        /*
         * Synchronisation avec la session.
         */
        if ($selectedSchoolYear) {
            session([
                'school_year_id' =>
                    $selectedSchoolYear->id,

                'school_year_name' =>
                    $selectedSchoolYear->name,
            ]);
        }

        $schoolYearId =
            $selectedSchoolYearId;

        /*
         * Classes de l'établissement courant.
         */
        $classesQuery = ClassRoom::query()
            ->where(
                'establishment_id',
                $establishment->id
            );

        if ($selectedSchoolYearId) {
            $classesQuery->where(function ($query) use (
                $selectedSchoolYearId
            ) {
                $query
                    ->where(
                        'school_year_id',
                        $selectedSchoolYearId
                    )
                    ->orWhereNull('school_year_id');
            });
        }

        $classes = $classesQuery
            ->orderBy('nom_classe')
            ->get();

        /*
         * Étudiants de l'établissement courant.
         */
        $students = Student::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view(
            'inscriptions.create',
            compact(
                'establishment',
                'schoolTypeName',
                'schoolYears',
                'selectedSchoolYear',
                'selectedSchoolYearId',
                'classes',
                'students',
                'schoolYearId'
            )
        );
    }

    /**
     * Enregistre une nouvelle inscription.
     */
    public function store(Request $request)
    {
        $establishment =
            $this->currentEstablishment();

        $validated = $request->validate([
            'school_year_id' => [
                'required',
                'integer',
                'exists:school_years,id',
            ],

            'class_room_id' => [
                'required',
                'integer',
                'exists:class_rooms,id',
            ],

            'student_id' => [
                'nullable',
                'integer',
                'exists:students,id',
            ],

            'nom' => [
                'nullable',
                'string',
                'max:255',
            ],

            'prenom' => [
                'nullable',
                'string',
                'max:255',
            ],

            'date_naissance' => [
                'nullable',
                'date',
            ],

            'lieu_naissance' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sexe' => [
                'nullable',
                Rule::in([
                    'M',
                    'F',
                    'Masculin',
                    'Féminin',
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
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],
        ]);

        /*
         * Vérification de l'année scolaire.
         */
        $schoolYear = $this->currentSchoolYear(
            (int) $validated['school_year_id']
        );

        /*
         * Vérification de la classe.
         */
        $classRoom = $this->currentClassRoom(
            (int) $validated['class_room_id']
        );

        /*
         * La classe doit correspondre à l'année sélectionnée
         * lorsqu'elle possède un school_year_id.
         */
        if (
            $classRoom->school_year_id &&
            (int) $classRoom->school_year_id !==
                (int) $schoolYear->id
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        'La classe sélectionnée ne correspond pas à l’année scolaire sélectionnée.',
                ]);
        }

        return DB::transaction(
            function () use (
                $request,
                $validated,
                $establishment,
                $schoolYear,
                $classRoom
            ) {
                /*
                 * =====================================================
                 * CAS 1 : étudiant existant
                 * =====================================================
                 */
                if (
                    !empty(
                        $validated['student_id']
                    )
                ) {
                    $student =
                        $this->currentStudent(
                            (int) $validated['student_id']
                        );
                } else {
                    /*
                     * =================================================
                     * CAS 2 : création d'un nouvel étudiant
                     * =================================================
                     */
                    if (
                        empty(
                            $validated['nom']
                        ) ||
                        empty(
                            $validated['prenom']
                        )
                    ) {
                        return back()
                            ->withInput()
                            ->withErrors([
                                'nom' =>
                                    'Le nom et le prénom sont obligatoires pour créer un nouvel étudiant.',
                            ]);
                    }

                    $student = Student::create([
                        'establishment_id' =>
                            $establishment->id,

                        'matricule' =>
                            $this->generateMatricule(
                                $establishment
                            ),

                        'nom' =>
                            $validated['nom'],

                        'prenom' =>
                            $validated['prenom'],

                        'date_naissance' =>
                            $validated['date_naissance']
                            ?? null,

                        'lieu_naissance' =>
                            $validated['lieu_naissance']
                            ?? null,

                        'sexe' =>
                            $validated['sexe']
                            ?? null,

                        'adresse' =>
                            $validated['adresse']
                            ?? null,

                        'telephone' =>
                            $validated['telephone']
                            ?? null,

                        'email' =>
                            $validated['email']
                            ?? null,
                    ]);
                }

                /*
                 * =====================================================
                 * Évite une double inscription
                 * =====================================================
                 */
                $alreadyRegistered =
                    Inscription::query()
                        ->where(
                            'student_id',
                            $student->id
                        )
                        ->where(
                            'school_year_id',
                            $schoolYear->id
                        )
                        ->exists();

                if ($alreadyRegistered) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'student_id' =>
                                'Cet étudiant est déjà inscrit pour cette année scolaire.',
                        ]);
                }

                /*
                 * =====================================================
                 * Création de l'inscription
                 * =====================================================
                 *
                 * IMPORTANT :
                 * annee_scolaire est obligatoire dans la table.
                 *
                 * On récupère sa valeur directement depuis
                 * l'année scolaire sélectionnée.
                 */
                $inscriptionData = [
                    'student_id' =>
                        $student->id,

                    'school_year_id' =>
                        $schoolYear->id,

                    'class_room_id' =>
                        $classRoom->id,

                    'annee_scolaire' =>
                        $schoolYear->name,
                ];

                /*
                 * L'inscription appartient toujours
                 * à l'établissement courant.
                 */
                $inscriptionColumns =
                    \Schema::getColumnListing(
                        'inscriptions'
                    );

                if (
                    in_array(
                        'establishment_id',
                        $inscriptionColumns,
                        true
                    )
                ) {
                    $inscriptionData[
                        'establishment_id'
                    ] =
                        $establishment->id;
                }

                /*
                 * Si la table possède matricule,
                 * on l'enregistre également.
                 */
                if (
                    in_array(
                        'matricule',
                        $inscriptionColumns,
                        true
                    )
                ) {
                    $inscriptionData[
                        'matricule'
                    ] =
                        $student->matricule;
                }

                /*
                 * Niveau scolaire si la colonne existe.
                 */
                if (
                    in_array(
                        'niveau_scolaire',
                        $inscriptionColumns,
                        true
                    )
                ) {
                    $inscriptionData[
                        'niveau_scolaire'
                    ] =
                        $classRoom->niveau
                        ?? null;
                }

                /*
                 * Situation scolaire si la colonne existe.
                 */
                if (
                    in_array(
                        'situation_scolaire',
                        $inscriptionColumns,
                        true
                    )
                ) {
                    $inscriptionData[
                        'situation_scolaire'
                    ] =
                        $request->input(
                            'situation_scolaire'
                        );
                }

                /*
                 * Date d'inscription si la colonne existe.
                 */
                if (
                    in_array(
                        'date_inscription',
                        $inscriptionColumns,
                        true
                    )
                ) {
                    $inscriptionData[
                        'date_inscription'
                    ] =
                        $request->input(
                            'date_inscription'
                        )
                        ?? now()->toDateString();
                }

                /*
                 * Statut si la colonne existe.
                 */
                if (
                    in_array(
                        'statut',
                        $inscriptionColumns,
                        true
                    )
                ) {
                    $inscriptionData[
                        'statut'
                    ] =
                        $request->input(
                            'statut'
                        )
                        ?? 'active';
                }

                $inscription =
                    Inscription::create(
                        $inscriptionData
                    );

                return redirect()
                    ->route(
                        'inscriptions.index'
                    )
                    ->with(
                        'success',
                        'Inscription enregistrée avec succès.'
                    );
            }
        );
    }

    /**
     * Affiche une inscription.
     */
    public function show(
        Inscription $inscription
    ) {
        $establishment =
            $this->currentEstablishment();

        $inscription->load([
            'student',
            'classRoom',
            'schoolYear',
        ]);

        if (
            !$inscription->student ||
            (int)
                $inscription->student
                    ->establishment_id !==
                (int) $establishment->id
        ) {
            abort(
                Response::HTTP_FORBIDDEN,
                'Vous n’avez pas accès à cette inscription.'
            );
        }

        if (
            $inscription->classRoom &&
            (int)
                $inscription->classRoom
                    ->establishment_id !==
                (int) $establishment->id
        ) {
            abort(
                Response::HTTP_FORBIDDEN,
                'La classe de cette inscription n’est pas accessible.'
            );
        }

        return view(
            'inscriptions.show',
            compact(
                'inscription',
                'establishment'
            )
        );
    }

    /**
     * Formulaire de modification.
     */
    public function edit(
        Inscription $inscription
    ) {
        $establishment =
            $this->currentEstablishment();

        $inscription->load([
            'student',
            'classRoom',
            'schoolYear',
        ]);

        if (
            !$inscription->student ||
            (int)
                $inscription->student
                    ->establishment_id !==
                (int) $establishment->id
        ) {
            abort(
                Response::HTTP_FORBIDDEN,
                'Vous n’avez pas accès à cette inscription.'
            );
        }

        $schoolYears = SchoolYear::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderByDesc('id')
            ->get();

        $classes = ClassRoom::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderBy('nom_classe')
            ->get();

        $students = Student::query()
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $schoolTypeName =
            $establishment->schoolType?->name
            ?? 'Type non défini';

        return view(
            'inscriptions.edit',
            compact(
                'inscription',
                'establishment',
                'schoolYears',
                'classes',
                'students',
                'schoolTypeName'
            )
        );
    }

    /**
     * Met à jour une inscription.
     */
    public function update(
        Request $request,
        Inscription $inscription
    ) {
        $establishment =
            $this->currentEstablishment();

        /*
         * Vérification d'accès.
         */
        if (
            !$inscription->student ||
            (int)
                $inscription->student
                    ->establishment_id !==
                (int) $establishment->id
        ) {
            abort(
                Response::HTTP_FORBIDDEN,
                'Vous n’avez pas accès à cette inscription.'
            );
        }

        $validated = $request->validate([
            'school_year_id' => [
                'required',
                'integer',
                'exists:school_years,id',
            ],

            'class_room_id' => [
                'required',
                'integer',
                'exists:class_rooms,id',
            ],

            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'situation_scolaire' => [
                'nullable',
                'string',
                'max:255',
            ],

            'date_inscription' => [
                'nullable',
                'date',
            ],

            'statut' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $schoolYear =
            $this->currentSchoolYear(
                (int) $validated[
                    'school_year_id'
                ]
            );

        $classRoom =
            $this->currentClassRoom(
                (int) $validated[
                    'class_room_id'
                ]
            );

        $student =
            $this->currentStudent(
                (int) $validated[
                    'student_id'
                ]
            );

        if (
            $classRoom->school_year_id &&
            (int) $classRoom->school_year_id !==
                (int) $schoolYear->id
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_room_id' =>
                        'La classe sélectionnée ne correspond pas à l’année scolaire sélectionnée.',
                ]);
        }

        /*
         * Vérification d'une éventuelle double inscription.
         */
        $alreadyRegistered =
            Inscription::query()
                ->where(
                    'student_id',
                    $student->id
                )
                ->where(
                    'school_year_id',
                    $schoolYear->id
                )
                ->where(
                    'id',
                    '!=',
                    $inscription->id
                )
                ->exists();

        if ($alreadyRegistered) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' =>
                        'Cet étudiant est déjà inscrit pour cette année scolaire.',
                ]);
        }

        $inscription->student_id =
            $student->id;

        $inscription->school_year_id =
            $schoolYear->id;

        $inscription->class_room_id =
            $classRoom->id;

        $inscription->establishment_id =
            $establishment->id;

        /*
         * Correction importante :
         * garder annee_scolaire synchronisée avec school_year_id.
         */
        $inscription->annee_scolaire =
            $schoolYear->name;

        if (
            array_key_exists(
                'situation_scolaire',
                $validated
            )
        ) {
            $inscription->situation_scolaire =
                $validated[
                    'situation_scolaire'
                ];
        }

        if (
            array_key_exists(
                'date_inscription',
                $validated
            )
        ) {
            $inscription->date_inscription =
                $validated[
                    'date_inscription'
                ];
        }

        if (
            array_key_exists(
                'statut',
                $validated
            )
        ) {
            $inscription->statut =
                $validated['statut'];
        }

        $inscription->save();

        return redirect()
            ->route(
                'inscriptions.index'
            )
            ->with(
                'success',
                'Inscription modifiée avec succès.'
            );
    }

    /**
     * Supprime une inscription.
     */
    public function destroy(
        Inscription $inscription
    ) {
        $establishment =
            $this->currentEstablishment();

        if (
            !$inscription->student ||
            (int)
                $inscription->student
                    ->establishment_id !==
                (int) $establishment->id
        ) {
            abort(
                Response::HTTP_FORBIDDEN,
                'Vous n’avez pas accès à cette inscription.'
            );
        }

        $inscription->delete();

        return redirect()
            ->route(
                'inscriptions.index'
            )
            ->with(
                'success',
                'Inscription supprimée avec succès.'
            );
    }
}