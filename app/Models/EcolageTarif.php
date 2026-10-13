<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcolageTarif extends Model
{
    protected $table = 'ecolage_tarifs';

    protected $fillable = [
        'establishment_id',
        'school_year_id',
        'class_room_id',
        'montant_mensuel',
    ];

    protected $casts = [
        'montant_mensuel' => 'decimal:2',
    ];

    /**
     * Établissement concerné.
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(
            Establishment::class,
            'establishment_id'
        );
    }

    /**
     * Année scolaire concernée.
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(
            SchoolYear::class,
            'school_year_id'
        );
    }

    /**
     * Classe concernée.
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(
            ClassRoom::class,
            'class_room_id'
        );
    }
}
