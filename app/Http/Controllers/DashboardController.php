<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Inscription;
use App\Models\Note;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Afficher le tableau de bord.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | 1. Vérification de l'utilisateur
        |--------------------------------------------------------------------------
        */
        if (!$user) {
            return redirect()->route('login');
        }

        /*
        |--------------------------------------------------------------------------
        | ESPACE PARENT DÉDIÉ
        |--------------------------------------------------------------------------
        | Le parent ne doit pas utiliser le dashboard général.
        | Il est automatiquement envoyé vers son espace consultatif.
        */
        if ($user->role === 'parent') {
            return redirect()->route('parent.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | ESPACE SECRÉTAIRE
        |--------------------------------------------------------------------------
        | Le secrétaire possède son propre dashboard administratif
        | et scolaire. Il ne gère pas les notes.
        */
        if ($user->role === 'secretariat') {
            return $this->secretaireDashboard($request, $user);
        }

        /*
        |--------------------------------------------------------------------------
        | ESPACE COMPTABILITÉ
        |--------------------------------------------------------------------------
        | Le comptable possède son propre dashboard financier.
        | Il ne doit pas utiliser le dashboard général.
        */
        if ($user->role === 'comptable') {
            return $this->comptabiliteDashboard($request, $user);
        }

        /*
        |--------------------------------------------------------------------------
        | ESPACE ENSEIGNANT
        |--------------------------------------------------------------------------
        | L'enseignant possède son propre dashboard pédagogique.
        | Il travaille uniquement avec ses classes, ses matières et ses notes.
        */
        if ($user->role === 'enseignant') {
            return $this->enseignantDashboard($request, $user);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Établissements accessibles
        |--------------------------------------------------------------------------
        |
        | Admin :
        |   → établissements qu'il possède
        |
        | Autres rôles :
        |   → établissements qui lui sont affectés
        |
        */
        if ($user->role === 'admin') {

            $establishments = $user
                ->establishments()
                ->with('schoolType')
                ->get();

        } else {

            $establishments = $user
                ->assignedEstablishments()
                ->with('schoolType')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Établissement courant
        |--------------------------------------------------------------------------
        */
        $establishmentId = session('establishment_id');

        /*
        | Si aucun établissement n'est sélectionné
        */
        if (!$establishmentId) {

            /*
            | S'il n'y en a qu'un, on le sélectionne automatiquement.
            */
            if ($establishments->count() === 1) {

                $currentEstablishment = $establishments->first();

                session([
                    'establishment_id' => $currentEstablishment->id,
                    'establishment_name' => $currentEstablishment->nom,
                ]);

                $establishmentId = $currentEstablishment->id;

            } else {

                return redirect()
                    ->route('establishments.choose')
                    ->with(
                        'error',
                        'Veuillez sélectionner un établissement.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Vérification de l'accès à l'établissement
        |--------------------------------------------------------------------------
        */
        $currentEstablishment = $establishments->firstWhere(
            'id',
            (int) $establishmentId
        );

        if (!$currentEstablishment) {

            session()->forget([
                'establishment_id',
                'establishment_name',
                'school_year_id',
                'school_year_name',
            ]);

            return redirect()
                ->route('establishments.choose')
                ->with(
                    'error',
                    'Vous n\'avez pas accès à cet établissement.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Années scolaires
        |--------------------------------------------------------------------------
        */
        $schoolYears = SchoolYear::where(
            'establishment_id',
            $establishmentId
        )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 6. Année scolaire courante
        |--------------------------------------------------------------------------
        */
        $schoolYearId = session('school_year_id');

        $currentSchoolYear = null;

        /*
        | Vérifier que l'année sélectionnée appartient bien
        | à l'établissement courant.
        */
        if ($schoolYearId) {

            $currentSchoolYear = $schoolYears->firstWhere(
                'id',
                (int) $schoolYearId
            );
        }

        /*
        | Si l'année enregistrée en session n'existe plus
        | pour cet établissement, on la supprime.
        */
        if ($schoolYearId && !$currentSchoolYear) {

            session()->forget([
                'school_year_id',
                'school_year_name',
            ]);

            $schoolYearId = null;
        }

        /*
        | Si aucune année n'est sélectionnée,
        | prendre l'année active.
        */
        if (!$currentSchoolYear) {

            $currentSchoolYear = $schoolYears->firstWhere(
                'is_active',
                true
            );
        }

        /*
        | Si aucune année active n'existe,
        | prendre la première année disponible.
        */
        if (!$currentSchoolYear) {

            $currentSchoolYear = $schoolYears->first();
        }

        /*
        | Mettre l'année courante en session.
        */
        if ($currentSchoolYear) {

            $schoolYearId = $currentSchoolYear->id;

            session([
                'school_year_id' => $currentSchoolYear->id,

                'school_year_name' =>
                    $currentSchoolYear->nom
                    ?? $currentSchoolYear->name
                    ?? $currentSchoolYear->annee_scolaire
                    ?? null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Étudiants
        |--------------------------------------------------------------------------
        */
        $studentsQuery = Student::query()
            ->where(
                'establishment_id',
                $establishmentId
            );

        /*
        |--------------------------------------------------------------------------
        | 8. Enseignants
        |--------------------------------------------------------------------------
        */
        $teachersQuery = Teacher::query()
            ->where(
                'establishment_id',
                $establishmentId
            );

        /*
        |--------------------------------------------------------------------------
        | 9. Classes
        |--------------------------------------------------------------------------
        */
        $classesQuery = ClassRoom::query()
            ->where(
                'establishment_id',
                $establishmentId
            );

        /*
        | Filtrer par année scolaire seulement si
        | la colonne school_year_id existe.
        */
        if (
            $schoolYearId &&
            $this->hasColumn(
                ClassRoom::class,
                'school_year_id'
            )
        ) {

            $classesQuery->where(
                'school_year_id',
                $schoolYearId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 10. Matières
        |--------------------------------------------------------------------------
        */
        $subjectsQuery = Subject::query()
            ->where(
                'establishment_id',
                $establishmentId
            );

        /*
        | Filtrer par année scolaire seulement si
        | la colonne school_year_id existe.
        */
        if (
            $schoolYearId &&
            $this->hasColumn(
                Subject::class,
                'school_year_id'
            )
        ) {

            $subjectsQuery->where(
                'school_year_id',
                $schoolYearId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 11. Inscriptions
        |--------------------------------------------------------------------------
        */
        $inscriptionsQuery = Inscription::query();

        /*
        | Si inscriptions possède establishment_id,
        | on l'utilise directement.
        */
        if (
            $this->hasColumn(
                Inscription::class,
                'establishment_id'
            )
        ) {

            $inscriptionsQuery->where(
                'establishment_id',
                $establishmentId
            );

        } else {

            /*
            | Sinon, on passe par l'étudiant.
            */
            $inscriptionsQuery->whereHas(
                'student',
                function ($query) use ($establishmentId) {

                    $query->where(
                        'establishment_id',
                        $establishmentId
                    );
                }
            );
        }

        /*
        | Filtrer par année scolaire si disponible.
        */
        if (
            $schoolYearId &&
            $this->hasColumn(
                Inscription::class,
                'school_year_id'
            )
        ) {

            $inscriptionsQuery->where(
                'school_year_id',
                $schoolYearId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 12. Notes
        |--------------------------------------------------------------------------
        */
        $notesQuery = Note::query();

        /*
        | Si notes possède establishment_id,
        | on l'utilise directement.
        */
        if (
            $this->hasColumn(
                Note::class,
                'establishment_id'
            )
        ) {

            $notesQuery->where(
                'establishment_id',
                $establishmentId
            );

        } else {

            /*
            | Sinon, on passe par l'étudiant.
            */
            $notesQuery->whereHas(
                'student',
                function ($query) use ($establishmentId) {

                    $query->where(
                        'establishment_id',
                        $establishmentId
                    );
                }
            );
        }

        /*
        | Filtrer par année scolaire si disponible.
        */
        if (
            $schoolYearId &&
            $this->hasColumn(
                Note::class,
                'school_year_id'
            )
        ) {

            $notesQuery->where(
                'school_year_id',
                $schoolYearId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 13. Paiements
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        | La table paiements ne possède PAS establishment_id.
        |
        | La relation est :
        |
        | paiements.student_id
        |        ↓
        | students.id
        |        ↓
        | students.establishment_id
        |
        */
        $paiementsQuery = Paiement::query()
            ->whereHas(
                'student',
                function ($query) use ($establishmentId) {

                    $query->where(
                        'establishment_id',
                        $establishmentId
                    );
                }
            );

        /*
        |--------------------------------------------------------------------------
        | 14. Exécution des requêtes
        |--------------------------------------------------------------------------
        */

        /*
        | Tous les étudiants de l'établissement courant.
        */
        $students = $studentsQuery
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Derniers étudiants
        |--------------------------------------------------------------------------
        |
        | $students est déjà filtré par établissement.
        | On garde les 5 étudiants les plus récemment créés.
        |
        */
        $latestStudents = $students
            ->take(5);

        /*
        |--------------------------------------------------------------------------
        | Répartition des étudiants par sexe
        |--------------------------------------------------------------------------
        |
        | M + Masculin = Hommes
        | F + Féminin  = Femmes
        |
        */
        $studentsBySexe = [

            'M' => $students
                ->filter(function ($student) {

                    return in_array(
                        $student->sexe,
                        ['M', 'Masculin'],
                        true
                    );

                })
                ->count(),

            'F' => $students
                ->filter(function ($student) {

                    return in_array(
                        $student->sexe,
                        ['F', 'Féminin'],
                        true
                    );

                })
                ->count(),
        ];

        /*
        | Enseignants
        */
        $teachers = $teachersQuery
            ->latest()
            ->get();

        /*
        | Classes
        */
        $classes = $classesQuery
            ->orderBy('nom_classe')
            ->get();

        /*
        | Matières
        */
        $subjects = $subjectsQuery
            ->orderBy('nom')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Inscriptions
        |--------------------------------------------------------------------------
        |
        | On charge les relations nécessaires au dashboard :
        | - étudiant
        | - classe
        | - année scolaire
        |
        */
        $inscriptions = $inscriptionsQuery
            ->with([
                'student',
                'classRoom',
                'schoolYear',
            ])
            ->orderByDesc('date_inscription')
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Dernières inscriptions
        |--------------------------------------------------------------------------
        |
        | Les inscriptions sont déjà filtrées par :
        | - établissement courant
        | - année scolaire courante
        |
        */
        $latestInscriptions = $inscriptions
            ->take(5);

        /*
        | Notes
        */
        $notes = $notesQuery
            ->orderByDesc('id')
            ->get();

        /*
        | Paiements
        */
        $paiements = $paiementsQuery
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 15. Statistiques
        |--------------------------------------------------------------------------
        */
        $stats = [

            'students' =>
                $students->count(),

            'teachers' =>
                $teachers->count(),

            'classes' =>
                $classes->count(),

            'subjects' =>
                $subjects->count(),

            'inscriptions' =>
                $inscriptions->count(),

            'notes' =>
                $notes->count(),

            'paiements' =>
                $paiements->count(),

            'school_years' =>
                $schoolYears->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | 16. Affichage du dashboard
        |--------------------------------------------------------------------------
        |
        | On conserve toutes les variables existantes.
        |
        | Variables ajoutées :
        |
        | $establishment
        | $schoolTypeName
        | $latestStudents
        | $latestInscriptions
        |
        */
        return view(
            'dashboard',
            [

                'user' =>
                    $user,

                'establishments' =>
                    $establishments,

                'currentEstablishment' =>
                    $currentEstablishment,

                /*
                | Compatibilité avec dashboard.blade.php
                */
                'establishment' =>
                    $currentEstablishment,

                'schoolTypeName' =>
                    $currentEstablishment
                        ->schoolType
                        ?->name,

                'schoolYears' =>
                    $schoolYears,

                'currentSchoolYear' =>
                    $currentSchoolYear,

                'schoolYear' =>
                    $currentSchoolYear,

                'students' =>
                    $students,

                'latestStudents' =>
                    $latestStudents,

                'studentsBySexe' =>
                    $studentsBySexe,

                'teachers' =>
                    $teachers,

                'classes' =>
                    $classes,

                'subjects' =>
                    $subjects,

                'inscriptions' =>
                    $inscriptions,

                'latestInscriptions' =>
                    $latestInscriptions,

                'notes' =>
                    $notes,

                'paiements' =>
                    $paiements,

                'stats' =>
                    $stats,
]
        );
    }

    /**
     * Dashboard dédié à la Comptabilité.
     *
     * Le Comptable travaille principalement sur :
     * - les paiements ;
     * - les encaissements ;
     * - les reçus ;
     * - le suivi financier de l'année scolaire courante.
     *
     * Il ne gère pas :
     * - les notes ;
     * - les enseignants ;
     * - les classes ;
     * - les matières ;
     * - les utilisateurs ;
     * - les établissements.
     */
    private function comptabiliteDashboard(
        Request $request,
        $user
    ) {
        /*
        |--------------------------------------------------------------------------
        | Établissements accessibles
        |--------------------------------------------------------------------------
        */
        $establishments = $user
            ->assignedEstablishments()
            ->with('schoolType')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Établissement courant
        |--------------------------------------------------------------------------
        */
        $establishmentId = session('establishment_id');

        if (!$establishmentId) {

            if ($establishments->count() === 1) {

                $currentEstablishment = $establishments->first();

                session([
                    'establishment_id' =>
                        $currentEstablishment->id,

                    'establishment_name' =>
                        $currentEstablishment->nom,
                ]);

                $establishmentId =
                    $currentEstablishment->id;

            } else {

                return redirect()
                    ->route('establishments.choose')
                    ->with(
                        'error',
                        'Veuillez sélectionner un établissement.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification de l'accès
        |--------------------------------------------------------------------------
        */
        $currentEstablishment =
            $establishments->firstWhere(
                'id',
                (int) $establishmentId
            );

        if (!$currentEstablishment) {

            session()->forget([
                'establishment_id',
                'establishment_name',
                'school_year_id',
                'school_year_name',
            ]);

            return redirect()
                ->route('establishments.choose')
                ->with(
                    'error',
                    'Vous n\'avez pas accès à cet établissement.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Années scolaires
        |--------------------------------------------------------------------------
        */
        $schoolYears = SchoolYear::where(
            'establishment_id',
            $establishmentId
        )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Année scolaire courante
        |--------------------------------------------------------------------------
        */
        $schoolYearId =
            session('school_year_id');

        $currentSchoolYear = null;

        if ($schoolYearId) {

            $currentSchoolYear =
                $schoolYears->firstWhere(
                    'id',
                    (int) $schoolYearId
                );
        }

        if ($schoolYearId && !$currentSchoolYear) {

            session()->forget([
                'school_year_id',
                'school_year_name',
            ]);

            $schoolYearId = null;
        }

        /*
        | Si aucune année n'est sélectionnée,
        | utiliser l'année active.
        */
        if (!$currentSchoolYear) {

            $currentSchoolYear =
                $schoolYears->firstWhere(
                    'is_active',
                    true
                );
        }

        /*
        | Si aucune année active n'existe,
        | prendre la première année disponible.
        */
        if (!$currentSchoolYear) {

            $currentSchoolYear =
                $schoolYears->first();
        }

        /*
        | Enregistrer l'année courante en session.
        */
        if ($currentSchoolYear) {

            $schoolYearId =
                $currentSchoolYear->id;

            session([
                'school_year_id' =>
                    $currentSchoolYear->id,

                'school_year_name' =>
                    $currentSchoolYear->nom
                    ?? $currentSchoolYear->name
                    ?? $currentSchoolYear->annee_scolaire
                    ?? null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Paiements
        |--------------------------------------------------------------------------
        |
        | On filtre toujours par établissement.
        |
        | La relation étudiant permet également de conserver
        | la compatibilité avec la structure actuelle du projet.
        |
        */
        $paiementsQuery = Paiement::query()
            ->where(function ($query) use ($establishmentId) {

                /*
                | Si establishment_id existe dans paiements,
                | il est utilisé directement.
                */
                if ($this->hasColumn(
                    Paiement::class,
                    'establishment_id'
                )) {

                    $query->where(
                        'establishment_id',
                        $establishmentId
                    );

                } else {

                    /*
                    | Compatibilité avec l'ancienne structure :
                    | paiement → étudiant → établissement.
                    */
                    $query->whereHas(
                        'student',
                        function ($studentQuery)
                        use ($establishmentId) {

                            $studentQuery->where(
                                'establishment_id',
                                $establishmentId
                            );
                        }
                    );
                }
            });

        /*
        |--------------------------------------------------------------------------
        | Filtre année scolaire
        |--------------------------------------------------------------------------
        */
        if (
            $schoolYearId &&
            $this->hasColumn(
                Paiement::class,
                'school_year_id'
            )
        ) {

            $paiementsQuery->where(
                'school_year_id',
                $schoolYearId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtres comptables : tous, mois ou année civile
        |--------------------------------------------------------------------------
        | Ces filtres s'appliquent après le filtre établissement et année scolaire.
        */
        $periodeFiltre = (string) $request->query('periode', 'tous');

        if (!in_array($periodeFiltre, ['tous', 'mois', 'annee'], true)) {
            $periodeFiltre = 'tous';
        }

        $moisFiltre = (int) $request->query('mois', now()->month);
        $anneeFiltre = (int) $request->query('annee', now()->year);

        if ($moisFiltre < 1 || $moisFiltre > 12) {
            $moisFiltre = now()->month;
        }

        if ($anneeFiltre < 2000 || $anneeFiltre > 2100) {
            $anneeFiltre = now()->year;
        }

        if ($periodeFiltre === 'mois') {
            $paiementsQuery
                ->whereYear('date_paiement', $anneeFiltre)
                ->whereMonth('date_paiement', $moisFiltre);
        } elseif ($periodeFiltre === 'annee') {
            $paiementsQuery->whereYear('date_paiement', $anneeFiltre);
        }

        /*
        |--------------------------------------------------------------------------
        | Tous les paiements de l'année courante
        |--------------------------------------------------------------------------
        */
        $paiements = $paiementsQuery
            ->with([
                'student',
                'teacher',
                'schoolYear',
            ])
            ->orderByDesc('date_paiement')
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Paiements du mois courant
        |--------------------------------------------------------------------------
        */
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $paiementsDuMois =
            $paiements->filter(function ($paiement)
            use ($currentMonth, $currentYear) {

                if (!$paiement->date_paiement) {
                    return false;
                }

                $date = \Carbon\Carbon::parse(
                    $paiement->date_paiement
                );

                return
                    $date->month === $currentMonth
                    &&
                    $date->year === $currentYear;
            });

        /*
        |--------------------------------------------------------------------------
        | Total encaissé
        |--------------------------------------------------------------------------
        */
        $totalEncaisse = (float) $paiements
            ->sum(function ($paiement) {

                return (float) $paiement->montant;
            });

        /*
        |--------------------------------------------------------------------------
        | Total des écolages
        |--------------------------------------------------------------------------
        */
        $totalEcolages = (float) $paiements
            ->filter(function ($paiement) {
                return trim((string) $paiement->type_paiement)
                    === 'Écolage mensuel';
            })
            ->sum(function ($paiement) {
                return (float) $paiement->montant;
            });

        /*
        |--------------------------------------------------------------------------
        | Total encaissé ce mois
        |--------------------------------------------------------------------------
        */
        $totalEncaisseMois =
            (float) $paiementsDuMois
                ->sum(function ($paiement) {

                    return (float) $paiement->montant;
                });

        /*
        |--------------------------------------------------------------------------
        | Nombre de paiements
        |--------------------------------------------------------------------------
        */
        $nombrePaiements =
            $paiements->count();

        /*
        |--------------------------------------------------------------------------
        | Nombre de paiements ce mois
        |--------------------------------------------------------------------------
        */
        $nombrePaiementsMois =
            $paiementsDuMois->count();

        /*
        |--------------------------------------------------------------------------
        | Élèves ayant effectué un paiement
        |--------------------------------------------------------------------------
        */
        $elevesAyantPaye =
            $paiements
                ->filter(function ($paiement) {

                    return !empty(
                        $paiement->student_id
                    );
                })
                ->pluck('student_id')
                ->unique()
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Paiements par type
        |--------------------------------------------------------------------------
        */
        $paiementsParType = $paiements
            ->groupBy('type_paiement')
            ->map(function ($items) {

                return [
                    'nombre' =>
                        $items->count(),

                    'montant' =>
                        (float) $items->sum(
                            'montant'
                        ),
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | Derniers paiements
        |--------------------------------------------------------------------------
        */
        $derniersPaiements =
            $paiements->take(8);

        /*
        |--------------------------------------------------------------------------
        | Paiements récents du mois
        |--------------------------------------------------------------------------
        */
        $paiementsRecentsMois =
            $paiementsDuMois->take(5);

        /*
        |--------------------------------------------------------------------------
        | Statistiques principales
        |--------------------------------------------------------------------------
        */
        $stats = [

            'total_encaisse' =>
                $totalEncaisse,

            'total_ecolages' =>
                $totalEcolages,

            'total_encaisse_mois' =>
                $totalEncaisseMois,

            'nombre_paiements' =>
                $nombrePaiements,

            'nombre_paiements_mois' =>
                $nombrePaiementsMois,

            'eleves_ayant_paye' =>
                $elevesAyantPaye,

            'types_paiement' =>
                $paiementsParType,
        ];

        /*
        |--------------------------------------------------------------------------
        | Dashboard Comptabilité
        |--------------------------------------------------------------------------
        */
        return view(
            'dashboard.comptabilite',
            [

                'user' =>
                    $user,

                'establishments' =>
                    $establishments,

                'establishment' =>
                    $currentEstablishment,

                'currentEstablishment' =>
                    $currentEstablishment,

                'schoolTypeName' =>
                    $currentEstablishment
                        ->schoolType
                        ?->name,

                'schoolYears' =>
                    $schoolYears,

                'schoolYear' =>
                    $currentSchoolYear,

                'currentSchoolYear' =>
                    $currentSchoolYear,

                'paiements' =>
                    $paiements,

                'derniersPaiements' =>
                    $derniersPaiements,

                'paiementsRecentsMois' =>
                    $paiementsRecentsMois,

                'paiementsParType' =>
                    $paiementsParType,

                'stats' =>
                    $stats,
                'periodeFiltre' =>
                    $periodeFiltre,

                'moisFiltre' =>
                    $moisFiltre,

                'anneeFiltre' =>
                    $anneeFiltre,

            ]
        );
    }

    /**
     * Dashboard dédié au Secrétaire.
     *
     * Le Secrétaire gère principalement les tâches
     * administratives et scolaires :
     * étudiants, inscriptions, classes, enseignants,
     * matières et emploi du temps.
     *
     * La gestion des notes reste réservée à l'enseignant
     * et aux rôles autorisés.
     */
    /**
     * Dashboard dédié à l'enseignant.
     *
     * L'enseignant ne travaille qu'avec :
     * - son profil Teacher ;
     * - ses établissements affectés ;
     * - ses affectations teacher_subjects ;
     * - ses classes ;
     * - ses matières ;
     * - ses élèves ;
     * - ses notes.
     */
    /**
     * Dashboard dédié à l'enseignant.
     *
     * L'enseignant travaille uniquement avec :
     * - son profil Teacher ;
     * - son établissement ;
     * - l'année scolaire courante ;
     * - ses affectations teacher_subjects ;
     * - ses classes ;
     * - ses matières ;
     * - les élèves inscrits dans ses classes ;
     * - ses propres notes.
     */
    private function enseignantDashboard(Request $request, $user)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Profil enseignant
        |--------------------------------------------------------------------------
        */
        $teacher = $user->teacher()
            ->with('establishment')
            ->first();

        if (!$teacher) {
            return view('dashboard.enseignant', [
                'teacher' => null,
                'currentEstablishment' => null,
                'schoolYears' => collect(),
                'currentSchoolYear' => null,
                'teacherSubjects' => collect(),
                'classes' => collect(),
                'subjects' => collect(),
                'students' => collect(),
                'notes' => collect(),
                'recentNotes' => collect(),
                'notesByType' => collect(),
                'stats' => [
                    'classes' => 0,
                    'subjects' => 0,
                    'students' => 0,
                    'notes' => 0,
                ],
                'profileMissing' => true,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Établissements accessibles
        |--------------------------------------------------------------------------
        */
        $establishments = $user
            ->assignedEstablishments()
            ->with('schoolType')
            ->get();

        /*
        | Le profil Teacher peut également posséder directement
        | un établissement.
        */
        if ($teacher->establishment_id) {

            $teacherEstablishment = Establishment::with('schoolType')
                ->find($teacher->establishment_id);

            if (
                $teacherEstablishment &&
                !$establishments->contains(
                    'id',
                    $teacherEstablishment->id
                )
            ) {
                $establishments->push($teacherEstablishment);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Établissement courant
        |--------------------------------------------------------------------------
        */
        $establishmentId = session('establishment_id');

        $currentEstablishment = null;

        /*
        | Si le profil enseignant possède un établissement,
        | celui-ci est prioritaire.
        */
        if ($teacher->establishment_id) {

            $teacherEstablishment = $establishments->firstWhere(
                'id',
                (int) $teacher->establishment_id
            );

            if ($teacherEstablishment) {
                $currentEstablishment = $teacherEstablishment;
                $establishmentId = $teacherEstablishment->id;
            }
        }

        /*
        | Sinon, on utilise l'établissement de session
        | uniquement s'il est réellement accessible.
        */
        if (!$currentEstablishment && $establishmentId) {

            $currentEstablishment = $establishments->firstWhere(
                'id',
                (int) $establishmentId
            );
        }

        /*
        | Si aucun établissement n'est encore défini
        | et qu'un seul est accessible, on le sélectionne.
        */
        if (!$currentEstablishment && $establishments->count() === 1) {

            $currentEstablishment = $establishments->first();
            $establishmentId = $currentEstablishment->id;
        }

        if (!$currentEstablishment) {

            session()->forget([
                'establishment_id',
                'establishment_name',
                'school_year_id',
                'school_year_name',
            ]);

            return redirect()
                ->route('establishments.choose')
                ->with(
                    'error',
                    'Veuillez sélectionner un établissement accessible.'
                );
        }

        session([
            'establishment_id' => $currentEstablishment->id,
            'establishment_name' => $currentEstablishment->nom,
        ]);

        $establishmentId = $currentEstablishment->id;

        /*
        |--------------------------------------------------------------------------
        | 4. Années scolaires
        |--------------------------------------------------------------------------
        */
        $schoolYears = SchoolYear::where(
            'establishment_id',
            $establishmentId
        )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 5. Année scolaire courante
        |--------------------------------------------------------------------------
        */
        $schoolYearId = session('school_year_id');

        $currentSchoolYear = $schoolYears->firstWhere(
            'id',
            (int) $schoolYearId
        );

        if (!$currentSchoolYear) {

            $currentSchoolYear = $schoolYears
                ->firstWhere('is_active', true);

            if (!$currentSchoolYear) {
                $currentSchoolYear = $schoolYears->first();
            }
        }

        if ($currentSchoolYear) {

            session([
                'school_year_id' => $currentSchoolYear->id,
                'school_year_name' => $currentSchoolYear->name,
            ]);

            $schoolYearId = $currentSchoolYear->id;

        } else {

            session()->forget([
                'school_year_id',
                'school_year_name',
            ]);

            $schoolYearId = null;
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Affectations enseignant
        |--------------------------------------------------------------------------
        */
        $teacherSubjectsQuery = TeacherSubject::query()
            ->with([
                'subject',
                'classRoom',
                'schoolYear',
            ])
            ->where('teacher_id', $teacher->id)
            ->whereHas('classRoom', function ($query) use ($establishmentId) {
                $query->where(
                    'establishment_id',
                    $establishmentId
                );
            });

        if ($schoolYearId) {

            $teacherSubjectsQuery->where(
                'school_year_id',
                $schoolYearId
            );
        }

        $teacherSubjects = $teacherSubjectsQuery
            ->orderBy('class_room_id')
            ->orderBy('subject_id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 7. Classes de l'enseignant
        |--------------------------------------------------------------------------
        */
        $classIds = $teacherSubjects
            ->pluck('class_room_id')
            ->filter()
            ->unique()
            ->values();

        $classes = collect();

        if ($classIds->isNotEmpty()) {

            $classes = ClassRoom::query()
                ->whereIn('id', $classIds)
                ->where(
                    'establishment_id',
                    $establishmentId
                )
                ->when(
                    $schoolYearId,
                    function ($query) use ($schoolYearId) {
                        $query->where(
                            'school_year_id',
                            $schoolYearId
                        );
                    }
                )
                ->with('schoolYear')
                ->orderBy('nom_classe')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | 8. Matières de l'enseignant
        |--------------------------------------------------------------------------
        */
        $subjectIds = $teacherSubjects
            ->pluck('subject_id')
            ->filter()
            ->unique()
            ->values();

        $subjects = collect();

        if ($subjectIds->isNotEmpty()) {

            $subjects = Subject::query()
                ->whereIn('id', $subjectIds)
                ->orderBy('nom')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | 9. Élèves des classes enseignées
        |--------------------------------------------------------------------------
        |
        | On utilise les inscriptions.
        |
        | Teacher
        |   ↓
        | teacher_subjects
        |   ↓
        | class_room_id
        |   ↓
        | inscriptions
        |   ↓
        | student_id
        |
        */
        $students = collect();

        if (
            $classIds->isNotEmpty() &&
            $schoolYearId
        ) {

            $studentIds = Inscription::query()
                ->where('establishment_id', $establishmentId)
                ->where('school_year_id', $schoolYearId)
                ->whereIn('class_room_id', $classIds)
                ->whereNotNull('student_id')
                ->pluck('student_id')
                ->unique()
                ->values();

            if ($studentIds->isNotEmpty()) {

                $students = Student::query()
                    ->where(
                        'establishment_id',
                        $establishmentId
                    )
                    ->whereIn('id', $studentIds)
                    ->orderBy('nom')
                    ->orderBy('prenom')
                    ->get();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 10. Notes de l'enseignant
        |--------------------------------------------------------------------------
        */
        $notesQuery = Note::query()
            ->with([
                'student',
                'subject',
                'classRoom',
                'schoolYear',
            ])
            ->where(
                'teacher_id',
                $teacher->id
            )
            ->where(
                'establishment_id',
                $establishmentId
            );

        if ($schoolYearId) {

            $notesQuery->where(
                'school_year_id',
                $schoolYearId
            );
        }

        $notes = $notesQuery
            ->latest('date_evaluation')
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 11. Statistiques
        |--------------------------------------------------------------------------
        */
        $stats = [
            'classes' => $classes->count(),
            'subjects' => $subjects->count(),
            'students' => $students->count(),
            'notes' => $notes->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | 12. Dernières notes
        |--------------------------------------------------------------------------
        */
        $recentNotes = $notes->take(8);

        /*
        |--------------------------------------------------------------------------
        | 13. Répartition des notes
        |--------------------------------------------------------------------------
        */
        $notesByType = $notes
            ->groupBy('type_evaluation')
            ->map(function ($items) {

                return [
                    'count' => $items->count(),
                    'average' => round(
                        (float) $items->avg('note'),
                        2
                    ),
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | 14. Vue
        |--------------------------------------------------------------------------
        */
        return view('dashboard.enseignant', [
            'teacher' => $teacher,
            'currentEstablishment' => $currentEstablishment,
            'schoolYears' => $schoolYears,
            'currentSchoolYear' => $currentSchoolYear,
            'teacherSubjects' => $teacherSubjects,
            'classes' => $classes,
            'subjects' => $subjects,
            'students' => $students,
            'notes' => $notes,
            'recentNotes' => $recentNotes,
            'notesByType' => $notesByType,
            'stats' => $stats,
            'profileMissing' => false,
        ]);
    }

    private function secretaireDashboard(
        Request $request,
        $user
    ) {
        /*
        |--------------------------------------------------------------------------
        | Établissements accessibles
        |--------------------------------------------------------------------------
        */
        $establishments = $user
            ->assignedEstablishments()
            ->with('schoolType')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Établissement courant
        |--------------------------------------------------------------------------
        */
        $establishmentId = session('establishment_id');

        if (!$establishmentId) {

            if ($establishments->count() === 1) {

                $currentEstablishment = $establishments->first();

                session([
                    'establishment_id' => $currentEstablishment->id,
                    'establishment_name' => $currentEstablishment->nom,
                ]);

                $establishmentId = $currentEstablishment->id;

            } else {

                return redirect()
                    ->route('establishments.choose')
                    ->with(
                        'error',
                        'Veuillez sélectionner un établissement.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Vérification de l'accès
        |--------------------------------------------------------------------------
        */
        $currentEstablishment = $establishments->firstWhere(
            'id',
            (int) $establishmentId
        );

        if (!$currentEstablishment) {

            session()->forget([
                'establishment_id',
                'establishment_name',
                'school_year_id',
                'school_year_name',
            ]);

            return redirect()
                ->route('establishments.choose')
                ->with(
                    'error',
                    'Vous n\'avez pas accès à cet établissement.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Années scolaires
        |--------------------------------------------------------------------------
        */
        $schoolYears = SchoolYear::where(
            'establishment_id',
            $establishmentId
        )
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $schoolYearId = session('school_year_id');

        $currentSchoolYear = null;

        if ($schoolYearId) {

            $currentSchoolYear = $schoolYears->firstWhere(
                'id',
                (int) $schoolYearId
            );
        }

        if ($schoolYearId && !$currentSchoolYear) {

            session()->forget([
                'school_year_id',
                'school_year_name',
            ]);

            $schoolYearId = null;
        }

        if (!$currentSchoolYear) {
            $currentSchoolYear = $schoolYears->firstWhere(
                'is_active',
                true
            );
        }

        if (!$currentSchoolYear) {
            $currentSchoolYear = $schoolYears->first();
        }

        if ($currentSchoolYear) {

            $schoolYearId = $currentSchoolYear->id;

            session([
                'school_year_id' => $currentSchoolYear->id,
                'school_year_name' =>
                    $currentSchoolYear->nom
                    ?? $currentSchoolYear->name
                    ?? $currentSchoolYear->annee_scolaire
                    ?? null,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Étudiants
        |--------------------------------------------------------------------------
        */
        $studentsQuery = Student::query()
            ->where(
                'establishment_id',
                $establishmentId
            );

        $students = $studentsQuery
            ->latest()
            ->get();

        $latestStudents = $students->take(5);

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
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */
        $classesQuery = ClassRoom::query()
            ->where(
                'establishment_id',
                $establishmentId
            );

        if (
            $schoolYearId &&
            $this->hasColumn(
                ClassRoom::class,
                'school_year_id'
            )
        ) {
            $classesQuery->where(
                'school_year_id',
                $schoolYearId
            );
        }

        $classes = $classesQuery
            ->orderBy('nom_classe')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Matières
        |--------------------------------------------------------------------------
        */
        $subjectsQuery = Subject::query()
            ->where(
                'establishment_id',
                $establishmentId
            );

        if (
            $schoolYearId &&
            $this->hasColumn(
                Subject::class,
                'school_year_id'
            )
        ) {
            $subjectsQuery->where(
                'school_year_id',
                $schoolYearId
            );
        }

        $subjects = $subjectsQuery
            ->orderBy('nom')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Inscriptions
        |--------------------------------------------------------------------------
        */
        $inscriptionsQuery = Inscription::query();

        if (
            $this->hasColumn(
                Inscription::class,
                'establishment_id'
            )
        ) {

            $inscriptionsQuery->where(
                'establishment_id',
                $establishmentId
            );

        } else {

            $inscriptionsQuery->whereHas(
                'student',
                function ($query) use ($establishmentId) {
                    $query->where(
                        'establishment_id',
                        $establishmentId
                    );
                }
            );
        }

        if (
            $schoolYearId &&
            $this->hasColumn(
                Inscription::class,
                'school_year_id'
            )
        ) {
            $inscriptionsQuery->where(
                'school_year_id',
                $schoolYearId
            );
        }

        $inscriptions = $inscriptionsQuery
            ->with([
                'student',
                'classRoom',
                'schoolYear',
            ])
            ->orderByDesc('date_inscription')
            ->orderByDesc('id')
            ->get();

        $latestInscriptions = $inscriptions->take(5);

        /*
        |--------------------------------------------------------------------------
        | Statistiques du Secrétaire
        |--------------------------------------------------------------------------
        */
        $studentsForCurrentYear = $schoolYearId
            ? $inscriptions
                ->where('school_year_id', $schoolYearId)
                ->pluck('student_id')
                ->filter()
                ->unique()
                ->count()
            : $students->count();

        $stats = [
            'students' =>
                $studentsForCurrentYear,

            'teachers' =>
                $teachers->count(),

            'classes' =>
                $classes->count(),

            'subjects' =>
                $subjects->count(),

            'inscriptions' =>
                $inscriptions->count(),

            'school_years' =>
                $schoolYears->count(),
        ];

        return view(
            'dashboard.secretaire',
            [
                'user' =>
                    $user,

                'establishments' =>
                    $establishments,

                'establishment' =>
                    $currentEstablishment,

                'currentEstablishment' =>
                    $currentEstablishment,

                'schoolTypeName' =>
                    $currentEstablishment
                        ->schoolType
                        ?->name,

                'schoolYears' =>
                    $schoolYears,

                'schoolYear' =>
                    $currentSchoolYear,

                'currentSchoolYear' =>
                    $currentSchoolYear,

                'students' =>
                    $students,

                'latestStudents' =>
                    $latestStudents,

                'teachers' =>
                    $teachers,

                'classes' =>
                    $classes,

                'subjects' =>
                    $subjects,

                'inscriptions' =>
                    $inscriptions,

                'latestInscriptions' =>
                    $latestInscriptions,

                'stats' =>
                    $stats,
            ]
        );
    }

    /**
     * Vérifier si une colonne existe réellement dans la table
     * correspondant au modèle.
     */
    private function hasColumn(
        string $modelClass,
        string $column
    ): bool {

        try {

            $model = new $modelClass();

            return \Schema::hasColumn(
                $model->getTable(),
                $column
            );

        } catch (\Throwable $e) {

            return false;
        }
    }
}
