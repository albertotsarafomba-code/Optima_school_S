<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbonnementPaiement extends Model
{
    use HasFactory;

    protected $table = 'abonnement_paiements';

    protected $fillable = [
        'abonnement_id',
        'montant',
        'mode_paiement',
        'reference_transaction',
        'preuve_paiement',
        'statut',
        'verifie_par',
        'date_soumission',
        'date_paiement',
        'date_verification',
        'note_verification',
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
            'date_soumission' => 'datetime',
            'date_paiement' => 'datetime',
            'date_verification' => 'datetime',
        ];
    }

    /**
     * Abonnement concerne par ce paiement.
     */
    public function abonnement(): BelongsTo
    {
        return $this->belongsTo(Abonnement::class);
    }

    /**
     * Super Admin ayant vérifié le paiement.
     */
    public function verificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifie_par');
    }
}
