<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolYear extends Model
{
    protected $fillable = [
        'establishment_id',
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Une année scolaire appartient à un établissement.
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(
            Establishment::class,
            'establishment_id'
        );
    }

    /**
     * Une année scolaire possède plusieurs classes.
     */
    public function classRooms(): HasMany
    {
        return $this->hasMany(
            ClassRoom::class,
            'school_year_id'
        );
    }

    /**
     * Une année scolaire possède plusieurs inscriptions.
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(
            Inscription::class,
            'school_year_id'
        );
    }
}
