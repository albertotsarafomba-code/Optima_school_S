<?php

namespace App\Notifications;

use App\Models\Abonnement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbonnementExpirationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Abonnement $abonnement,
        public string $evenement,
        public int $jours
    ) {
    }

    /**
     * Notification dans l'application et par courriel.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Contenu du courriel.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $etablissement = $this->abonnement->establishment;
        $nom = $etablissement?->nom ?? 'votre établissement';

        $expiration = $this->abonnement->date_expiration
            ? $this->abonnement->date_expiration->format('d/m/Y')
            : 'non définie';

        $message = match ($this->evenement) {
            'avant_7_jours' =>
                'Votre abonnement expire dans 7 jours.',
            'avant_3_jours' =>
                'Attention : votre abonnement expire dans 3 jours.',
            'expiration' =>
                'Votre abonnement arrive à expiration aujourd’hui.',
            'apres_7_jours' =>
                'Votre abonnement a expiré depuis 7 jours. '
                . 'Vous êtes encore dans le délai de grâce.',
            'apres_14_jours' =>
                'Votre abonnement a expiré depuis 14 jours. '
                . 'Il reste un jour avant le passage en lecture seule.',
            'apres_15_jours' =>
                'Votre abonnement a expiré depuis 15 jours. '
                . 'Le passage en lecture seule est prévu demain.',
            'apres_16_jours' =>
                'Votre abonnement a expiré depuis 16 jours. '
                . 'L’établissement est désormais en lecture seule.',
            default =>
                'Veuillez vérifier la situation de votre abonnement.',
        };

        $urlAbonnement = $notifiable->role === 'super_admin'
            ? route('super-admin.abonnements.index')
            : url('/mon-abonnement');

        return (new MailMessage)
            ->subject('OPTIMASCHOOL — Information sur votre abonnement')
            ->greeting('Bonjour ' . ($notifiable->name ?? ''))
            ->line('Établissement : ' . $nom)
            ->line('Date d’expiration : ' . $expiration)
            ->line($message)
            ->action(
                'Consulter l’abonnement',
                $urlAbonnement
            )
            ->line(
                'Après expiration, le délai de grâce est de 15 jours. '
                . 'À partir du 16e jour, la consultation reste autorisée, '
                . 'mais les créations, modifications et suppressions sont bloquées. '
                . 'Aucune donnée ne sera supprimée.'
            );
    }

    /**
     * Données enregistrées pour la notification dans l'application.
     */
    public function toArray(object $notifiable): array
    {
        $etablissement = $this->abonnement->establishment;

        return [
            'event_key' => $this->abonnement->id . ':' . $this->evenement,
            'abonnement_id' => $this->abonnement->id,
            'establishment_id' => $this->abonnement->establishment_id,
            'establishment_nom' => $etablissement?->nom,
            'evenement' => $this->evenement,
            'jours' => $this->jours,
            'date_expiration' => $this->abonnement->date_expiration?->toDateString(),
            'message' => $this->messageTexte(),
            'url' => $notifiable->role === 'super_admin'
                ? route('super-admin.abonnements.index')
                : '/mon-abonnement',
        ];
    }

    private function messageTexte(): string
    {
        return match ($this->evenement) {
            'avant_7_jours' => 'Votre abonnement expire dans 7 jours.',
            'avant_3_jours' => 'Votre abonnement expire dans 3 jours.',
            'expiration' => 'Votre abonnement expire aujourd’hui.',
            'apres_7_jours' => 'Votre abonnement a expiré depuis 7 jours.',
            'apres_14_jours' => 'Votre abonnement a expiré depuis 14 jours. Il reste un jour avant le passage en lecture seule.',
            'apres_15_jours' => 'Votre abonnement a expiré depuis 15 jours. Le passage en lecture seule est prévu demain.',
            'apres_16_jours' => 'Votre abonnement a expiré depuis 16 jours. L’établissement est désormais en lecture seule.',
            default => 'Veuillez vérifier votre abonnement.',
        };
    }
}
