<?php

namespace App\Http\Controllers;

use App\Models\Abonnement;
use App\Models\AbonnementPaiement;
use App\Models\Establishment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                && Carbon::parse($expirationActuelle)->isFuture()
            ) {
                $base = Carbon::parse($expirationActuelle);
            } else {
                $base = $now->copy();
            }

            $nouvelleExpiration = $base
                ->copy()
                ->addMonthNoOverflow()
                ->toDateString();

            if (!$abonnement->date_debut || !$expirationActuelle
                || Carbon::parse($expirationActuelle)->isPast()) {
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

        return redirect()
            ->back()
            ->with(
                'success',
                'Paiement validé. L’abonnement a été activé ou renouvelé.'
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

        return redirect()
            ->back()
            ->with(
                'success',
                'Le paiement a été refusé.'
            );
    }
}
