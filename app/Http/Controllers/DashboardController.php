<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Teacher;
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
        $students = $studentsQuery
            ->latest()
            ->get();

        $teachers = $teachersQuery
            ->latest()
            ->get();

        $classes = $classesQuery
            ->orderBy('nom_classe')
            ->get();

        $subjects = $subjectsQuery
            ->orderBy('nom')
            ->get();

        $inscriptions = $inscriptionsQuery
            ->orderByDesc('id')
            ->get();

        $notes = $notesQuery
            ->orderByDesc('id')
            ->get();

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
        | On fournit à la vue :
        |
        | $currentSchoolYear
        | ET
        | $schoolYear
        |
        | afin de rester compatible avec le dashboard.blade.php actuel.
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

                'schoolYears' =>
                    $schoolYears,

                'currentSchoolYear' =>
                    $currentSchoolYear,

                'schoolYear' =>
                    $currentSchoolYear,

                'students' =>
                    $students,

                'teachers' =>
                    $teachers,

                'classes' =>
                    $classes,

                'subjects' =>
                    $subjects,

                'inscriptions' =>
                    $inscriptions,

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
