<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'establishment_id',
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'adresse',
        'telephone',
        'email',
        'specialite',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(
            Establishment::class,
            'establishment_id'
        );
    }

    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(
            TeacherSubject::class,
            'teacher_id'
        );
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            'teacher_subjects',
            'teacher_id',
            'subject_id'
        )
        ->withPivot([
            'class_room_id',
            'school_year_id',
        ])
        ->withTimestamps();
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(
            ClassRoom::class,
            'teacher_subjects',
            'teacher_id',
            'class_room_id'
        )
        ->withPivot([
            'subject_id',
            'school_year_id',
        ])
        ->withTimestamps();
    }
}