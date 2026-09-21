<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'nom',
        'capacite',
        'description',
    ];

    protected $casts = [
        'capacite' => 'integer',
    ];

    /**
     * Établissement auquel appartient la salle.
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(
            Establishment::class,
            'establishment_id'
        );
    }

    /**
     * Classes affectées à cette salle.
     */
    public function classRooms(): HasMany
    {
        return $this->hasMany(
            ClassRoom::class,
            'salle_id'
        );
    }
}