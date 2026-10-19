<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ClassRoom;
use App\Models\EcolageTarif;
use App\Models\Establishment;
use App\Models\Inscription;
use App\Models\Paiement;
use App\Models\SchoolYear;
use App\Notifications\NotificationGenerale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuiviEcolageController extends Controller
{
    /**
     * Mois disponibles pour le suivi.
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
     * Établissement actuellement sélectionné.
     */
    private function currentEstablishment(): Establishment
    {
        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            abort(403, 'Aucun établissement n’est sélectionné.');
        }

        $user = auth()->user();

        /*
         * L'administrateur peut accéder aux établissements
         * auxquels il a accès via la logique existante.
         */
        if ($user->role === 'admin') {
            $establishment = Establishment::find($establishmentId);
        } else {
            $establishment = $user
                ->assignedEstablishments()
                ->where('establishments.id', $establishmentId)
                ->first();
        }

        if (!$establishment) {
            abort(403, 'Vous n’avez pas accès à cet établissement.');
        }

        return $establishment;
    }

    /**
     * Année scolaire sélectionnée.
     */
    private function currentSchoolYear(
        Establishment $establishment,
        ?int $schoolYearId = null
    ): SchoolYear {
        $id = $schoolYearId ?: session('school_year_id');

        /*
         * Si aucune année n'est en session, on prend l'année active
         * de l'établissement.
         */
        if (!$id) {
            $id = SchoolYear::where('establishment_id', $establishment->id)
                ->where('is_active', true)
                ->orderByDesc('id')
                ->value('id');
        }

        if (!$id) {
            abort(404, 'Aucune année scolaire disponible.');
        }

        $schoolYear = SchoolYear::where('id', $id)
            ->where('establishment_id', $establishment->id)
            ->first();

        if (!$schoolYear) {
            abort(403, 'Cette année scolaire n’appartient pas à cet établissement.');
        }

        return $schoolYear;
    }

    /**
     * Années scolaires disponibles.
     */
    private function schoolYears(Establishment $establishment)
    {
        return SchoolYear::where('establishment_id', $establishment->id)
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Tableau principal du suivi écolage.
     *
     * Chaque ligne représente un élève inscrit pour l'année scolaire.
     */
    public function index(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->currentSchoolYear(
            $establishment,
            $request->filled('school_year_id')
                ? (int) $request->school_year_id
                : null
        );

        /*
         * Mois sélectionné.
         *
         * Par défaut :
         * - mois courant si l'année sélectionnée correspond à l'année courante ;
         * - sinon janvier.
         */
        $defaultMonth = now()->month;
        $defaultYear = now()->year;

        $month = (int) $request->input('mois', $defaultMonth);
        $year = (int) $request->input('annee', $defaultYear);

        if (!array_key_exists($month, self::MOIS)) {
            $month = $defaultMonth;
        }

        if ($year < 2000 || $year > 2100) {
            $year = $defaultYear;
        }

        $classRoomId = $request->filled('class_room_id')
            ? (int) $request->class_room_id
            : null;

        $statusFilter = $request->input('statut');

        $search = trim((string) $request->input('search'));

        /*
         * Classes de l'établissement et de l'année scolaire.
         */
        $classes = ClassRoom::where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->orderBy('nom_classe')
            ->get();

        /*
         * Inscriptions de l'année scolaire.
         */
        $inscriptionsQuery = Inscription::query()
            ->with([
                'student',
                'classRoom',
            ])
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id);

        if ($classRoomId) {
            $inscriptionsQuery->where('class_room_id', $classRoomId);
        }

        /*
         * Recherche par nom, prénom ou matricule.
         */
        if ($search !== '') {
            $inscriptionsQuery->whereHas('student', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', '%' . $search . '%')
                        ->orWhere('prenom', 'like', '%' . $search . '%')
                        ->orWhere('matricule', 'like', '%' . $search . '%');
                });
            });
        }

        $inscriptions = $inscriptionsQuery
            ->orderBy('id')
            ->get();

        /*
         * Tous les tarifs de l'année sélectionnée.
         */
        $tarifs = EcolageTarif::where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->get()
            ->keyBy('class_room_id');

        /*
         * Paiements du mois sélectionné.
         *
         * On agrège par étudiant pour gérer correctement les paiements
         * partiels effectués en plusieurs fois.
         */
        $studentIds = $inscriptions
            ->pluck('student_id')
            ->filter()
            ->unique()
            ->values();

        $paymentsByStudent = collect();

        if ($studentIds->isNotEmpty()) {
            $paymentsByStudent = Paiement::query()
                ->where('establishment_id', $establishment->id)
                ->where('school_year_id', $schoolYear->id)
                ->whereIn('student_id', $studentIds)
                ->where('type_paiement', 'Écolage mensuel')
                ->where('mois', $month)
                ->where('annee', $year)
                ->select(
                    'student_id',
                    DB::raw('SUM(montant) as total_paye')
                )
                ->groupBy('student_id')
                ->pluck('total_paye', 'student_id');
        }

        /*
         * Construction du tableau final.
         */
        $rows = collect();

        foreach ($inscriptions as $inscription) {
            $student = $inscription->student;
            $classRoom = $inscription->classRoom;

            if (!$student || !$classRoom) {
                continue;
            }

            $tarif = $tarifs->get($classRoom->id);

            $expected = $tarif
                ? (float) $tarif->montant_mensuel
                : null;

            $paid = (float) (
                $paymentsByStudent->get($student->id, 0)
            );

            if ($expected === null) {
                $remaining = null;
                $status = 'tarif_non_defini';
            } elseif ($paid <= 0) {
                $remaining = $expected;
                $status = 'impaye';
            } elseif ($paid < $expected) {
                $remaining = max(0, $expected - $paid);
                $status = 'partiel';
            } else {
                $remaining = 0;
                $status = 'paye';
            }

            $rows->push([
                'inscription' => $inscription,
                'student' => $student,
                'class_room' => $classRoom,
                'tarif' => $tarif,
                'montant_attendu' => $expected,
                'montant_paye' => $paid,
                'reste' => $remaining,
                'statut' => $status,
            ]);
        }

        /*
         * Statistiques globales pour les filtres de période,
         * de classe et de recherche.
         *
         * Elles sont calculées avant le filtre de statut afin
         * de conserver des indicateurs cohérents.
         */
        $allRows = $rows;

        $totalExpected = $allRows
            ->sum(function ($row) {
                return $row['montant_attendu'] ?? 0;
            });

        $totalPaid = $allRows->sum('montant_paye');

        $totalRemaining = $allRows
            ->sum(function ($row) {
                return $row['reste'] ?? 0;
            });

        $countPaid = $allRows
            ->where('statut', 'paye')
            ->count();

        $countPartial = $allRows
            ->where('statut', 'partiel')
            ->count();

        $countUnpaid = $allRows
            ->where('statut', 'impaye')
            ->count();

        $countWithoutTariff = $allRows
            ->where('statut', 'tarif_non_defini')
            ->count();

        $collectionRate = $totalExpected > 0
            ? round(($totalPaid / $totalExpected) * 100, 2)
            : 0;

        $stats = [
            'total_expected' => $totalExpected,
            'total_paid' => $totalPaid,
            'total_remaining' => $totalRemaining,
            'count_paid' => $countPaid,
            'count_partial' => $countPartial,
            'count_unpaid' => $countUnpaid,
            'count_without_tariff' => $countWithoutTariff,
            'collection_rate' => $collectionRate,
            'students' => $allRows->count(),
        ];

        /*
         * Le filtre de statut ne s'applique qu'au tableau.
         */
        if (in_array(
            $statusFilter,
            ['paye', 'partiel', 'impaye', 'tarif_non_defini'],
            true
        )) {
            $rows = $allRows
                ->filter(fn ($row) => $row['statut'] === $statusFilter)
                ->values();
        }

        return view('paiements.index', [
            'establishment' => $establishment,
            'schoolYear' => $schoolYear,
            'schoolYears' => $this->schoolYears($establishment),
            'classes' => $classes,
            'rows' => $rows,
            'stats' => $stats,
            'mois' => self::MOIS,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'selectedClassRoom' => $classRoomId,
            'selectedStatus' => $statusFilter,
            'search' => $search,
        ]);
    }

    /**
     * Affiche le formulaire de saisie d'un paiement d'écolage.
     */
    public function create(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->currentSchoolYear(
            $establishment,
            $request->filled('school_year_id')
                ? (int) $request->school_year_id
                : null
        );

        /*
         * Classes disponibles pour l'année scolaire sélectionnée.
         */
        $classes = ClassRoom::where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->orderBy('nom_classe')
            ->get();

        /*
         * Inscriptions de l'année scolaire.
         */
        $inscriptions = Inscription::with([
                'student',
                'classRoom',
            ])
            ->where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->orderBy('id')
            ->get();

        /*
         * Élèves disponibles dans le formulaire.
         *
         * On utilise les inscriptions plutôt que tous les élèves
         * afin d'éviter de proposer un élève d'une autre année
         * scolaire ou d'un autre établissement.
         */
        $students = $inscriptions
            ->map(function ($inscription) {
                $student = $inscription->student;

                if ($student) {
                    $student->setAttribute(
                        'inscription_class_room_id',
                        $inscription->class_room_id
                    );

                    $student->setAttribute(
                        'inscription_class_room_name',
                        $inscription->classRoom?->nom_classe
                    );
                }

                return $student;
            })
            ->filter()
            ->values();

        /*
         * Anciennes variables conservées pour assurer la compatibilité
         * avec la vue existante.
         *
         * Le nouveau suivi n'autorise qu'un seul type de paiement :
         * Écolage mensuel.
         *
         * Et un seul mode :
         * Espèces.
         */
        $typesPaiement = [
            'Écolage mensuel',
        ];

        $modesPaiement = [
            'Espèces',
        ];

        /*
         * Mois disponibles.
         */
        $moisDisponibles = self::MOIS;

        /*
         * Variables de compatibilité avec l'ancienne vue.
         *
         * Aucun paiement enseignant n'est proposé dans le nouveau
         * formulaire d'écolage.
         */
        $teachers = collect();

        $prefill = null;

        return view('paiements.create', [
            'establishment' => $establishment,
            'schoolYear' => $schoolYear,
            'schoolYears' => $this->schoolYears($establishment),

            'classes' => $classes,
            'inscriptions' => $inscriptions,
            'students' => $students,

            'teachers' => $teachers,

            'typesPaiement' => $typesPaiement,
            'modesPaiement' => $modesPaiement,

            'mois' => self::MOIS,
            'moisDisponibles' => $moisDisponibles,

            'selectedMonth' => (int) $request->input(
                'mois',
                now()->month
            ),

            'selectedYear' => (int) $request->input(
                'annee',
                now()->year
            ),

            'prefill' => $prefill,
        ]);
    }

    /**
     * Enregistre un paiement mensuel d'écolage.
     *
     * Règle :
     * - type = Écolage mensuel
     * - mode = Espèces
     * - aucun justificatif
     * - aucune validation secondaire
     */
    public function store(Request $request)
    {
        $establishment = $this->currentEstablishment();

        $schoolYear = $this->currentSchoolYear(
            $establishment,
            $request->filled('school_year_id')
                ? (int) $request->school_year_id
                : null
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
                'min:0.01',
            ],
            'mois' => [
                'required',
                'integer',
                'between:1,12',
            ],
            'annee' => [
                'required',
                'integer',
                'between:2000,2100',
            ],
            'date_paiement' => [
                'required',
                'date',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        /*
         * L'élève doit être inscrit dans l'établissement et
         * l'année scolaire sélectionnés.
         */
        $inscription = Inscription::with('classRoom')
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('student_id', $validated['student_id'])
            ->first();

        if (!$inscription) {
            return back()
                ->withInput()
                ->withErrors([
                    'student_id' => 'Cet élève n’est pas inscrit dans cet établissement pour cette année scolaire.',
                ]);
        }

        /*
         * Récupération du tarif de la classe.
         */
        $tarif = EcolageTarif::where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->where(
                'class_room_id',
                $inscription->class_room_id
            )
            ->first();

        /*
         * Total déjà payé pour le même mois.
         */
        $alreadyPaid = (float) Paiement::where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $schoolYear->id
            )
            ->where(
                'student_id',
                $validated['student_id']
            )
            ->where('type_paiement', 'Écolage mensuel')
            ->where('mois', $validated['mois'])
            ->where('annee', $validated['annee'])
            ->sum('montant');

        $newAmount = (float) $validated['montant'];

        /*
         * Si un tarif existe, empêcher de dépasser le reste à payer.
         * Sans tarif, enregistrer le montant réellement reçu.
         */
        if ($tarif) {
            $expected = (float) $tarif->montant_mensuel;
            $remainingBeforePayment = max(0, $expected - $alreadyPaid);

            if ($newAmount > $remainingBeforePayment) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'montant' => sprintf(
                            'Le montant dépasse le reste à payer. Reste actuel : %s Ar.',
                            number_format(
                                $remainingBeforePayment,
                                0,
                                ',',
                                ' '
                            )
                        ),
                    ]);
            }
        }

        /*
         * Génération d'un numéro de reçu unique.
         */
        $numeroRecu = 'REC-' .
            now()->format('Y') .
            '-' .
            strtoupper(bin2hex(random_bytes(3)));

        $paiement = new Paiement();

        $paiement->establishment_id = $establishment->id;
        $paiement->school_year_id = $schoolYear->id;
        $paiement->student_id = $validated['student_id'];
        $paiement->teacher_id = null;
        $paiement->montant = $newAmount;
        $paiement->type_paiement = 'Écolage mensuel';
        $paiement->mois = $validated['mois'];
        $paiement->annee = $validated['annee'];
        $paiement->mode_paiement = 'Espèces';
        $paiement->date_paiement = $validated['date_paiement'];
        $paiement->reference = null;
        $paiement->numero_recu = $numeroRecu;
        $paiement->description = $validated['description'] ?? null;

        /*
         * Les anciens champs de vérification restent dans la base,
         * mais ils ne sont volontairement pas utilisés ici.
         */
        $paiement->save();

        // Informer les autres comptables de cet établissement.
        try {
            $etablissementPaiement = Establishment::find(
                $paiement->establishment_id
            );

            if ($etablissementPaiement) {
                $eventKey = 'paiement:' . $paiement->id . ':enregistre';

                $destinataires = $etablissementPaiement->users()
                    ->where('role', 'comptable')
                    ->get();

                foreach ($destinataires as $destinataire) {
                    // Ne pas notifier le compte qui vient d'enregistrer le paiement.
                    if ((int) $destinataire->id === (int) auth()->id()) {
                        continue;
                    }

                    $dejaNotifie = $destinataire->notifications()
                        ->where('data->event_key', $eventKey)
                        ->exists();

                    if (!$dejaNotifie) {
                        $destinataire->notify(new NotificationGenerale([
                            'event_key' => $eventKey,
                            'title' => 'Nouveau paiement d’écolage',
                            'message' => 'Un paiement d’écolage de '
                                . number_format((float) $paiement->montant, 0, ',', ' ')
                                . ' MGA a été enregistré. Reçu : '
                                . ($paiement->numero_recu ?? 'non renseigné') . '.',
                            'url' => route('paiements.index'),
                            'establishment_id' => $etablissementPaiement->id,
                            'establishment_nom' => $etablissementPaiement->nom,
                            'type' => 'paiement',
                        ]));
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning(
                'Notification paiement impossible : ' . $e->getMessage()
            );
        }


        return redirect()
            ->route('paiements.recu', ['paiement' => $paiement->id])
            ->with(
                'success',
                'Paiement d’écolage enregistré avec succès.'
            );
    }

    /**
     * Affiche le détail d'un paiement d'écolage.
     */
    public function show(Paiement $paiement)
    {
        $establishment = $this->currentEstablishment();

        if ($paiement->establishment_id !== $establishment->id) {
            abort(403, 'Accès non autorisé à ce paiement.');
        }

        if ($paiement->type_paiement !== 'Écolage mensuel') {
            abort(404, 'Ce paiement n’est pas un paiement d’écolage mensuel.');
        }

        $paiement->load([
            'student',
            'schoolYear',
            'establishment',
        ]);

        $inscription = Inscription::with('classRoom')
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $paiement->school_year_id)
            ->where('student_id', $paiement->student_id)
            ->first();

        $tarif = null;

        if ($inscription) {
            $tarif = EcolageTarif::where(
                    'establishment_id',
                    $establishment->id
                )
                ->where(
                    'school_year_id',
                    $paiement->school_year_id
                )
                ->where(
                    'class_room_id',
                    $inscription->class_room_id
                )
                ->first();
        }

        $totalPaid = (float) Paiement::where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $paiement->school_year_id
            )
            ->where(
                'student_id',
                $paiement->student_id
            )
            ->where('type_paiement', 'Écolage mensuel')
            ->where('mois', $paiement->mois)
            ->where('annee', $paiement->annee)
            ->sum('montant');

        $expected = $tarif
            ? (float) $tarif->montant_mensuel
            : null;

        $remaining = $expected !== null
            ? max(0, $expected - $totalPaid)
            : null;

        if ($expected === null) {
            $status = 'tarif_non_defini';
        } elseif ($totalPaid <= 0) {
            $status = 'impaye';
        } elseif ($totalPaid < $expected) {
            $status = 'partiel';
        } else {
            $status = 'paye';
        }

        return view('paiements.show', [
            'paiement' => $paiement,
            'inscription' => $inscription,
            'tarif' => $tarif,
            'totalPaid' => $totalPaid,
            'expected' => $expected,
            'remaining' => $remaining,
            'status' => $status,
        ]);
    }

    /**
     * Reçu d'un paiement d'écolage.
     */
    public function receipt(Paiement $paiement)
    {
        $establishment = $this->currentEstablishment();

        if ($paiement->establishment_id !== $establishment->id) {
            abort(403, 'Accès non autorisé à ce reçu.');
        }

        if ($paiement->type_paiement !== 'Écolage mensuel') {
            abort(404, 'Ce paiement n’est pas un paiement d’écolage mensuel.');
        }

        $paiement->load([
            'student',
            'schoolYear',
            'establishment',
        ]);

        $inscription = Inscription::with('classRoom')
            ->where('establishment_id', $establishment->id)
            ->where('school_year_id', $paiement->school_year_id)
            ->where('student_id', $paiement->student_id)
            ->first();

        $tarif = null;

        if ($inscription) {
            $tarif = EcolageTarif::where(
                    'establishment_id',
                    $establishment->id
                )
                ->where(
                    'school_year_id',
                    $paiement->school_year_id
                )
                ->where(
                    'class_room_id',
                    $inscription->class_room_id
                )
                ->first();
        }

        $totalPaid = (float) Paiement::where(
                'establishment_id',
                $establishment->id
            )
            ->where(
                'school_year_id',
                $paiement->school_year_id
            )
            ->where(
                'student_id',
                $paiement->student_id
            )
            ->where('type_paiement', 'Écolage mensuel')
            ->where('mois', $paiement->mois)
            ->where('annee', $paiement->annee)
            ->sum('montant');

        $expected = $tarif
            ? (float) $tarif->montant_mensuel
            : null;

        $remaining = $expected !== null
            ? max(0, $expected - $totalPaid)
            : null;

        if ($expected === null) {
            $status = 'tarif_non_defini';
        } elseif ($totalPaid <= 0) {
            $status = 'impaye';
        } elseif ($totalPaid < $expected) {
            $status = 'partiel';
        } else {
            $status = 'paye';
        }

        $pdf = Pdf::loadView('paiements.recu', [
            'paiement' => $paiement,
            'inscription' => $inscription,
            'tarif' => $tarif,
            'totalPaid' => $totalPaid,
            'expected' => $expected,
            'remaining' => $remaining,
            'status' => $status,
        ])->setPaper('a4', 'portrait');

        $numeroRecu = $paiement->numero_recu
            ?: 'PAI-' . str_pad(
                (string) $paiement->id,
                6,
                '0',
                STR_PAD_LEFT
            );

        return $pdf->download('recu-' . $numeroRecu . '.pdf');
    }
}
