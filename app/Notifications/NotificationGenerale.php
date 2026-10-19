<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotificationGenerale extends Notification
{
    use Queueable;

    public function __construct(
        public array $donnees
    ) {
    }

    /**
     * Enregistrer la notification dans l'application.
     * Aucun envoi de courriel n'est déclenché ici.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Données accessibles dans la cloche de notifications.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_key' => $this->donnees['event_key'] ?? null,
            'title' => $this->donnees['title'] ?? 'Notification OPTIMASCHOOL',
            'message' => $this->donnees['message'] ?? '',
            'url' => $this->donnees['url'] ?? '/notifications',
            'establishment_id' => $this->donnees['establishment_id'] ?? null,
            'establishment_nom' => $this->donnees['establishment_nom'] ?? null,
            'type' => $this->donnees['type'] ?? 'information',
            'date' => now()->toDateTimeString(),
        ];
    }
}
