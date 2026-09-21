<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    protected $fillable = [
        'establishment_id',
        'school_year_id',
        'school_type_id',
        'code',
        'nom',
        'description',
    ];

    /**
     * Établissement auquel appartient la matière.
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(
            Establishment::class,
            'establishment_id'
        );
    }

    /**
     * Année scolaire de la matière.
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(
            SchoolYear::class,
            'school_year_id'
        );
    }

    /**
     * Type d'établissement.
     *
     * Conservé pour les anciennes données.
     */
    public function schoolType(): BelongsTo
    {
        return $this->belongsTo(
            SchoolType::class,
            'school_type_id'
        );
    }

    /**
     * Affectations de la matière aux classes.
     */
    public function classRoomSubjects(): HasMany
    {
        return $this->hasMany(
            ClassRoomSubject::class,
            'subject_id'
        );
    }

    /**
     * Affectations de la matière aux enseignants.
     */
    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(
            TeacherSubject::class,
            'subject_id'
        );
    }

    /**
     * Classes qui utilisent cette matière.
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(
            ClassRoom::class,
            'class_room_subjects',
            'subject_id',
            'class_room_id'
        )
        ->withPivot('coefficient')
        ->withTimestamps();
    }

    /**
     * Enseignants qui enseignent cette matière.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(
            Teacher::class,
            'teacher_subjects',
            'subject_id',
            'teacher_id'
        )
        ->withPivot([
            'class_room_id',
            'school_year_id',
        ])
        ->withTimestamps();
    }
}
