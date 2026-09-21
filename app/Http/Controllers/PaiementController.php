<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
     * Types de paiement disponibles.
     */
    private const TYPES_PAIEMENT = [
        'Droit d\'inscription',
        'Écolage mensuel',
        'Cotisation élève',
        'Cotisation enseignant',
        'Autres frais',
    ];

    /**
     * Modes de paiement disponibles.
     */
    private const MODES_PAIEMENT = [
        'Espèces',
        'MVola',
        'Orange Money',
        'Airtel Money',
        'Virement bancaire',
        'Chèque',
    ];

    /**
     * Vérifie qu'un établissement est sélectionné.
     */
    private function establishmentId(): int
    {
        abort_unless(
            session()->has('establishment_id'),
            403,
            'Aucun établissement sélectionné.'
        );

        return (int) session('establishment_id');
    }

    /**
     * Récupère l'année scolaire sélectionnée.
     */
    private function schoolYearId(): ?int
    {
        $id = session('school_year_id');

        return $id ? (int) $id : null;
    }

    /**
     * Vérifie qu'une année scolaire appartient
     * à l'établissement courant.
     */
    private function authorizeSchoolYear(?int $schoolYearId): ?SchoolYear
    {
        if (!$schoolYearId) {
            return null;
        }

        $schoolYear = SchoolYear::where('id', $schoolYearId)
            ->where('establishment_id', $this->establishmentId())
            ->first();

        abort_unless(
            $schoolYear !== null,
            403,
            'Cette année scolaire n\'appartient pas à l\'établissement sélectionné.'
        );

        return $schoolYear;
    }

    /**
     * Vérifie qu'un étudiant appartient
     * à l'établissement courant.
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
     * Vérifie qu'un enseignant appartient
     * à l'établissement courant.
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
     * Vérifie qu'un paiement appartient
     * à l'établissement courant.
     */
    private function authorizePaiement(Paiement $paiement): void
    {
        $establishmentId = $this->establishmentId();

        $paiement->loadMissing([
            'student',
            'teacher',
            'establishment',
            'schoolYear',
        ]);

        abort_unless(
            (int) $paiement->establishment_id === $establishmentId,
            403,
            'Accès interdit à ce paiement.'
        );
    }

    /**
     * Liste des paiements.
     */
    public function index(Request $request)
    {
        $establishmentId = $this->establishmentId();

        $query = Paiement::with([
            'student',
            'teacher',
            'schoolYear',
        ])
            ->where('establishment_id', $establishmentId);

        /*
         * Filtre par type de paiement.
         */
        if ($request->filled('type_paiement')) {
            $query->where(
                'type_paiement',
                $request->type_paiement
            );
        }

        /*
         * Filtre par mode de paiement.
         */
        if ($request->filled('mode_paiement')) {
            $query->where(
                'mode_paiement',
                $request->mode_paiement
            );
        }

        /*
         * Filtre par mois.
         */
        if ($request->filled('mois')) {
            $query->where(
                'mois',
                (int) $request->mois
            );
        }

        /*
         * Filtre par date.
         */
        if ($request->filled('date_debut')) {
            $query->whereDate(
                'date_paiement',
                '>=',
                $request->date_debut
            );
        }

        if ($request->filled('date_fin')) {
            $query->whereDate(
                'date_paiement',
                '<=',
                $request->date_fin
            );
        }

        $paiements = $query
            ->latest('date_paiement')
            ->latest('id')
            ->get();

        $totalPaiements = (clone $query)->sum('montant');

        return view('paiements.index', [
            'paiements' => $paiements,
            'totalPaiements' => $totalPaiements,
            'moisDisponibles' => self::MOIS,
            'typesPaiement' => self::TYPES_PAIEMENT,
            'modesPaiement' => self::MODES_PAIEMENT,
        ]);
    }

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

    $students = Student::where(
        'establishment_id',
        $establishmentId
    )
        ->orderBy('nom')
        ->orderBy('prenom')
        ->get();

    $teachers = Teacher::where(
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
    |
    | Ces valeurs peuvent être envoyées depuis le suivi des
    | écolages mensuels.
    |
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

        'beneficiaire_type' => 'student',
    ];

    /*
    |--------------------------------------------------------------------------
    | Vérification de l'étudiant
    |--------------------------------------------------------------------------
    |
    | On vérifie que l'étudiant appartient bien à l'établissement
    | actuellement sélectionné.
    |
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
    | Vérification du mois
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
    | Vérification de l'année
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
    | Retour vers la vue
    |--------------------------------------------------------------------------
    */

    return view('paiements.create', [
        'students' => $students,
        'teachers' => $teachers,
        'schoolYear' => $schoolYear,
        'moisDisponibles' => self::MOIS,
        'typesPaiement' => self::TYPES_PAIEMENT,
        'modesPaiement' => self::MODES_PAIEMENT,
        'prefill' => $prefill,
    ]);
}
    

    /**
     * Enregistre un nouveau paiement.
     */
    public function store(Request $request)
    {
        $establishmentId = $this->establishmentId();

        $schoolYearId = $this->schoolYearId();

        $this->authorizeSchoolYear($schoolYearId);

        /*
         * Validation générale.
         */
        $validated = $request->validate([
            'beneficiaire_type' => [
                'required',
                Rule::in([
                    'student',
                    'teacher',
                ]),
            ],

            'student_id' => [
                'nullable',
                'exists:students,id',
            ],

            'teacher_id' => [
                'nullable',
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

            'mode_paiement' => [
                'required',
                Rule::in(self::MODES_PAIEMENT),
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
        ]);

        /*
         * Vérification du bénéficiaire.
         */
        if ($validated['beneficiaire_type'] === 'student') {

            if (empty($validated['student_id'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'student_id' =>
                            'Veuillez sélectionner un élève.',
                    ]);
            }

            $student = Student::findOrFail(
                $validated['student_id']
            );

            $this->authorizeStudent($student);

            $teacherId = null;

            $studentId = $student->id;

        } else {

            if (empty($validated['teacher_id'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Veuillez sélectionner un enseignant.',
                    ]);
            }

            $teacher = Teacher::findOrFail(
                $validated['teacher_id']
            );

            $this->authorizeTeacher($teacher);

            $teacherId = $teacher->id;

            $studentId = null;
        }

        /*
         * Cotisation enseignant :
         * elle doit obligatoirement concerner
         * un enseignant.
         */
        if (
            $validated['type_paiement']
            === 'Cotisation enseignant'
            && $validated['beneficiaire_type'] !== 'teacher'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'type_paiement' =>
                        'La cotisation enseignant doit être enregistrée pour un enseignant.',
                ]);
        }

        /*
         * Les autres frais élève doivent concerner
         * un élève.
         */
        if (
            $validated['type_paiement']
            !== 'Cotisation enseignant'
            && $validated['beneficiaire_type'] !== 'student'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'type_paiement' =>
                        'Ce type de paiement doit être enregistré pour un élève.',
                ]);
        }

        /*
         * Écolage mensuel :
         * le mois et l'année deviennent obligatoires.
         */
        if (
            $validated['type_paiement']
            === 'Écolage mensuel'
        ) {
            if (empty($validated['mois'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'mois' =>
                            'Veuillez sélectionner le mois de l\'écolage.',
                    ]);
            }

            if (empty($validated['annee'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'annee' =>
                            'Veuillez indiquer l\'année du mois payé.',
                    ]);
            }
        } else {
            /*
             * Pour les autres types de frais,
             * le mois n'est pas nécessaire.
             */
            $validated['mois'] = null;
            $validated['annee'] = null;
        }

        /*
         * Génération automatique du numéro de reçu.
         */
        $numeroRecu = $this->generateNumeroRecu();

        /*
         * Création du paiement.
         */
        $paiement = Paiement::create([
            'establishment_id' => $establishmentId,

            'school_year_id' => $schoolYearId,

            'student_id' => $studentId,

            'teacher_id' => $teacherId,

            'montant' => $validated['montant'],

            'type_paiement' => $validated['type_paiement'],

            'mois' => $validated['mois'],

            'annee' => $validated['annee'],

            'mode_paiement' => $validated['mode_paiement'],

            'date_paiement' => $validated['date_paiement'],

            'reference' => $validated['reference'] ?? null,

            'numero_recu' => $numeroRecu,

            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('paiements.show', $paiement)
            ->with(
                'success',
                'Paiement enregistré avec succès. Reçu : '
                . $numeroRecu
            );
    }

    /**
     * Afficher un paiement.
     */
    public function show(Paiement $paiement)
    {
        $this->authorizePaiement($paiement);

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

    /**
     * Formulaire de modification.
     */
    public function edit(Paiement $paiement)
    {
        $this->authorizePaiement($paiement);

        $establishmentId = $this->establishmentId();

        $schoolYear = $this->authorizeSchoolYear(
            $paiement->school_year_id
        );

        $students = Student::where(
            'establishment_id',
            $establishmentId
        )
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $teachers = Teacher::where(
            'establishment_id',
            $establishmentId
        )
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('paiements.edit', [
            'paiement' => $paiement,
            'students' => $students,
            'teachers' => $teachers,
            'schoolYear' => $schoolYear,
            'moisDisponibles' => self::MOIS,
            'typesPaiement' => self::TYPES_PAIEMENT,
            'modesPaiement' => self::MODES_PAIEMENT,
        ]);
    }

    /**
     * Modifier un paiement.
     */
    public function update(
        Request $request,
        Paiement $paiement
    ) {
        $this->authorizePaiement($paiement);

        $establishmentId = $this->establishmentId();

        $this->authorizeSchoolYear(
            $paiement->school_year_id
        );

        $validated = $request->validate([
            'beneficiaire_type' => [
                'required',
                Rule::in([
                    'student',
                    'teacher',
                ]),
            ],

            'student_id' => [
                'nullable',
                'exists:students,id',
            ],

            'teacher_id' => [
                'nullable',
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

            'mode_paiement' => [
                'required',
                Rule::in(self::MODES_PAIEMENT),
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
        ]);

        /*
         * Vérification du bénéficiaire.
         */
        if ($validated['beneficiaire_type'] === 'student') {

            if (empty($validated['student_id'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'student_id' =>
                            'Veuillez sélectionner un élève.',
                    ]);
            }

            $student = Student::findOrFail(
                $validated['student_id']
            );

            $this->authorizeStudent($student);

            $studentId = $student->id;

            $teacherId = null;

        } else {

            if (empty($validated['teacher_id'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'teacher_id' =>
                            'Veuillez sélectionner un enseignant.',
                    ]);
            }

            $teacher = Teacher::findOrFail(
                $validated['teacher_id']
            );

            $this->authorizeTeacher($teacher);

            $teacherId = $teacher->id;

            $studentId = null;
        }

        /*
         * Vérification cotisation enseignant.
         */
        if (
            $validated['type_paiement']
            === 'Cotisation enseignant'
            && $validated['beneficiaire_type'] !== 'teacher'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'type_paiement' =>
                        'La cotisation enseignant doit être enregistrée pour un enseignant.',
                ]);
        }

        /*
         * Vérification des paiements élèves.
         */
        if (
            $validated['type_paiement']
            !== 'Cotisation enseignant'
            && $validated['beneficiaire_type'] !== 'student'
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'type_paiement' =>
                        'Ce type de paiement doit être enregistré pour un élève.',
                ]);
        }

        /*
         * Gestion du mois pour l'écolage.
         */
        if (
            $validated['type_paiement']
            === 'Écolage mensuel'
        ) {
            if (empty($validated['mois'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'mois' =>
                            'Veuillez sélectionner le mois de l\'écolage.',
                    ]);
            }

            if (empty($validated['annee'])) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'annee' =>
                            'Veuillez indiquer l\'année du mois payé.',
                    ]);
            }
        } else {
            $validated['mois'] = null;
            $validated['annee'] = null;
        }

        $paiement->update([
            'establishment_id' => $establishmentId,

            'student_id' => $studentId,

            'teacher_id' => $teacherId,

            'montant' => $validated['montant'],

            'type_paiement' => $validated['type_paiement'],

            'mois' => $validated['mois'],

            'annee' => $validated['annee'],

            'mode_paiement' => $validated['mode_paiement'],

            'date_paiement' => $validated['date_paiement'],

            'reference' => $validated['reference'] ?? null,

            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('paiements.show', $paiement)
            ->with(
                'success',
                'Paiement modifié avec succès.'
            );
    }

    /**
     * Supprimer un paiement.
     */
    public function destroy(Paiement $paiement)
    {
        $this->authorizePaiement($paiement);

        $paiement->delete();

        return redirect()
            ->route('paiements.index')
            ->with(
                'success',
                'Paiement supprimé avec succès.'
            );
    }

    /**
     * Générer automatiquement un numéro de reçu.
     */
    private function generateNumeroRecu(): string
    {
        do {
            $numero = 'REC-'
                . now()->format('Y')
                . '-'
                . strtoupper(
                    substr(
                        uniqid(),
                        -6
                    )
                );

        } while (
            Paiement::where(
                'numero_recu',
                $numero
            )->exists()
        );

        return $numero;
    }
}