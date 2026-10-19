<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AbonnementValidationPaiementTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function preparerPaiement(
        string $expiration,
        string $statutPaiement = 'en_attente'
    ): array {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin-' . uniqid() . '@example.test',
        ]);

        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'email' => 'super-' . uniqid() . '@example.test',
        ]);

        $schoolTypeId = DB::table('school_types')->insertGetId([
            'name' => 'Test ' . uniqid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $establishmentId = DB::table('establishments')->insertGetId([
            'user_id' => $admin->id,
            'school_type_id' => $schoolTypeId,
            'nom' => 'Etablissement abonnement test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $abonnementId = DB::table('abonnements')->insertGetId([
            'establishment_id' => $establishmentId,
            'date_debut' => '2026-09-01',
            'date_expiration' => $expiration,
            'montant_mensuel' => 10000,
            'devise' => 'MGA',
            'statut' => 'inactif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $paiementId = DB::table('abonnement_paiements')->insertGetId([
            'abonnement_id' => $abonnementId,
            'montant' => 10000,
            'mode_paiement' => 'MVola',
            'reference_transaction' => 'TEST-' . uniqid(),
            'preuve_paiement' => null,
            'statut' => $statutPaiement,
            'date_soumission' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [$admin, $superAdmin, $abonnementId, $paiementId];
    }

    public function test_validation_renouvelle_un_abonnement_encore_valide(): void
    {
        Carbon::setTestNow('2026-10-15 10:00:00');

        [, $superAdmin, $abonnementId, $paiementId] =
            $this->preparerPaiement('2026-10-31');

        $this->actingAs($superAdmin)
            ->post(route(
                'super-admin.abonnements.paiements.valider',
                ['paiement' => $paiementId]
            ))
            ->assertRedirect();

        $this->assertDatabaseHas('abonnements', [
            'id' => $abonnementId,
            'date_expiration' => '2026-11-30 00:00:00',
            'statut' => 'actif',
        ]);

        $this->assertDatabaseHas('abonnement_paiements', [
            'id' => $paiementId,
            'statut' => 'valide',
            'verifie_par' => $superAdmin->id,
            'date_paiement' => '2026-10-15 10:00:00',
        ]);
    }

    public function test_validation_d_un_abonnement_expire_repart_d_aujourd_hui(): void
    {
        Carbon::setTestNow('2026-10-15 10:00:00');

        [, $superAdmin, $abonnementId, $paiementId] =
            $this->preparerPaiement('2026-09-30');

        $this->actingAs($superAdmin)
            ->post(route(
                'super-admin.abonnements.paiements.valider',
                ['paiement' => $paiementId]
            ))
            ->assertRedirect();

        $this->assertDatabaseHas('abonnements', [
            'id' => $abonnementId,
            'date_debut' => '2026-10-15 00:00:00',
            'date_expiration' => '2026-11-15 00:00:00',
            'statut' => 'actif',
        ]);

        $this->assertDatabaseHas('abonnement_paiements', [
            'id' => $paiementId,
            'statut' => 'valide',
            'verifie_par' => $superAdmin->id,
        ]);
    }

    public function test_un_paiement_deja_traite_ne_peut_pas_etre_valide_une_seconde_fois(): void
    {
        Carbon::setTestNow('2026-10-15 10:00:00');

        [, $superAdmin, $abonnementId, $paiementId] =
            $this->preparerPaiement('2026-10-31', 'valide');

        $this->actingAs($superAdmin)
            ->post(route(
                'super-admin.abonnements.paiements.valider',
                ['paiement' => $paiementId]
            ))
            ->assertStatus(422);

        $this->assertDatabaseHas('abonnements', [
            'id' => $abonnementId,
            'date_expiration' => '2026-10-31',
            'statut' => 'inactif',
        ]);
    }

    public function test_un_admin_ne_peut_pas_valider_un_paiement(): void
    {
        Carbon::setTestNow('2026-10-15 10:00:00');

        [$admin, , $abonnementId, $paiementId] =
            $this->preparerPaiement('2026-10-31');

        $this->actingAs($admin)
            ->post(route(
                'super-admin.abonnements.paiements.valider',
                ['paiement' => $paiementId]
            ))
            ->assertForbidden();

        $this->assertDatabaseHas('abonnement_paiements', [
            'id' => $paiementId,
            'statut' => 'en_attente',
        ]);

        $this->assertDatabaseHas('abonnements', [
            'id' => $abonnementId,
            'date_expiration' => '2026-10-31',
            'statut' => 'inactif',
        ]);
    }


    public function test_un_super_admin_peut_rejeter_un_paiement_en_attente(): void
    {
        Carbon::setTestNow('2026-10-15 10:00:00');

        [, $superAdmin, $abonnementId, $paiementId] =
            $this->preparerPaiement('2026-10-31');

        $this->actingAs($superAdmin)
            ->from('/super-admin/abonnements')
            ->post(route(
                'super-admin.abonnements.paiements.rejeter',
                ['paiement' => $paiementId]
            ), [
                'note_verification' => 'Référence de test non confirmée.',
            ])
            ->assertRedirect('/super-admin/abonnements');

        $this->assertDatabaseHas('abonnement_paiements', [
            'id' => $paiementId,
            'statut' => 'rejete',
            'verifie_par' => $superAdmin->id,
            'note_verification' => 'Référence de test non confirmée.',
        ]);

        $this->assertDatabaseHas('abonnements', [
            'id' => $abonnementId,
            'date_expiration' => '2026-10-31',
            'statut' => 'inactif',
        ]);
    }

    public function test_un_admin_ne_peut_pas_rejeter_un_paiement(): void
    {
        Carbon::setTestNow('2026-10-15 10:00:00');

        [$admin, , $abonnementId, $paiementId] =
            $this->preparerPaiement('2026-10-31');

        $this->actingAs($admin)
            ->post(route(
                'super-admin.abonnements.paiements.rejeter',
                ['paiement' => $paiementId]
            ), [
                'note_verification' => 'Test de sécurité.',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('abonnement_paiements', [
            'id' => $paiementId,
            'statut' => 'en_attente',
        ]);

        $this->assertDatabaseHas('abonnements', [
            'id' => $abonnementId,
            'date_expiration' => '2026-10-31',
            'statut' => 'inactif',
        ]);
    }

    public function test_un_paiement_deja_traite_ne_peut_pas_etre_rejete(): void
    {
        Carbon::setTestNow('2026-10-15 10:00:00');

        [, $superAdmin, $abonnementId, $paiementId] =
            $this->preparerPaiement('2026-10-31', 'valide');

        $this->actingAs($superAdmin)
            ->post(route(
                'super-admin.abonnements.paiements.rejeter',
                ['paiement' => $paiementId]
            ), [
                'note_verification' => 'Tentative de rejet répétée.',
            ])
            ->assertStatus(422);

        $this->assertDatabaseHas('abonnement_paiements', [
            'id' => $paiementId,
            'statut' => 'valide',
        ]);

        $this->assertDatabaseHas('abonnements', [
            'id' => $abonnementId,
            'date_expiration' => '2026-10-31',
            'statut' => 'inactif',
        ]);
    }

}
