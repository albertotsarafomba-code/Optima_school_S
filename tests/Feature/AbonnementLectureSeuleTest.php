<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifierAbonnementActif;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AbonnementLectureSeuleTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function preparerEtablissement(
        string $role = 'admin',
        ?string $expiration = null
    ): array {
        $user = User::factory()->create([
            'role' => $role,
        ]);

        $schoolTypeId = DB::table('school_types')->insertGetId([
            'name' => 'Test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $establishmentId = DB::table('establishments')->insertGetId([
            'user_id' => $user->id,
            'school_type_id' => $schoolTypeId,
            'nom' => 'Etablissement test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('abonnements')->insert([
            'establishment_id' => $establishmentId,
            'date_debut' => null,
            'date_expiration' => $expiration,
            'montant_mensuel' => 10000,
            'devise' => 'MGA',
            'statut' => 'inactif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [$user, $establishmentId];
    }

    private function executerMiddleware(
        User $user,
        string $method = 'POST',
        string $routeName = 'test.mutation',
        bool $json = false
    ) {
        $request = Request::create(
            '/test',
            $method,
            [],
            [],
            [],
            $json ? ['HTTP_ACCEPT' => 'application/json'] : []
        );

        $request->setUserResolver(fn () => $user);

        $request->setRouteResolver(fn () => new class($routeName) {
            public function __construct(private string $name) {}

            public function getName(): string
            {
                return $this->name;
            }
        });

        return app(VerifierAbonnementActif::class)->handle(
            $request,
            fn () => response('OK', 200)
        );
    }

    public function test_subscription_without_expiration_does_not_block_changes(): void
    {
        Carbon::setTestNow('2026-10-15 12:00:00');

        [$user, $establishmentId] = $this->preparerEtablissement();

        session(['establishment_id' => $establishmentId]);

        $response = $this->executerMiddleware($user);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_changes_are_allowed_until_the_fifteenth_day_after_expiration(): void
    {
        Carbon::setTestNow('2026-10-15 12:00:00');

        [$user, $establishmentId] = $this->preparerEtablissement(
            'admin',
            '2026-09-30'
        );

        session(['establishment_id' => $establishmentId]);

        $response = $this->executerMiddleware($user);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_changes_are_blocked_from_the_sixteenth_day_after_expiration(): void
    {
        Carbon::setTestNow('2026-10-16 12:00:00');

        [$user, $establishmentId] = $this->preparerEtablissement(
            'admin',
            '2026-09-30'
        );

        session(['establishment_id' => $establishmentId]);

        $response = $this->executerMiddleware(
            $user,
            'POST',
            'test.mutation',
            true
        );

        $this->assertSame(403, $response->getStatusCode());
        $this->assertStringContainsString(
            'lecture seule',
            $response->getContent()
        );
    }

    public function test_consultation_remains_allowed_after_expiration(): void
    {
        Carbon::setTestNow('2026-10-16 12:00:00');

        [$user, $establishmentId] = $this->preparerEtablissement(
            'admin',
            '2026-09-30'
        );

        session(['establishment_id' => $establishmentId]);

        $response = $this->executerMiddleware($user, 'GET');

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_super_admin_is_not_blocked_by_subscription_expiration(): void
    {
        Carbon::setTestNow('2026-10-16 12:00:00');

        [$user, $establishmentId] = $this->preparerEtablissement(
            'super_admin',
            '2026-09-30'
        );

        session(['establishment_id' => $establishmentId]);

        $response = $this->executerMiddleware($user);

        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_subscription_payment_route_remains_allowed_after_expiration(): void
    {
        Carbon::setTestNow('2026-10-16 12:00:00');

        [$user, $establishmentId] = $this->preparerEtablissement(
            'admin',
            '2026-09-30'
        );

        session(['establishment_id' => $establishmentId]);

        $response = $this->executerMiddleware(
            $user,
            'POST',
            'admin.abonnement.paiements.store'
        );

        $this->assertSame(200, $response->getStatusCode());
    }
}
