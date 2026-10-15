<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Abonnement extends Model
{
    use HasFactory;

    protected $table = 'abonnements';

    protected $fillable = [
        'establishment_id',
        'date_debut',
        'date_expiration',
        'montant_mensuel',
        'devise',
        'statut',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_expiration' => 'date',
            'montant_mensuel' => 'decimal:2',
        ];
    }

    /**
     * Etablissement auquel appartient cet abonnement.
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * Historique des paiements de cet abonnement.
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(AbonnementPaiement::class);
    }
}
