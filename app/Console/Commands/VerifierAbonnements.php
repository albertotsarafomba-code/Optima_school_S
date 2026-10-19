<?php

namespace App\Console\Commands;

use App\Models\Abonnement;
use App\Models\User;
use App\Notifications\AbonnementExpirationNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

#[Signature('app:verifier-abonnements')]
#[Description('Vérifie les expirations des abonnements OPTIMASCHOOL et envoie les notifications.')]
class VerifierAbonnements extends Command
{
    public function handle(): int
    {
        $today = now()->startOfDay();

        $evenements = [
            7 => 'avant_7_jours',
            3 => 'avant_3_jours',
            0 => 'expiration',
            -7 => 'apres_7_jours',
            -14 => 'apres_14_jours',
            -15 => 'apres_15_jours',
            -16 => 'apres_16_jours',
        ];

        $superAdmins = User::query()
            ->where('role', 'super_admin')
            ->get();

        $totalNotifications = 0;

        Abonnement::query()
            ->with(['establishment.user'])
            ->whereNotNull('date_expiration')
            ->orderBy('id')
            ->chunkById(100, function ($abonnements) use (
                $today,
                $evenements,
                $superAdmins,
                &$totalNotifications
            ): void {
                foreach ($abonnements as $abonnement) {
                    if (! $abonnement->date_expiration) {
                        continue;
                    }

                    $expiration = $abonnement->date_expiration
                        ->copy()
                        ->startOfDay();

                    $joursRestants = (int) $today->diffInDays(
                        $expiration,
                        false
                    );

                    if (! array_key_exists($joursRestants, $evenements)) {
                        continue;
                    }

                    $evenement = $evenements[$joursRestants];

                    $etablissement = $abonnement->establishment;

                    if (! $etablissement) {
                        $this->warn(
                            "Abonnement #{$abonnement->id} : établissement introuvable."
                        );

                        continue;
                    }

                    $destinataires = collect();

                    if ($etablissement->user) {
                        $destinataires->push($etablissement->user);
                    }

                    foreach ($superAdmins as $superAdmin) {
                        $destinataires->push($superAdmin);
                    }

                    $destinataires = $destinataires
                        ->unique('id')
                        ->values();

                    foreach ($destinataires as $destinataire) {
                        $eventKey = $abonnement->id . ':' . $evenement;

                        $dejaEnvoyee = $destinataire
                            ->notifications()
                            ->where(
                                'type',
                                AbonnementExpirationNotification::class
                            )
                            ->where('data->event_key', $eventKey)
                            ->exists();

                        if ($dejaEnvoyee) {
                            continue;
                        }

                        try {
                            $destinataire->notify(
                                new AbonnementExpirationNotification(
                                    $abonnement,
                                    $evenement,
                                    abs($joursRestants)
                                )
                            );

                            $totalNotifications++;

                            $this->info(
                                "Notification {$evenement} envoyée à "
                                . "{$destinataire->email} "
                                . "pour {$etablissement->nom}."
                            );
                        } catch (\Throwable $exception) {
                            report($exception);

                            $this->error(
                                "Échec de notification pour "
                                . "{$destinataire->email} "
                                . "(abonnement #{$abonnement->id})."
                            );
                        }
                    }
                }
            });

        $this->info(
            "Vérification terminée. Notifications envoyées : "
            . $totalNotifications
        );

        return self::SUCCESS;
    }
}
