<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\Inscription;
use App\Models\Note;
use App\Models\ParentModel;
use App\Models\Paiement;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    /**
     * Espace parent — consultation uniquement.
     *
     * IMPORTANT :
     * Un parent ne peut consulter que les élèves qui lui sont
     * explicitement rattachés dans la table parent_student.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== 'parent') {
            return redirect()->route('dashboard');
        }

        /*
         * Profils parent du compte connecté.
         */
        $parentProfiles = ParentModel::query()
            ->where('user_id', $user->id)
            ->with([
                'establishment.schoolType',
                'students',
            ])
            ->get();

        /*
         * Établissements accessibles.
         *
         * On conserve la logique existante :
         * - établissements attribués au compte ;
         * - établissements des profils parent.
         */
        $assignedEstablishments = $user->assignedEstablishments()
            ->with('schoolType')
            ->get();

        $parentEstablishmentIds = $parentProfiles
            ->pluck('establishment_id')
            ->filter()
            ->unique()
            ->values();

        $accessibleEstablishmentIds = $assignedEstablishments
            ->pluck('id')
            ->merge($parentEstablishmentIds)
            ->filter()
            ->unique()
            ->values();

        if ($accessibleEstablishmentIds->isEmpty()) {
            return view('parent.dashboard', [
                'user' => $user,
                'parentProfiles' => $parentProfiles,
                'establishments' => collect(),
                'currentEstablishment' => null,
                'schoolYears' => collect(),
                'currentSchoolYear' => null,
                'students' => collect(),
                'inscriptions' => collect(),
                'notes' => collect(),
                'paiements' => collect(),
                'stats' => [
                    'students' => 0,
                    'notes' => 0,
                    'paiements' => 0,
                    'total_paiements' => 0,
                ],
                'noEstablishment' => true,
                'noChildren' => true,
            ]);
        }

        /*
         * Établissements réellement accessibles.
         */
        $establishments = Establishment::query()
            ->whereIn('id', $accessibleEstablishmentIds)
            ->with('schoolType')
            ->orderBy('nom')
            ->get();

        /*
         * Établissement courant.
         */
        $sessionEstablishmentId = session('establishment_id');

        $currentEstablishment = $establishments->firstWhere(
            'id',
            (int) $sessionEstablishmentId
        );

        if (!$currentEstablishment) {
            /*
             * Priorité à l'établissement du profil parent.
             */
            $profileEstablishmentId = $parentProfiles
                ->pluck('establishment_id')
                ->filter()
                ->first();

            $currentEstablishment = $establishments->firstWhere(
                'id',
                (int) $profileEstablishmentId
            );

            if (!$currentEstablishment) {
                $currentEstablishment = $establishments->first();
            }

            if ($currentEstablishment) {
                session([
                    'establishment_id' => $currentEstablishment->id,
                    'establishment_name' => $currentEstablishment->nom,
                ]);
            }
        }

        if (!$currentEstablishment) {
            abort(403, 'Établissement introuvable.');
        }

        /*
         * Années scolaires de l'établissement courant.
         */
        $schoolYears = SchoolYear::query()
            ->where('establishment_id', $currentEstablishment->id)
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $currentSchoolYear = null;

        $sessionSchoolYearId = session('school_year_id');

        if ($sessionSchoolYearId) {
            $currentSchoolYear = $schoolYears->firstWhere(
                'id',
                (int) $sessionSchoolYearId
            );
        }

        if (!$currentSchoolYear) {
            $currentSchoolYear = $schoolYears->firstWhere(
                'is_active',
                true
            ) ?? $schoolYears->first();

            if ($currentSchoolYear) {
                session([
                    'school_year_id' => $currentSchoolYear->id,
                    'school_year_name' => $currentSchoolYear->name,
                ]);
            }
        }

        /*
         * ============================================================
         * ENFANTS DU PARENT
         * ============================================================
         *
         * IMPORTANT :
         * On ne récupère PLUS les élèves de l'établissement.
         *
         * On part exclusivement de la relation parent_student.
         */
        $parentStudentIds = collect();

        foreach ($parentProfiles as $parentProfile) {
            /*
             * Un profil parent ne donne accès qu'aux enfants liés
             * à CE profil.
             */
            if (
                $parentProfile->establishment_id === null ||
                (int) $parentProfile->establishment_id === (int) $currentEstablishment->id
            ) {
                $parentStudentIds = $parentStudentIds
                    ->merge($parentProfile->students->pluck('id'));
            }
        }

        $parentStudentIds = $parentStudentIds
            ->filter()
            ->unique()
            ->values();

        /*
         * Élèves du parent uniquement.
         *
         * On vérifie également qu'ils appartiennent bien à
         * l'établissement courant.
         */
        $students = collect();

        if ($parentStudentIds->isNotEmpty()) {
            $students = Student::query()
                ->whereIn('id', $parentStudentIds)
                ->where('establishment_id', $currentEstablishment->id)
                ->when(
                    $currentSchoolYear,
                    function ($query) use ($currentEstablishment, $currentSchoolYear) {
                        $query->whereHas('inscriptions', function ($inscriptionQuery) use (
                            $currentEstablishment,
                            $currentSchoolYear
                        ) {
                            $inscriptionQuery
                                ->where('establishment_id', $currentEstablishment->id)
                                ->where('school_year_id', $currentSchoolYear->id);
                        });
                    }
                )
                ->with([
                    'establishment',
                    'inscriptions' => function ($query) use (
                        $currentEstablishment,
                        $currentSchoolYear
                    ) {
                        $query
                            ->where('establishment_id', $currentEstablishment->id)
                            ->when(
                                $currentSchoolYear,
                                fn ($q) => $q->where(
                                    'school_year_id',
                                    $currentSchoolYear->id
                                )
                            )
                            ->with(['classRoom', 'schoolYear'])
                            ->orderByDesc('id');
                    },
                ])
                ->orderBy('nom')
                ->orderBy('prenom')
                ->get();
        }

        /*
         * IDs réellement visibles dans l'année scolaire courante.
         */
        $studentIds = $students
            ->pluck('id')
            ->filter()
            ->unique()
            ->values();

        /*
         * Inscriptions des enfants uniquement.
         */
        $inscriptions = collect();

        if ($studentIds->isNotEmpty() && $currentSchoolYear) {
            $inscriptions = Inscription::query()
                ->where('establishment_id', $currentEstablishment->id)
                ->where('school_year_id', $currentSchoolYear->id)
                ->whereIn('student_id', $studentIds)
                ->with([
                    'student',
                    'classRoom',
                    'schoolYear',
                ])
                ->orderByDesc('date_inscription')
                ->orderByDesc('id')
                ->get();
        }

        /*
         * Notes des enfants uniquement.
         */
        $notes = collect();

        if ($studentIds->isNotEmpty() && $currentSchoolYear) {
            $notes = Note::query()
                ->where('establishment_id', $currentEstablishment->id)
                ->where('school_year_id', $currentSchoolYear->id)
                ->whereIn('student_id', $studentIds)
                ->with([
                    'student',
                    'subject',
                    'classRoom',
                    'teacher',
                ])
                ->orderByDesc('id')
                ->get();
        }

        /*
         * Paiements des enfants uniquement.
         */
        $paiements = collect();

        if ($studentIds->isNotEmpty() && $currentSchoolYear) {
            $paiements = Paiement::query()
                ->where('establishment_id', $currentEstablishment->id)
                ->where('school_year_id', $currentSchoolYear->id)
                ->whereIn('student_id', $studentIds)
                ->with([
                    'student',
                    'schoolYear',
                ])
                ->orderByDesc('date_paiement')
                ->orderByDesc('id')
                ->get();
        }

        $stats = [
            'students' => $students->count(),
            'notes' => $notes->count(),
            'paiements' => $paiements->count(),
            'total_paiements' => (float) $paiements->sum('montant'),
        ];

        return view('parent.dashboard', [
            'user' => $user,
            'parentProfiles' => $parentProfiles,
            'establishments' => $establishments,
            'currentEstablishment' => $currentEstablishment,
            'schoolYears' => $schoolYears,
            'currentSchoolYear' => $currentSchoolYear,
            'students' => $students,
            'inscriptions' => $inscriptions,
            'notes' => $notes,
            'paiements' => $paiements,
            'stats' => $stats,
            'noEstablishment' => false,
            'noChildren' => $students->isEmpty(),
        ]);
    }

    /**
     * Consultation des inscriptions des enfants du parent.
     */
    public function inscriptions(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'parent') {
            abort(403, 'Accès réservé aux parents.');
        }

        $context = $this->getParentContext($user);

        if (!$context['currentEstablishment']) {
            abort(403, 'Établissement introuvable.');
        }

        $currentEstablishment = $context['currentEstablishment'];
        $currentSchoolYear = $context['currentSchoolYear'];

        /*
         * Récupération EXCLUSIVE des enfants liés au parent.
         */
        $studentIds = $this->getParentStudentIds(
            $context['parentProfiles'],
            $currentEstablishment
        );

        $inscriptions = collect();

        if ($studentIds->isNotEmpty() && $currentSchoolYear) {
            $inscriptions = Inscription::query()
                ->where('establishment_id', $currentEstablishment->id)
                ->where('school_year_id', $currentSchoolYear->id)
                ->whereIn('student_id', $studentIds)
                ->with([
                    'student',
                    'classRoom',
                    'schoolYear',
                ])
                ->orderByDesc('date_inscription')
                ->orderByDesc('id')
                ->get();
        }

        return view('parent.inscriptions', [
            'user' => $user,
            'establishments' => $context['establishments'],
            'currentEstablishment' => $currentEstablishment,
            'schoolYears' => $context['schoolYears'],
            'currentSchoolYear' => $currentSchoolYear,
            'inscriptions' => $inscriptions,
        ]);
    }

    /**
     * Consultation des paiements des enfants du parent.
     */
    public function paiements(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'parent') {
            abort(403, 'Accès réservé aux parents.');
        }

        $context = $this->getParentContext($user);

        if (!$context['currentEstablishment']) {
            abort(403, 'Établissement introuvable.');
        }

        $currentEstablishment = $context['currentEstablishment'];
        $currentSchoolYear = $context['currentSchoolYear'];

        /*
         * Récupération EXCLUSIVE des enfants liés au parent.
         */
        $studentIds = $this->getParentStudentIds(
            $context['parentProfiles'],
            $currentEstablishment
        );

        $paiements = collect();

        if ($studentIds->isNotEmpty() && $currentSchoolYear) {
            $paiements = Paiement::query()
                ->where('establishment_id', $currentEstablishment->id)
                ->where('school_year_id', $currentSchoolYear->id)
                ->whereIn('student_id', $studentIds)
                ->with([
                    'student',
                    'schoolYear',
                ])
                ->orderByDesc('date_paiement')
                ->orderByDesc('id')
                ->get();
        }

        return view('parent.paiements', [
            'user' => $user,
            'establishments' => $context['establishments'],
            'currentEstablishment' => $currentEstablishment,
            'schoolYears' => $context['schoolYears'],
            'currentSchoolYear' => $currentSchoolYear,
            'paiements' => $paiements,
        ]);
    }

    /**
     * Construit le contexte établissement + année scolaire du parent.
     */
    private function getParentContext($user): array
    {
        $parentProfiles = ParentModel::query()
            ->where('user_id', $user->id)
            ->with([
                'establishment.schoolType',
                'students',
            ])
            ->get();

        $assignedEstablishments = $user->assignedEstablishments()
            ->with('schoolType')
            ->get();

        $accessibleIds = $assignedEstablishments
            ->pluck('id')
            ->merge($parentProfiles->pluck('establishment_id'))
            ->filter()
            ->unique()
            ->values();

        $establishments = Establishment::query()
            ->whereIn('id', $accessibleIds)
            ->with('schoolType')
            ->orderBy('nom')
            ->get();

        $currentEstablishment = $establishments->firstWhere(
            'id',
            (int) session('establishment_id')
        );

        if (!$currentEstablishment) {
            $profileEstablishmentId = $parentProfiles
                ->pluck('establishment_id')
                ->filter()
                ->first();

            $currentEstablishment = $establishments->firstWhere(
                'id',
                (int) $profileEstablishmentId
            ) ?? $establishments->first();

            if ($currentEstablishment) {
                session([
                    'establishment_id' => $currentEstablishment->id,
                    'establishment_name' => $currentEstablishment->nom,
                ]);
            }
        }

        $schoolYears = collect();
        $currentSchoolYear = null;

        if ($currentEstablishment) {
            $schoolYears = SchoolYear::query()
                ->where('establishment_id', $currentEstablishment->id)
                ->orderByDesc('is_active')
                ->orderByDesc('start_date')
                ->orderByDesc('id')
                ->get();

            $currentSchoolYear = $schoolYears->firstWhere(
                'id',
                (int) session('school_year_id')
            );

            if (!$currentSchoolYear) {
                $currentSchoolYear = $schoolYears->firstWhere(
                    'is_active',
                    true
                ) ?? $schoolYears->first();

                if ($currentSchoolYear) {
                    session([
                        'school_year_id' => $currentSchoolYear->id,
                        'school_year_name' => $currentSchoolYear->name,
                    ]);
                }
            }
        }

        return [
            'parentProfiles' => $parentProfiles,
            'establishments' => $establishments,
            'currentEstablishment' => $currentEstablishment,
            'schoolYears' => $schoolYears,
            'currentSchoolYear' => $currentSchoolYear,
        ];
    }

    /**
     * Retourne uniquement les IDs des enfants du parent
     * pour l'établissement courant.
     */
    private function getParentStudentIds($parentProfiles, $currentEstablishment)
    {
        $studentIds = collect();

        foreach ($parentProfiles as $parentProfile) {
            if (
                $parentProfile->establishment_id === null ||
                (int) $parentProfile->establishment_id === (int) $currentEstablishment->id
            ) {
                $studentIds = $studentIds->merge(
                    $parentProfile->students->pluck('id')
                );
            }
        }

        return $studentIds
            ->filter()
            ->unique()
            ->values();
    }
}
