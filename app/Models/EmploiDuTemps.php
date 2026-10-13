<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploiDuTemps extends Model
{
    protected $table = 'emploi_du_temps';

    protected $fillable = [
        'establishment_id',
        'school_year_id',
        'class_room_id',
        'subject_id',
        'teacher_id',
        'salle_id',
        'jour',
        'heure_debut',
        'heure_fin',
    ];

    protected function casts(): array
    {
        return [
            'heure_debut' => 'datetime:H:i',
            'heure_fin' => 'datetime:H:i',
        ];
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(
            Establishment::class,
            'establishment_id'
        );
    }

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(
            SchoolYear::class,
            'school_year_id'
        );
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(
            ClassRoom::class,
            'class_room_id'
        );
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id'
        );
    }

    public function salle(): BelongsTo
    {
        return $this->belongsTo(
            Salle::class,
            'salle_id'
        );
    }
}
