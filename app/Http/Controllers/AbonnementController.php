<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\AbonnementPaiement;
use App\Models\Establishment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AbonnementController extends Controller
{
    /**
     * Retourne exclusivement l'établissement appartenant
     * à l'administrateur actuellement connecté.
     */
    private function getEtablissementAdmin(): Establishment
    {
        abort_unless(
            auth()->check()
                && auth()->user()->role === 'admin',
            403,
            'Accès réservé à l’administrateur.'
        );

        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            abort(
                redirect()->route('establishments.choose')
                    ->with(
                        'error',
                        'Veuillez sélectionner votre établissement.'
                    )
            );
        }

        return Establishment::query()
            ->whereKey($establishmentId)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }

    /**
     * Affiche l'abonnement et l'historique des paiements.
     */
    public function index(): View
    {
        $establishment = $this->getEtablissementAdmin();

        $abonnement = Abonnement::firstOrCreate(
            [
                'establishment_id' => $establishment->id,
            ],
            [
                'statut' => 'inactif',
                'devise' => 'MGA',
            ]
        );

        $paiements = $abonnement->paiements()
            ->latest()
            ->paginate(10);

        $paiementEnAttente = $abonnement->paiements()
            ->where('statut', 'en_attente')
            ->exists();

        return view(
            'abonnements.index',
            compact(
                'establishment',
                'abonnement',
                'paiements',
                'paiementEnAttente'
            )
        );
    }

    /**
     * Soumet une demande de paiement.
     *
     * Le montant provient exclusivement du tarif configuré
     * par le Super Administrateur, jamais du formulaire.
     */
    public function store(Request $request): RedirectResponse
    {
        $establishment = $this->getEtablissementAdmin();

        $abonnement = Abonnement::firstOrCreate(
            [
                'establishment_id' => $establishment->id,
            ],
            [
                'statut' => 'inactif',
                'devise' => 'MGA',
            ]
        );

        if (
            $abonnement->montant_mensuel === null
            || (float) $abonnement->montant_mensuel <= 0
        ) {
            return redirect()
                ->route('admin.abonnement.index')
                ->with(
                    'error',
                    'Le tarif mensuel n’est pas encore défini. '
                    . 'Veuillez contacter le Super Administrateur.'
                );
        }

        if (
            $abonnement->paiements()
                ->where('statut', 'en_attente')
                ->exists()
        ) {
            return redirect()
                ->route('admin.abonnement.index')
                ->with(
                    'error',
                    'Une demande de paiement est déjà en attente '
                    . 'de vérification.'
                );
        }

        $validated = $request->validate([
            'mode_paiement' => [
                'required',
                'string',
                Rule::in([
                    'MVola',
                    'Orange Money',
                    'Airtel Money',
                    'Virement bancaire',
                ]),
            ],
            'reference_transaction' => [
                'required',
                'string',
                'max:150',
            ],
            'preuve_paiement' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ], [
            'mode_paiement.required' =>
                'Veuillez sélectionner le mode de paiement.',
            'mode_paiement.in' =>
                'Le mode de paiement sélectionné est invalide.',
            'reference_transaction.required' =>
                'La référence de transaction est obligatoire.',
            'preuve_paiement.required' =>
                'Veuillez joindre votre justificatif de paiement.',
            'preuve_paiement.mimes' =>
                'Le justificatif doit être une image JPG, PNG ou un PDF.',
            'preuve_paiement.max' =>
                'Le justificatif ne doit pas dépasser 5 Mo.',
        ]);

        /*
         * Le justificatif est conservé sur le disque privé local.
         * Aucun lien public direct vers le fichier n'est créé.
         */
        $cheminPreuve = $request->file('preuve_paiement')
            ->store('abonnements/preuves', 'local');

        try {
            DB::transaction(function () use (
                $abonnement,
                $validated,
                $cheminPreuve
            ) {
                $abonnementVerrouille = Abonnement::query()
                    ->whereKey($abonnement->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $abonnementVerrouille->montant_mensuel === null
                    || (float) $abonnementVerrouille->montant_mensuel <= 0
                ) {
                    abort(
                        422,
                        'Le tarif mensuel n’est pas configuré.'
                    );
                }

                if (
                    $abonnementVerrouille->paiements()
                        ->where('statut', 'en_attente')
                        ->exists()
                ) {
                    abort(
                        422,
                        'Une demande de paiement est déjà en attente.'
                    );
                }

                AbonnementPaiement::create([
                    'abonnement_id' => $abonnementVerrouille->id,
                    'montant' => $abonnementVerrouille->montant_mensuel,
                    'mode_paiement' => $validated['mode_paiement'],
                    'reference_transaction' => trim(
                        $validated['reference_transaction']
                    ),
                    'preuve_paiement' => $cheminPreuve,
                    'statut' => 'en_attente',
                    'date_soumission' => now(),
                ]);
            });
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($cheminPreuve);

            throw $exception;
        }

        /*
         * Informer les Super Administrateurs après l'enregistrement
         * réussi du paiement. Une erreur de notification ne doit pas
         * annuler ni perturber la soumission du paiement.
         */
        try {
            $paiementSoumis = AbonnementPaiement::query()
                ->where('abonnement_id', $abonnement->id)
                ->where('statut', 'en_attente')
                ->latest('id')
                ->first();

            if ($paiementSoumis) {
                $etablissement = $abonnement->establishment;
                $nomEtablissement = $etablissement?->nom
                    ?? 'Établissement #' . $abonnement->establishment_id;

                $destinataires = \App\Models\User::query()
                    ->where('role', 'super_admin')
                    ->get();

                foreach ($destinataires as $destinataire) {
                    $eventKey = 'abonnement-paiement:'
                        . $paiementSoumis->id . ':soumis';

                    $dejaNotifie = $destinataire->notifications()
                        ->where('data->event_key', $eventKey)
                        ->exists();

                    if (!$dejaNotifie) {
                        $destinataire->notify(
                            new \App\Notifications\NotificationGenerale([
                                'event_key' => $eventKey,
                                'title' => 'Nouveau paiement d’abonnement',
                                'message' => $nomEtablissement
                                    . ' a soumis un paiement d’abonnement de '
                                    . number_format(
                                        (float) $paiementSoumis->montant,
                                        0,
                                        ',',
                                        ' '
                                    )
                                    . ' MGA. Vérification nécessaire.',
                                'url' => route('super-admin.abonnements.index'),
                                'establishment_id' => $abonnement->establishment_id,
                                'establishment_nom' => $nomEtablissement,
                                'type' => 'abonnement',
                            ])
                        );
                    }
                }
            }
        } catch (\Throwable $notificationException) {
            \Illuminate\Support\Facades\Log::warning(
                'Notification de soumission d’abonnement impossible.',
                [
                    'abonnement_id' => $abonnement->id,
                    'error' => $notificationException->getMessage(),
                ]
            );
        }

        return redirect()
            ->route('admin.abonnement.index')
            ->with(
                'success',
                'Votre demande de paiement a été envoyée. '
                . 'L’abonnement sera renouvelé après vérification '
                . 'effective du paiement par le Super Administrateur.'
            );
    }
}
