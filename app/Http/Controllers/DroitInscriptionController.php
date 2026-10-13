<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Inscription;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DroitInscriptionController extends Controller
{
    /**
     * Vérifie l'établissement sélectionné et les droits d'accès.
     */
    private function currentEstablishment(): Establishment
    {
        $id = (int) session('establishment_id', 0);

        abort_if(
            $id <= 0,
            403,
            'Aucun établissement valide n’est sélectionné.'
        );

        $user = auth()->user();

        abort_unless(
            $user !== null,
            403,
            'Authentification requise.'
        );

        if ($user->role === 'admin') {
            $establishment = Establishment::query()
                ->whereKey($id)
                ->first();
        } else {
            $establishment = $user->assignedEstablishments()
                ->where('establishments.id', $id)
                ->first();
        }

        abort_unless(
            $establishment !== null,
            403,
            'Vous n’avez pas accès à cet établissement.'
        );

        return $establishment;
    }

    /**
     * Vérifie que l'année scolaire appartient à l'établissement.
     */
    private function selectedSchoolYear(
        Request $request,
        Establishment $establishment
    ): SchoolYear {
        $yearId = $request->input('school_year_id')
            ?: session('school_year_id');

        $yearsQuery = SchoolYear::query()
            ->where('establishment_id', $establishment->id);

        if ($yearId) {
            $schoolYear = (clone $yearsQuery)
                ->whereKey((int) $yearId)
                ->first();

            abort_unless(
                $schoolYear !== null,
                403,
                'Cette année scolaire n’appartient pas à l’établissement sélectionné.'
            );

            return $schoolYear;
        }

        $schoolYear = (clone $yearsQuery)
            ->where('is_active', true)
            ->first();

        if (!$schoolYear) {
            $schoolYear = (clone $yearsQuery)
                ->orderByDesc('start_date')
                ->first();
        }

        abort_unless(
            $schoolYear !== null,
            403,
            'Aucune année scolaire n’est configurée pour cet établissement.'
        );

        return $schoolYear;
    }

    /**
     * Élèves inscrits pendant l'année scolaire sélectionnée
     * et n'ayant pas encore de droit d'inscription enregistré.
     */
    private function eligibleInscriptions(
        Establishment $establishment,
        SchoolYear $schoolYear
    ) {
        $paidStudentIds = Paiement::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('type_paiement', "Droit d'inscription")
            ->whereNotNull('student_id')
            ->pluck('student_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return Inscription::query()
            ->with(['student', 'classRoom'])
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->whereHas('student', function ($query) use ($establishment) {
                $query->where(
                    'establishment_id',
                    $establishment->id
                );
            })
            ->orderBy('student_id')
            ->get()
            ->filter(function ($inscription) use ($paidStudentIds) {
                return $inscription->student !== null
                    && !in_array(
                        (int) $inscription->student_id,
                        $paidStudentIds,
                        true
                    );
            })
            ->unique('student_id')
            ->values();
    }

    /**
     * Liste des droits d'inscription enregistrés.
     */
    public function index(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->selectedSchoolYear(
            $request,
            $establishment
        );

        $schoolYears = SchoolYear::query()
            ->where('establishment_id', $establishment->id)
            ->orderByDesc('start_date')
            ->get();

        $paymentsQuery = Paiement::query()
            ->with(['student', 'schoolYear'])
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('type_paiement', "Droit d'inscription");

        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));

            $paymentsQuery->whereHas('student', function ($query) use ($search) {
                $query->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $totalCollected = (clone $paymentsQuery)->sum('montant');
        $paymentCount = (clone $paymentsQuery)->count();

        $paiements = $paymentsQuery
            ->orderByDesc('date_paiement')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('paiements.droits.index', compact(
            'establishment',
            'schoolYear',
            'schoolYears',
            'paiements',
            'totalCollected',
            'paymentCount'
        ));
    }

    /**
     * Formulaire de paiement du droit d'inscription.
     */
    public function create(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->selectedSchoolYear(
            $request,
            $establishment
        );

        $inscriptions = $this->eligibleInscriptions(
            $establishment,
            $schoolYear
        );

        return view('paiements.droits.create', compact(
            'establishment',
            'schoolYear',
            'inscriptions'
        ));
    }

    /**
     * Enregistre le droit d'inscription en espèces.
     */
    public function store(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->selectedSchoolYear(
            $request,
            $establishment
        );

        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],
            'montant' => [
                'required',
                'numeric',
                'min:1',
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
                'max:5000',
            ],
        ]);

        $student = Student::query()
            ->whereKey($validated['student_id'])
            ->where('establishment_id', $establishment->id)
            ->first();

        if (!$student) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' => 'Cet élève n’appartient pas à l’établissement sélectionné.',
                ]);
        }

        $isEnrolled = Inscription::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('student_id', $student->id)
            ->exists();

        if (!$isEnrolled) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' => 'Cet élève n’est pas inscrit pour l’année scolaire sélectionnée.',
                ]);
        }

        $existingPayment = Paiement::query()
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('student_id', $student->id)
            ->where('type_paiement', "Droit d'inscription")
            ->first();

        if ($existingPayment) {
            return redirect()
                ->route('paiements.droits.show', [
                    'paiement' => $existingPayment->id,
                    'school_year_id' => $schoolYear->id,
                ])
                ->with(
                    'warning',
                    'Un droit d’inscription existe déjà pour cet élève et cette année scolaire.'
                );
        }

        $paiement = DB::transaction(function () use (
            $validated,
            $student,
            $establishment,
            $schoolYear
        ) {
            do {
                $numeroRecu = 'REC-INS-'
                    . now()->format('Y')
                    . '-'
                    . Str::upper(Str::random(8));
            } while (
                Paiement::query()
                    ->where('numero_recu', $numeroRecu)
                    ->exists()
            );

            return Paiement::create([
                'establishment_id' => $establishment->id,
                'school_year_id' => $schoolYear->id,
                'student_id' => $student->id,
                'teacher_id' => null,
                'montant' => $validated['montant'],
                'type_paiement' => "Droit d'inscription",
                'mois' => null,
                'annee' => null,
                'mode_paiement' => 'Espèces',
                'date_paiement' => $validated['date_paiement'],
                'reference' => $validated['reference'] ?? null,
                'numero_recu' => $numeroRecu,
                'description' => $validated['description'] ?? null,
            ]);
        });

        return redirect()
            ->route('paiements.droits.show', [
                'paiement' => $paiement->id,
                'school_year_id' => $schoolYear->id,
            ])
            ->with(
                'success',
                'Droit d’inscription enregistré avec succès. Reçu : '
                    . $paiement->numero_recu
            );
    }

    /**
     * Détail d'un droit d'inscription.
     */
    public function show(Request $request, int $paiement)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->selectedSchoolYear(
            $request,
            $establishment
        );

        $paiement = Paiement::query()
            ->with(['student', 'establishment', 'schoolYear'])
            ->whereKey($paiement)
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('type_paiement', "Droit d'inscription")
            ->firstOrFail();

        return view('paiements.droits.show', compact(
            'establishment',
            'schoolYear',
            'paiement'
        ));
    }

    /**
     * Génère le reçu PDF d'un droit d'inscription.
     */
    public function receipt(Request $request, int $paiement)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->selectedSchoolYear(
            $request,
            $establishment
        );

        $paiement = Paiement::query()
            ->with(['student', 'establishment', 'schoolYear'])
            ->whereKey($paiement)
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('type_paiement', "Droit d'inscription")
            ->firstOrFail();

        $pdf = app('dompdf.wrapper');

        $pdf->loadView('paiements.recu', compact('paiement'));

        $filename = preg_replace(
            '/[^A-Za-z0-9_-]/',
            '-',
            $paiement->numero_recu ?: 'paiement-' . $paiement->id
        );

        return $pdf->download('recu-' . $filename . '.pdf');
    }
}
