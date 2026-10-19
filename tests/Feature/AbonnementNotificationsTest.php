<?php

namespace Tests\Feature;

use App\Console\Commands\VerifierAbonnements;
use App\Models\User;
use App\Notifications\AbonnementExpirationNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AbonnementNotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function preparerAbonnement(
        ?string $expiration,
        string $suffix = 'test'
    ): array {
        $admin = User::factory()->create([
            'name' => 'Admin Test',
            'email' => "admin-{$suffix}@example.test",
            'role' => 'admin',
        ]);

        $superAdmin = User::factory()->create([
            'name' => 'Super Admin Test',
            'email' => "super-{$suffix}@example.test",
            'role' => 'super_admin',
        ]);

        $schoolTypeId = DB::table('school_types')->insertGetId([
            'name' => 'Test ' . uniqid(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $establishmentId = DB::table('establishments')->insertGetId([
            'user_id' => $admin->id,
            'school_type_id' => $schoolTypeId,
            'nom' => 'Etablissement Notification Test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $abonnementId = DB::table('abonnements')->insertGetId([
            'establishment_id' => $establishmentId,
            'date_debut' => null,
            'date_expiration' => $expiration,
            'montant_mensuel' => 10000,
            'devise' => 'MGA',
            'statut' => 'inactif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            $admin,
            $superAdmin,
            $abonnementId,
        ];
    }

    public function test_notifications_are_sent_for_the_six_expected_dates(): void
    {
        $evenements = [
            7 => 'avant_7_jours',
            3 => 'avant_3_jours',
            0 => 'expiration',
            -7 => 'apres_7_jours',
            -14 => 'apres_14_jours',
            -15 => 'apres_15_jours',
           -16 => 'apres_16_jours',
        ];

        foreach ($evenements as $difference => $evenementAttendu) {
            Notification::fake();

            $today = Carbon::parse('2026-10-15')->startOfDay();
            Carbon::setTestNow($today);

            $expiration = $today->copy()
                ->addDays($difference)
                ->toDateString();

            [$admin, $superAdmin] = $this->preparerAbonnement(
                $expiration,
                'jour-' . str_replace('-', 'm', (string) $difference)
            );

            $this->artisan('app:verifier-abonnements')
                ->assertExitCode(0);

            Notification::assertSentTo(
                $admin,
                AbonnementExpirationNotification::class,
                function ($notification) use ($evenementAttendu, $difference) {
                    return $notification->evenement === $evenementAttendu
                        && $notification->jours === abs($difference);
                }
            );

            Notification::assertSentTo(
                $superAdmin,
                AbonnementExpirationNotification::class,
                function ($notification) use ($evenementAttendu, $difference) {
                    return $notification->evenement === $evenementAttendu
                        && $notification->jours === abs($difference);
                }
            );
        }
    }

    public function test_same_notification_is_not_sent_twice(): void
    {
        Notification::fake();

        Carbon::setTestNow('2026-10-15 08:00:00');

        [$admin, $superAdmin] = $this->preparerAbonnement(
            '2026-10-22',
            'duplicate'
        );

        $this->artisan('app:verifier-abonnements')->assertExitCode(0);

        Notification::assertSentTo(
            $admin,
            AbonnementExpirationNotification::class,
            1
        );

        Notification::assertSentTo(
            $superAdmin,
            AbonnementExpirationNotification::class,
            1
        );
    }

    public function test_subscription_without_expiration_sends_no_notification(): void
    {
        Notification::fake();

        Carbon::setTestNow('2026-10-15 08:00:00');

        [$admin, $superAdmin] = $this->preparerAbonnement(
            null,
            'sans-expiration'
        );

        $this->artisan('app:verifier-abonnements')->assertExitCode(0);

        Notification::assertNothingSent();
    }
}
