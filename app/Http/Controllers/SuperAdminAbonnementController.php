<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\AbonnementPaiement;
use App\Models\Establishment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SuperAdminAbonnementController extends Controller
{
    /**
     * Vérifier le rôle Super Admin.
     */
    private function ensureSuperAdmin(): void
    {
        abort_unless(
            auth()->check()
                && auth()->user()->role === 'super_admin',
            403,
            'Accès réservé au Super Administrateur.'
        );
    }

    /**
     * Liste des établissements et de leurs abonnements.
     */
    public function index(): View
    {
        $this->ensureSuperAdmin();

        $establishments = Establishment::query()
            ->with([
                'user',
                'abonnement',
            ])
            ->withCount([
                'abonnement as abonnements_count',
            ])
            ->orderBy('nom')
            ->paginate(20);

        $paiementsEnAttente = AbonnementPaiement::query()
            ->where('statut', 'en_attente')
            ->count();

        return view(
            'super-admin.abonnements.index',
            compact(
                'establishments',
                'paiementsEnAttente'
            )
        );
    }

    /**
     * Consulter l'abonnement d'un établissement.
     */
    public function show(
        Establishment $establishment
    ): View {
        $this->ensureSuperAdmin();

        $abonnement = Abonnement::firstOrCreate(
            [
                'establishment_id' => $establishment->id,
            ],
            [
                'statut' => 'inactif',
                'devise' => 'MGA',
            ]
        );

        $abonnement->load([
            'paiements.verificateur',
        ]);

        $establishment->load('user');

        return view(
            'super-admin.abonnements.show',
            compact(
                'establishment',
                'abonnement'
            )
        );
    }

    /**
     * Configurer ou modifier le tarif mensuel d'un établissement.
     */
    public function configurerTarif(
        Request $request,
        Establishment $establishment
    ): RedirectResponse {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'montant_mensuel' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
                'max:9999999999.99',
            ],
        ], [
            'montant_mensuel.required' =>
                'Veuillez renseigner le tarif mensuel.',
            'montant_mensuel.numeric' =>
                'Le tarif doit être un nombre valide.',
            'montant_mensuel.gt' =>
                'Le tarif mensuel doit être supérieur à zéro.',
            'montant_mensuel.decimal' =>
                'Le tarif peut comporter au maximum deux décimales.',
            'montant_mensuel.max' =>
                'Le tarif mensuel dépasse le montant maximal autorisé.',
        ]);

        $abonnement = Abonnement::firstOrCreate(
            [
                'establishment_id' => $establishment->id,
            ],
            [
                'statut' => 'inactif',
                'devise' => 'MGA',
            ]
        );

        $abonnement->montant_mensuel = $validated['montant_mensuel'];
        $abonnement->save();

        return redirect()
            ->route('super-admin.abonnements.show', $establishment)
            ->with(
                'success',
                'Le tarif mensuel de l’établissement a été enregistré.'
            );
    }

    /**
     * Valider un paiement et activer ou renouveler l'abonnement.
     */
    public function validerPaiement(
        AbonnementPaiement $paiement
    ): RedirectResponse {
        $this->ensureSuperAdmin();

        DB::transaction(function () use ($paiement) {
            $paiement = AbonnementPaiement::query()
                ->whereKey($paiement->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $paiement->statut === 'en_attente',
                422,
                'Ce paiement a déjà été traité.'
            );

            $abonnement = Abonnement::query()
                ->whereKey($paiement->abonnement_id)
                ->lockForUpdate()
                ->firstOrFail();

            $now = now();

            /*
             * Si l'abonnement est encore valide, ajouter un mois
             * à sa date d'expiration actuelle.
             * Sinon, commencer à partir d'aujourd'hui.
             */
            $expirationActuelle = $abonnement->date_expiration;

            if (
                $expirationActuelle
                && Carbon::parse($expirationActuelle)->startOfDay()
                    ->greaterThanOrEqualTo($now->copy()->startOfDay())
            ) {
                $base = Carbon::parse($expirationActuelle);
            } else {
                $base = $now->copy();
            }

            $nouvelleExpiration = $base
                ->copy()
                ->addMonthNoOverflow()
                ->toDateString();

            if (
                !$abonnement->date_debut
                || !$expirationActuelle
                || Carbon::parse($expirationActuelle)->startOfDay()
                    ->lessThan($now->copy()->startOfDay())
            ) {
                $abonnement->date_debut = $now->toDateString();
            }

            $abonnement->date_expiration = $nouvelleExpiration;
            $abonnement->statut = 'actif';

            /*
             * Ne pas imposer de tarif mensuel si aucun tarif
             * n'a encore été configuré.
             */
            $abonnement->save();

            $paiement->statut = 'valide';
            $paiement->verifie_par = auth()->id();
            $paiement->date_verification = $now;
            $paiement->date_paiement = $now;
            $paiement->note_verification = null;
            $paiement->save();
        });

        /*
         * Informer le propriétaire de l'établissement après validation.
         * La mise à jour de l'abonnement est déjà terminée.
         */
        try {
            $paiementValide = AbonnementPaiement::query()
                ->with(['abonnement.establishment.user'])
                ->find($paiement->id);

            $etablissement = $paiementValide?->abonnement?->establishment;
            $administrateur = $etablissement?->user;

            if ($administrateur) {
                $eventKey = 'abonnement-paiement:'
                    . $paiementValide->id . ':valide';

                $dejaNotifie = $administrateur->notifications()
                    ->where('data->event_key', $eventKey)
                    ->exists();

                if (!$dejaNotifie) {
                    $administrateur->notify(
                        new \App\Notifications\NotificationGenerale([
                            'event_key' => $eventKey,
                            'title' => 'Paiement d’abonnement validé',
                            'message' => 'Votre paiement d’abonnement pour '
                                . ($etablissement->nom ?? 'votre établissement')
                                . ' a été validé. Votre abonnement a été activé '
                                . 'ou renouvelé.',
                            'url' => route('admin.abonnement.index'),
                            'establishment_id' => $etablissement->id,
                            'establishment_nom' => $etablissement->nom,
                            'type' => 'abonnement',
                        ])
                    );
                }
            }
        } catch (\Throwable $notificationException) {
            \Illuminate\Support\Facades\Log::warning(
                'Notification de validation d’abonnement impossible.',
                [
                    'paiement_id' => $paiement->id,
                    'error' => $notificationException->getMessage(),
                ]
            );
        }

        return redirect()
            ->back()
            ->with(
                'success',
                'Paiement validé. L’abonnement a été activé ou renouvelé.'
            );
    }


    /**
     * Page de vérification détaillée d'un paiement.
     * Le fichier reste privé et accessible uniquement au Super Admin.
     */
    public function verificationPaiement(
        AbonnementPaiement $paiement
    ): View {
        $this->ensureSuperAdmin();

        $paiement->load('abonnement.establishment');

        $establishment = $paiement->abonnement?->establishment;

        abort_if(
            !$establishment,
            404,
            'Établissement du paiement introuvable.'
        );

        $path = $paiement->preuve_paiement;

        abort_unless(
            is_string($path)
                && str_starts_with($path, 'abonnements/preuves/'),
            404,
            'Justificatif introuvable.'
        );

        $disk = Storage::disk('local');

        abort_unless(
            $disk->exists($path),
            404,
            'Le fichier justificatif est introuvable.'
        );

        $mime = $disk->mimeType($path) ?: '';

        abort_unless(
            in_array($mime, [
                'image/jpeg',
                'image/png',
                'application/pdf',
            ], true),
            404,
            'Format de justificatif non autorisé.'
        );

        return view(
            'super-admin.abonnements.verification-paiement',
            compact('paiement', 'establishment', 'mime')
        );
    }

    /**
     * Télécharger un justificatif privé après vérification du rôle
     * et du type de fichier.
     */
    public function telechargerJustificatif(
        AbonnementPaiement $paiement
    ) {
        $this->ensureSuperAdmin();

        $path = $paiement->preuve_paiement;

        abort_unless(
            is_string($path)
                && str_starts_with($path, 'abonnements/preuves/'),
            404,
            'Justificatif introuvable.'
        );

        $disk = Storage::disk('local');

        abort_unless(
            $disk->exists($path),
            404,
            'Le fichier justificatif est introuvable.'
        );

        $mime = $disk->mimeType($path) ?: '';

        abort_unless(
            in_array($mime, [
                'image/jpeg',
                'image/png',
                'application/pdf',
            ], true),
            404,
            'Format de justificatif non autorisé.'
        );

        $extension = match ($mime) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf',
        };

        return $disk->download(
            $path,
            'justificatif-paiement-' . $paiement->id . '.' . $extension,
            [
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store, max-age=0',
            ]
        );
    }

    /**
     * Afficher un justificatif de paiement depuis le disque privé.
     * Accès réservé au Super Admin.
     */
    public function voirJustificatif(
        AbonnementPaiement $paiement
    ) {
        $this->ensureSuperAdmin();

        $path = $paiement->preuve_paiement;

        abort_unless(
            is_string($path)
                && str_starts_with($path, 'abonnements/preuves/'),
            404,
            'Justificatif introuvable.'
        );

        $disk = Storage::disk('local');

        abort_unless(
            $disk->exists($path),
            404,
            'Le fichier justificatif est introuvable.'
        );

        $mime = $disk->mimeType($path) ?: '';

        abort_unless(
            in_array($mime, [
                'image/jpeg',
                'image/png',
                'application/pdf',
            ], true),
            404,
            'Format de justificatif non autorisé.'
        );

        return $disk->response(
            $path,
            basename($path),
            [
                'Content-Type' => $mime,
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store, max-age=0',
            ],
            'inline'
        );
    }

    /**
     * Refuser un paiement en attente.
     */
    public function rejeterPaiement(
        Request $request,
        AbonnementPaiement $paiement
    ): RedirectResponse {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'note_verification' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'note_verification.required' =>
                'Veuillez indiquer le motif du refus.',
        ]);

        DB::transaction(function () use ($paiement, $validated) {
            $paiement = AbonnementPaiement::query()
                ->whereKey($paiement->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $paiement->statut === 'en_attente',
                422,
                'Ce paiement a déjà été traité.'
            );

            $paiement->statut = 'rejete';
            $paiement->verifie_par = auth()->id();
            $paiement->date_verification = now();
            $paiement->note_verification =
                trim($validated['note_verification']);

            $paiement->save();
        });

        /*
         * Informer l'administrateur de l'établissement du refus.
         * Cette notification intervient après l'enregistrement du refus.
         */
        try {
            $paiementRefuse = AbonnementPaiement::query()
                ->with(['abonnement.establishment.user'])
                ->find($paiement->id);

            $etablissement = $paiementRefuse?->abonnement?->establishment;
            $administrateur = $etablissement?->user;

            if ($administrateur) {
                $eventKey = 'abonnement-paiement:'
                    . $paiementRefuse->id . ':rejete';

                $dejaNotifie = $administrateur->notifications()
                    ->where('data->event_key', $eventKey)
                    ->exists();

                if (!$dejaNotifie) {
                    $motif = trim(
                        (string) $paiementRefuse->note_verification
                    );

                    $administrateur->notify(
                        new \App\Notifications\NotificationGenerale([
                            'event_key' => $eventKey,
                            'title' => 'Paiement d’abonnement refusé',
                            'message' => 'Le paiement de votre abonnement pour '
                                . ($etablissement->nom ?? 'votre établissement')
                                . ' a été refusé. Motif : '
                                . ($motif !== '' ? $motif : 'Non précisé'),
                            'url' => route('admin.abonnement.index'),
                            'establishment_id' => $etablissement->id,
                            'establishment_nom' => $etablissement->nom,
                            'type' => 'abonnement',
                        ])
                    );
                }
            }
        } catch (\Throwable $notificationException) {
            \Illuminate\Support\Facades\Log::warning(
                'Notification de refus d’abonnement impossible.',
                [
                    'paiement_id' => $paiement->id,
                    'error' => $notificationException->getMessage(),
                ]
            );
        }

        return redirect()
            ->back()
            ->with(
                'success',
                'Le paiement a été refusé.'
            );
    }
}
