<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolYear;
use App\Models\Inscription;
use App\Models\EcolageTarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class PaiementController extends Controller
{
    /**
     * Mois disponibles pour l'écolage mensuel.
     */
    private const MOIS = [
        1  => 'Janvier',
        2  => 'Février',
        3  => 'Mars',
        4  => 'Avril',
        5  => 'Mai',
        6  => 'Juin',
        7  => 'Juillet',
        8  => 'Août',
        9  => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre',
    ];

    /**
     * Types de frais/paiements.
     *
     * Le type de paiement reste nécessaire pour savoir
     * ce que l'élève ou l'enseignant a payé.
     */
    private const TYPES_PAIEMENT = [
        'Droit d\'inscription',
        'Écolage mensuel',
        'Cotisation élève',
        'Cotisation enseignant',
        'Autres frais',
    ];

    /**
     * Mode de paiement unique.
     *
     * Tous les paiements de l'établissement sont enregistrés
     * comme paiements en espèces.
     */
    private const MODE_PAIEMENT = 'Espèces';

    /*
    |--------------------------------------------------------------------------
    | CONTEXTE
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne l'identifiant de l'établissement sélectionné.
     */
    private function establishmentId(): int
    {
        abort_unless(
            session()->has('establishment_id'),
            403,
            'Aucun établissement sélectionné.'
        );

        $id = (int) session('establishment_id');

        abort_if(
            $id <= 0,
            403,
            'Établissement sélectionné invalide.'
        );

        $user = auth()->user();

        abort_unless(
            $user !== null,
            403,
            'Authentification requise.'
        );

        if ($user->role === 'admin') {
            $hasAccess = \App\Models\Establishment::query()
                ->whereKey($id)
                ->exists();
        } else {
            $hasAccess = $user->assignedEstablishments()
                ->where('establishments.id', $id)
                ->exists();
        }

        abort_unless(
            $hasAccess,
            403,
            'Vous n’avez pas accès à cet établissement.'
        );

        return $id;
    }

    /**
     * Retourne l'identifiant de l'année scolaire sélectionnée.
     */
    private function schoolYearId(): ?int
    {
        $id = session('school_year_id');

        if (!$id) {
            return null;
        }

        return (int) $id;
    }

    /**
     * Vérifie et retourne une année scolaire appartenant
     * à l'établissement courant.
     */
    private function authorizeSchoolYear(
        ?int $schoolYearId
    ): ?SchoolYear {
        if (!$schoolYearId) {
            return null;
        }

        $schoolYear = SchoolYear::query()
            ->whereKey($schoolYearId)
            ->where(
                'establishment_id',
                $this->establishmentId()
            )
            ->first();

        abort_unless(
            $schoolYear !== null,
            403,
            'Cette année scolaire n\'appartient pas à l\'établissement sélectionné.'
        );

        return $schoolYear;
    }

    /*
    |--------------------------------------------------------------------------
    | AUTORISATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Vérifie qu'un étudiant appartient à l'établissement courant.
     */
    private function authorizeStudent(Student $student): void
    {
        abort_unless(
            (int) $student->establishment_id === $this->establishmentId(),
            403,
            'Accès interdit à cet étudiant.'
        );
    }

    /**
     * Vérifie qu'un enseignant appartient à l'établissement courant.
     */
    private function authorizeTeacher(Teacher $teacher): void
    {
        abort_unless(
            (int) $teacher->establishment_id === $this->establishmentId(),
            403,
            'Accès interdit à cet enseignant.'
        );
    }

    /**
     * Vérifie qu'un paiement appartient à l'établissement courant.
     */
    private function authorizePaiement(Paiement $paiement): void
    {
        $paiement->loadMissing([
            'student',
            'teacher',
            'establishment',
            'schoolYear',
        ]);

        abort_unless(
            (int) $paiement->establishment_id === $this->establishmentId(),
            403,
            'Accès interdit à ce paiement.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REQUÊTES COMMUNES
    |--------------------------------------------------------------------------
    */

    /**
     * Base de requête des paiements de l'établissement courant.
     */
    private function paiementQuery(): Builder
    {
        return Paiement::query()
            ->where(
                'establishment_id',
                $this->establishmentId()
            );
    }

    /**
     * Applique l'année scolaire sélectionnée.
     */
    private function applyCurrentSchoolYear(
        Builder $query,
        bool $onlyIfSelected = true
    ): Builder {
        $schoolYearId = $this->schoolYearId();

        if ($schoolYearId) {
            $query->where(
                'school_year_id',
                $schoolYearId
            );
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    /**
     * Règles communes de validation.
     *
     * IMPORTANT :
     * Le mode de paiement n'est plus envoyé comme un choix utilisateur.
     * Le contrôleur impose toujours "Espèces".
     */
    private function paymentValidationRules(): array
    {
        return [
            'beneficiaire_type' => [
                'required',
                Rule::in([
                    'student',
                    'teacher',
                ]),
            ],

            'student_id' => [
                'nullable',
                'integer',
                'exists:students,id',
            ],

            'teacher_id' => [
                'nullable',
                'integer',
                'exists:teachers,id',
            ],

            'montant' => [
                'required',
                'numeric',
                'min:1',
            ],

            'type_paiement' => [
                'required',
                Rule::in(self::TYPES_PAIEMENT),
            ],

            'mois' => [
                'nullable',
                'integer',
                'between:1,12',
            ],

            'annee' => [
                'nullable',
                'integer',
                'between:2000,2100',
            ],

            'date_paiement' => [
                'required',
                'date',
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Résout le bénéficiaire.
     *
     * @return array{
     *     student_id: ?int,
     *     teacher_id: ?int
     * }
     */
    private function resolveBeneficiary(
        array $validated
    ): array {
        if ($validated['beneficiaire_type'] === 'student') {

            if (empty($validated['student_id'])) {
                abort(
                    422,
                    'Veuillez sélectionner un élève.'
                );
            }

            $student = Student::query()
                ->whereKey($validated['student_id'])
                ->where(
                    'establishment_id',
                    $this->establishmentId()
                )
                ->first();

            abort_unless(
                $student !== null,
                403,
                'L\'élève sélectionné n\'appartient pas à l\'établissement courant.'
            );

            return [
                'student_id' => (int) $student->id,
                'teacher_id' => null,
            ];
        }

        if (empty($validated['teacher_id'])) {
            abort(
                422,
                'Veuillez sélectionner un enseignant.'
            );
        }

        $teacher = Teacher::query()
            ->whereKey($validated['teacher_id'])
            ->where(
                'establishment_id',
                $this->establishmentId()
            )
            ->first();

        abort_unless(
            $teacher !== null,
            403,
            'L\'enseignant sélectionné n\'appartient pas à l\'établissement courant.'
        );

        return [
            'student_id' => null,
            'teacher_id' => (int) $teacher->id,
        ];
    }

    /**
     * Vérifie la cohérence entre le type de paiement
     * et le bénéficiaire.
     */
    private function validatePaymentType(
        array $validated
    ): void {
        $type = $validated['type_paiement'];
        $beneficiaireType = $validated['beneficiaire_type'];

        /*
         * Cotisation enseignant => enseignant obligatoire.
         */
        if (
            $type === 'Cotisation enseignant'
            && $beneficiaireType !== 'teacher'
        ) {
            abort(
                422,
                'La cotisation enseignant doit être enregistrée pour un enseignant.'
            );
        }

        /*
         * Tous les autres types => élève obligatoire.
         */
        if (
            $type !== 'Cotisation enseignant'
            && $beneficiaireType !== 'student'
        ) {
            abort(
                422,
                'Ce type de paiement doit être enregistré pour un élève.'
            );
        }
    }

    /**
     * Prépare le mois et l'année.
     */
    private function normalizeMonthAndYear(
        array $validated
    ): array {
        if (
            $validated['type_paiement']
            === 'Écolage mensuel'
        ) {
            if (empty($validated['mois'])) {
                abort(
                    422,
                    'Veuillez sélectionner le mois de l\'écolage.'
                );
            }

            if (empty($validated['annee'])) {
                abort(
                    422,
                    'Veuillez indiquer l\'année du mois payé.'
                );
            }

            $validated['mois'] = (int) $validated['mois'];
            $validated['annee'] = (int) $validated['annee'];

            return $validated;
        }

        /*
         * Les autres types n'utilisent pas le mois d'écolage.
         */
        $validated['mois'] = null;
        $validated['annee'] = null;

        return $validated;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    /**
     * Liste des paiements.
     */
    public function index(Request $request)
    {
        $query = $this->paiementQuery()
            ->with([
                'student',
                'teacher',
                'schoolYear',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Année scolaire sélectionnée
        |--------------------------------------------------------------------------
        */

        $schoolYearId = $this->schoolYearId();

        if (!$schoolYearId) {
            return redirect()
                ->route('school_years.index')
                ->with(
                    'error',
                    'Veuillez sélectionner une année scolaire.'
                );
        }

        $schoolYear = $this->authorizeSchoolYear(
            $schoolYearId
        );

        $schoolYearId = $schoolYear->id;

        $query->where(
            'school_year_id',
            $schoolYearId
        );

        /*
        |--------------------------------------------------------------------------
        | Filtres
        |--------------------------------------------------------------------------
        */

        if ($request->filled('type_paiement')) {
            $query->where(
                'type_paiement',
                $request->input('type_paiement')
            );
        }

        /*
         * Le mode est toujours Espèces.
         */
        $query->where(
            'mode_paiement',
            self::MODE_PAIEMENT
        );

        if ($request->filled('mois')) {
            $query->where(
                'mois',
                (int) $request->input('mois')
            );
        }

        if ($request->filled('date_debut')) {
            $query->whereDate(
                'date_paiement',
                '>=',
                $request->input('date_debut')
            );
        }

        if ($request->filled('date_fin')) {
            $query->whereDate(
                'date_paiement',
                '<=',
                $request->input('date_fin')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Liste
        |--------------------------------------------------------------------------
        */

        $paiements = (clone $query)
            ->latest('date_paiement')
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Total selon les filtres
        |--------------------------------------------------------------------------
        */

        $totalPaiements = (clone $query)
            ->sum('montant');

        /*
        |--------------------------------------------------------------------------
        | Statistiques financières
        |--------------------------------------------------------------------------
        */

        $statsQuery = function () use ($schoolYearId) {
            return $this->paiementQuery()
                ->where(
                    'school_year_id',
                    $schoolYearId
                )
                ->where(
                    'mode_paiement',
                    self::MODE_PAIEMENT
                );
        };

        /*
        * Total encaissé aujourd'hui.
        */
        $totalJour = $statsQuery()
            ->whereDate(
                'date_paiement',
                today()
            )
            ->sum('montant');

        /*
        * Total encaissé ce mois.
        */
        $totalMois = $statsQuery()
            ->whereYear(
                'date_paiement',
                now()->year
            )
            ->whereMonth(
                'date_paiement',
                now()->month
            )
            ->sum('montant');

        /*
        * Total écolage.
        */
        $totalEcolage = $statsQuery()
            ->where(
                'type_paiement',
                'Écolage mensuel'
            )
            ->sum('montant');

        /*
        * Total droits d'inscription.
        */
        $totalInscriptions = $statsQuery()
            ->where(
                'type_paiement',
                'Droit d\'inscription'
            )
            ->sum('montant');

        /*
        * Cotisations élèves.
        */
        $totalCotisationEleves = $statsQuery()
            ->where(
                'type_paiement',
                'Cotisation élève'
            )
            ->sum('montant');

        /*
        * Cotisations enseignants.
        */
        $totalCotisationEnseignants = $statsQuery()
            ->where(
                'type_paiement',
                'Cotisation enseignant'
            )
            ->sum('montant');

        /*
        * Autres frais.
        */
        $totalAutresFrais = $statsQuery()
            ->where(
                'type_paiement',
                'Autres frais'
            )
            ->sum('montant');

        /*
        |--------------------------------------------------------------------------
        | Répartition par mode
        |--------------------------------------------------------------------------
        |
        | Il n'y a maintenant qu'un seul mode : Espèces.
        |
        */

        $totauxParMode = $statsQuery()
            ->selectRaw(
                'mode_paiement, SUM(montant) as total'
            )
            ->groupBy('mode_paiement')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Répartition par type
        |--------------------------------------------------------------------------
        */

        $totauxParType = $statsQuery()
            ->selectRaw(
                'type_paiement, SUM(montant) as total'
            )
            ->groupBy('type_paiement')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Nombre d'élèves ayant effectué un paiement
        |--------------------------------------------------------------------------
        */

        $nombreEleves = $statsQuery()
            ->whereNotNull('student_id')
            ->distinct()
            ->count('student_id');

        /*
        |--------------------------------------------------------------------------
        | Nombre d'enseignants ayant effectué un paiement
        |--------------------------------------------------------------------------
        */

        $nombreEnseignants = $statsQuery()
            ->whereNotNull('teacher_id')
            ->distinct()
            ->count('teacher_id');

        /*
        |--------------------------------------------------------------------------
        | Nombre de paiements aujourd'hui
        |--------------------------------------------------------------------------
        */

        $nombrePaiementsJour = $statsQuery()
            ->whereDate(
                'date_paiement',
                today()
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | 10 derniers paiements
        |--------------------------------------------------------------------------
        */

        $derniersPaiements = $statsQuery()
            ->with([
                'student',
                'teacher',
                'schoolYear',
            ])
            ->latest('date_paiement')
            ->latest('id')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Retour vue
        |--------------------------------------------------------------------------
        */

        return view('paiements.index', [
            'paiements' => $paiements,

            'totalPaiements' => $totalPaiements,

            'moisDisponibles' => self::MOIS,

            'typesPaiement' => self::TYPES_PAIEMENT,

            /*
             * Conservé pour compatibilité avec la vue existante.
             * Il ne contient qu'Espèces.
             */
            'modesPaiement' => [
                self::MODE_PAIEMENT,
            ],

            'modePaiement' => self::MODE_PAIEMENT,

            'totalJour' => $totalJour,

            'totalMois' => $totalMois,

            'totalEcolage' => $totalEcolage,

            'totalInscriptions' => $totalInscriptions,

            'totalCotisationEleves' => $totalCotisationEleves,

            'totalCotisationEnseignants' => $totalCotisationEnseignants,

            'totalAutresFrais' => $totalAutresFrais,

            'totauxParMode' => $totauxParMode,

            'totauxParType' => $totauxParType,

            'nombreEleves' => $nombreEleves,

            'nombreEnseignants' => $nombreEnseignants,

            'nombrePaiementsJour' => $nombrePaiementsJour,

            'derniersPaiements' => $derniersPaiements,

            'schoolYearId' => $schoolYearId,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Formulaire de création.
     */
    public function create(Request $request)
    {
        $establishmentId = $this->establishmentId();

        $schoolYearId = $this->schoolYearId();

        $schoolYear = $this->authorizeSchoolYear(
            $schoolYearId
        );

        /*
        |--------------------------------------------------------------------------
        | Élèves
        |--------------------------------------------------------------------------
        */

        $students = Student::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Enseignants
        |--------------------------------------------------------------------------
        */

        $teachers = Teacher::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Préremplissage
        |--------------------------------------------------------------------------
        */

        $prefill = [
            'student_id' => $request->input('student_id'),

            'mois' => $request->input('mois'),

            'annee' => $request->input(
                'annee',
                now()->year
            ),

            'type_paiement' => $request->input(
                'type_paiement',
                'Écolage mensuel'
            ),

            'beneficiaire_type' => $request->input(
                'beneficiaire_type',
                'student'
            ),

            /*
             * Toujours Espèces.
             */
            'mode_paiement' => self::MODE_PAIEMENT,
        ];

        /*
        |--------------------------------------------------------------------------
        | Vérification bénéficiaire
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $prefill['beneficiaire_type'],
                ['student', 'teacher'],
                true
            )
        ) {
            $prefill['beneficiaire_type'] = 'student';
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification étudiant
        |--------------------------------------------------------------------------
        */

        if (!empty($prefill['student_id'])) {

            $studentExists = $students->contains(
                'id',
                (int) $prefill['student_id']
            );

            if (!$studentExists) {
                $prefill['student_id'] = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification mois
        |--------------------------------------------------------------------------
        */

        if (
            $prefill['mois'] !== null
            && (
                !is_numeric($prefill['mois'])
                || (int) $prefill['mois'] < 1
                || (int) $prefill['mois'] > 12
            )
        ) {
            $prefill['mois'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification année
        |--------------------------------------------------------------------------
        */

        if (
            !is_numeric($prefill['annee'])
            || (int) $prefill['annee'] < 2000
            || (int) $prefill['annee'] > 2100
        ) {
            $prefill['annee'] = now()->year;
        }

        /*
        |--------------------------------------------------------------------------
        | Normalisation
        |--------------------------------------------------------------------------
        */

        if ($prefill['student_id'] !== null) {
            $prefill['student_id'] = (int) $prefill['student_id'];
        }

        if ($prefill['mois'] !== null) {
            $prefill['mois'] = (int) $prefill['mois'];
        }

        $prefill['annee'] = (int) $prefill['annee'];

        /*
        |--------------------------------------------------------------------------
        | Retour vue
        |--------------------------------------------------------------------------
        */

        return view('paiements.create', [
            'students' => $students,

            'teachers' => $teachers,

            'schoolYear' => $schoolYear,

            'moisDisponibles' => self::MOIS,

            'typesPaiement' => self::TYPES_PAIEMENT,

            /*
             * Uniquement Espèces.
             */
            'modesPaiement' => [
                self::MODE_PAIEMENT,
            ],

            'modePaiement' => self::MODE_PAIEMENT,

            'prefill' => $prefill,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    /**
     * Enregistre un nouveau paiement.
     *
     * IMPORTANT :
     * Aucun système de confirmation.
     * Aucune preuve de paiement.
     * Aucun système de vérification.
     *
     * Dès que le formulaire est validé :
     * paiement enregistré -> reçu généré -> fiche reçu.
     */
    public function store(Request $request)
    {
        $establishmentId = $this->establishmentId();

        /*
        |--------------------------------------------------------------------------
        | Année scolaire
        |--------------------------------------------------------------------------
        */

        $schoolYearId = $this->schoolYearId();

        $this->authorizeSchoolYear(
            $schoolYearId
        );

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            $this->paymentValidationRules()
        );

        /*
        |--------------------------------------------------------------------------
        | Bénéficiaire
        |--------------------------------------------------------------------------
        */

        $beneficiary = $this->resolveBeneficiary(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Type de paiement
        |--------------------------------------------------------------------------
        */

        $this->validatePaymentType(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Mois / année
        |--------------------------------------------------------------------------
        */

        $validated = $this->normalizeMonthAndYear(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Création transactionnelle
        |--------------------------------------------------------------------------
        */

        $paiement = DB::transaction(
            function () use (
                $validated,
                $beneficiary,
                $establishmentId,
                $schoolYearId
            ) {
                $numeroRecu = $this->generateNumeroRecu();

                return Paiement::create([
                    'establishment_id' => $establishmentId,

                    'school_year_id' => $schoolYearId,

                    'student_id' => $beneficiary['student_id'],

                    'teacher_id' => $beneficiary['teacher_id'],

                    'montant' => $validated['montant'],

                    'type_paiement' => $validated['type_paiement'],

                    'mois' => $validated['mois'],

                    'annee' => $validated['annee'],

                    /*
                     * Toujours Espèces.
                     */
                    'mode_paiement' => self::MODE_PAIEMENT,

                    'date_paiement' => $validated['date_paiement'],

                    'reference' => $validated['reference'] ?? null,

                    'numero_recu' => $numeroRecu,

                    'description' => $validated['description'] ?? null,
                ]);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Redirection vers la fiche du reçu
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'paiements.show',
                $paiement
            )
            ->with(
                'success',
                'Paiement en espèces enregistré avec succès. Reçu : '
                . $paiement->numero_recu
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    /**
     * Affiche la fiche d'un paiement.
     */
    public function show(Paiement $paiement)
    {
        $this->authorizePaiement(
            $paiement
        );

        $this->authorizeSchoolYear(
            $paiement->school_year_id
        );

        $paiement->load([
            'student',
            'teacher',
            'establishment',
            'schoolYear',
        ]);

        return view(
            'paiements.show',
            compact('paiement')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    /**
     * Formulaire de modification.
     */
    public function edit(Paiement $paiement)
    {
        $this->authorizePaiement(
            $paiement
        );

        $establishmentId = $this->establishmentId();

        /*
        |--------------------------------------------------------------------------
        | Année scolaire du paiement
        |--------------------------------------------------------------------------
        */

        $schoolYear = $this->authorizeSchoolYear(
            $paiement->school_year_id
        );

        /*
        |--------------------------------------------------------------------------
        | Élèves
        |--------------------------------------------------------------------------
        */

        $students = Student::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Enseignants
        |--------------------------------------------------------------------------
        */

        $teachers = Teacher::query()
            ->where(
                'establishment_id',
                $establishmentId
            )
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Type de bénéficiaire
        |--------------------------------------------------------------------------
        */

        $beneficiaireType = $paiement->teacher_id
            ? 'teacher'
            : 'student';

        return view('paiements.edit', [
            'paiement' => $paiement,

            'students' => $students,

            'teachers' => $teachers,

            'schoolYear' => $schoolYear,

            'moisDisponibles' => self::MOIS,

            'typesPaiement' => self::TYPES_PAIEMENT,

            /*
             * Toujours Espèces.
             */
            'modesPaiement' => [
                self::MODE_PAIEMENT,
            ],

            'modePaiement' => self::MODE_PAIEMENT,

            'beneficiaireType' => $beneficiaireType,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Modifie un paiement existant.
     */
    public function update(
        Request $request,
        Paiement $paiement
    ) {
        $this->authorizePaiement(
            $paiement
        );

        $establishmentId = $this->establishmentId();

        /*
        |--------------------------------------------------------------------------
        | Année scolaire
        |--------------------------------------------------------------------------
        */

        $schoolYearId = $paiement->school_year_id;

        $this->authorizeSchoolYear(
            $schoolYearId
        );

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            $this->paymentValidationRules()
        );

        /*
        |--------------------------------------------------------------------------
        | Bénéficiaire
        |--------------------------------------------------------------------------
        */

        $beneficiary = $this->resolveBeneficiary(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Type de paiement
        |--------------------------------------------------------------------------
        */

        $this->validatePaymentType(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Mois / année
        |--------------------------------------------------------------------------
        */

        $validated = $this->normalizeMonthAndYear(
            $validated
        );

        /*
        |--------------------------------------------------------------------------
        | Vérification du reste à payer avant modification
        |--------------------------------------------------------------------------
        */
        if ($validated['type_paiement'] === 'Écolage mensuel') {
            if (!$schoolYearId) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'type_paiement' => 'Aucune année scolaire n’est associée à ce paiement.',
                    ]);
            }

            $inscription = Inscription::with('classRoom')
                ->where('establishment_id', $establishmentId)
                ->where('school_year_id', $schoolYearId)
                ->where('student_id', $beneficiary['student_id'])
                ->first();

            if (!$inscription) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'student_id' => 'Cet élève n’est pas inscrit dans cet établissement pour cette année scolaire.',
                    ]);
            }

            $tarif = EcolageTarif::query()
                ->where('establishment_id', $establishmentId)
                ->where('school_year_id', $schoolYearId)
                ->where('class_room_id', $inscription->class_room_id)
                ->first();

            if ($tarif) {
                $alreadyPaidWithoutCurrent = (float) Paiement::query()
                    ->where('establishment_id', $establishmentId)
                    ->where('school_year_id', $schoolYearId)
                    ->where('student_id', $beneficiary['student_id'])
                    ->where('type_paiement', 'Écolage mensuel')
                    ->where('mois', $validated['mois'])
                    ->where('annee', $validated['annee'])
                    ->where('id', '!=', $paiement->id)
                    ->sum('montant');

                $remaining = max(
                    0,
                    (float) $tarif->montant_mensuel - $alreadyPaidWithoutCurrent
                );

                if ((float) $validated['montant'] > $remaining) {
                    return back()
                        ->withInput()
                        ->withErrors([
                            'montant' => sprintf(
                                'Le montant dépasse le reste à payer. Montant maximum autorisé : %s Ar.',
                                number_format($remaining, 0, ',', ' ')
                            ),
                        ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Mise à jour transactionnelle
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $paiement,
                $validated,
                $beneficiary,
                $establishmentId
            ) {
                $paiement->update([
                    /*
                     * L'établissement reste celui du paiement.
                     */
                    'establishment_id' => $establishmentId,

                    /*
                     * L'année scolaire n'est pas modifiée.
                     */
                    'student_id' => $beneficiary['student_id'],

                    'teacher_id' => $beneficiary['teacher_id'],

                    'montant' => $validated['montant'],

                    'type_paiement' => $validated['type_paiement'],

                    'mois' => $validated['mois'],

                    'annee' => $validated['annee'],

                    /*
                     * Conserve le mode de paiement historique.
                     * Les nouveaux paiements restent en Espèces.
                     */
                    'mode_paiement' => $paiement->mode_paiement ?: self::MODE_PAIEMENT,

                    'date_paiement' => $validated['date_paiement'],

                    'reference' => $validated['reference'] ?? null,

                    'description' => $validated['description'] ?? null,
                ]);
            }
        );

        return redirect()
            ->route(
                'paiements.show',
                $paiement
            )
            ->with(
                'success',
                'Paiement modifié avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    /**
     * Supprime un paiement.
     */
    public function destroy(Paiement $paiement)
    {
        $this->authorizePaiement(
            $paiement
        );

        $this->authorizeSchoolYear(
            $paiement->school_year_id
        );

        DB::transaction(
            function () use ($paiement) {
                $paiement->delete();
            }
        );

        return redirect()
            ->route('paiements.index')
            ->with(
                'success',
                'Paiement supprimé avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | REÇU PDF
    |--------------------------------------------------------------------------
    */

    /**
     * Génère le reçu PDF directement.
     *
     * IMPORTANT :
     * Le reçu ne dépend plus d'une vérification.
     *
     * Un paiement enregistré est immédiatement imprimable.
     */
    public function recu(Paiement $paiement)
    {
        $this->authorizePaiement(
            $paiement
        );

        $this->authorizeSchoolYear(
            $paiement->school_year_id
        );

        $paiement->load([
            'student',
            'teacher',
            'establishment',
            'schoolYear',
        ]);

        $pdf = app('dompdf.wrapper');

        $pdf->loadView(
            'paiements.recu',
            compact('paiement')
        );

        $numeroRecu = $paiement->numero_recu
            ?: 'paiement-' . $paiement->id;

        return $pdf->download(
            'recu-' . $numeroRecu . '.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NUMÉRO DE REÇU
    |--------------------------------------------------------------------------
    */

    /**
     * Génère automatiquement un numéro de reçu unique.
     *
     * Exemple :
     *
     * REC-2026-A4F91C
     */
    private function generateNumeroRecu(): string
    {
        do {
            $numero = 'REC-'
                . now()->format('Y')
                . '-'
                . strtoupper(
                    substr(
                        bin2hex(
                            random_bytes(4)
                        ),
                        0,
                        6
                    )
                );

        } while (
            Paiement::query()
                ->where(
                    'numero_recu',
                    $numero
                )
                ->exists()
        );

        return $numero;
    }
}