<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentModel extends Model
{
    protected $table = 'parents';

    protected $fillable = [
        'user_id',
        'establishment_id',
        'nom',
        'prenom',
        'telephone',
        'email',
        'adresse',
    ];

    /**
     * Compte utilisateur associé au parent.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * Établissement du parent.
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(
            Establishment::class,
            'establishment_id'
        );
    }
}
