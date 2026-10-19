<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\NotificationGenerale;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationsMetierTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_utilisateur_authentifie_peut_consulter_ses_notifications(): void
    {
        $user = User::factory()->create();

        $user->notify(new NotificationGenerale([
            'event_key' => 'test:consultation',
            'title' => 'Notification de test',
            'message' => 'Message de test.',
            'url' => '/notifications',
            'type' => 'information',
        ]));

        $response = $this->actingAs($user)
            ->get(route('notifications.index'));

        $response->assertOk();
        $response->assertSee('Notification de test');
        $this->assertSame(1, $user->unreadNotifications()->count());
    }

    public function test_un_utilisateur_ne_peut_pas_marquer_comme_lue_la_notification_d_un_autre(): void
    {
        $proprietaire = User::factory()->create();
        $autreUtilisateur = User::factory()->create();

        $proprietaire->notify(new NotificationGenerale([
            'event_key' => 'test:propriete',
            'title' => 'Notification privée',
            'message' => 'Cette notification appartient à son propriétaire.',
            'url' => '/notifications',
            'type' => 'information',
        ]));

        $notification = $proprietaire->notifications()->firstOrFail();

        $response = $this->actingAs($autreUtilisateur)
            ->post(route('notifications.read', [
                'notification' => $notification->id,
            ]));

        $response->assertNotFound();

        $this->assertNull(
            $notification->fresh()->read_at,
            'La notification du propriétaire ne doit pas être marquée comme lue.'
        );
    }

    public function test_un_utilisateur_peut_marquer_sa_notification_comme_lue(): void
    {
        $user = User::factory()->create();

        $user->notify(new NotificationGenerale([
            'event_key' => 'test:lecture',
            'title' => 'Notification à lire',
            'message' => 'Message de test.',
            'url' => '/notifications',
            'type' => 'information',
        ]));

        $notification = $user->notifications()->firstOrFail();

        $response = $this->actingAs($user)
            ->from(route('notifications.index'))
            ->post(route('notifications.read', [
                'notification' => $notification->id,
            ]));

        $response->assertRedirect(route('notifications.index'));

        $this->assertNotNull($notification->fresh()->read_at);
        $this->assertSame(0, $user->unreadNotifications()->count());
    }

    public function test_un_utilisateur_peut_marquer_toutes_ses_notifications_comme_lues(): void
    {
        $user = User::factory()->create();
        $autreUtilisateur = User::factory()->create();

        foreach (range(1, 3) as $numero) {
            $user->notify(new NotificationGenerale([
                'event_key' => 'test:toutes:' . $numero,
                'title' => 'Notification ' . $numero,
                'message' => 'Message de test.',
                'url' => '/notifications',
                'type' => 'information',
            ]));
        }

        $autreUtilisateur->notify(new NotificationGenerale([
            'event_key' => 'test:autre-utilisateur',
            'title' => 'Notification indépendante',
            'message' => 'Cette notification ne doit pas être modifiée.',
            'url' => '/notifications',
            'type' => 'information',
        ]));

        $response = $this->actingAs($user)
            ->post(route('notifications.read-all'));

        $response->assertRedirect(route('notifications.index'));

        $this->assertSame(0, $user->unreadNotifications()->count());

        $this->assertSame(
            1,
            $autreUtilisateur->unreadNotifications()->count(),
            'Les notifications des autres utilisateurs doivent rester intactes.'
        );
    }

    public function test_un_visiteur_non_connecte_ne_peut_pas_consulter_les_notifications(): void
    {
        $response = $this->get(route('notifications.index'));

        $response->assertRedirect(route('login'));
    }
}
