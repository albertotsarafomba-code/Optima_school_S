<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassRoom extends Model
{
    protected $fillable = [
        'establishment_id',
        'nom_classe',
        'niveau',
        'filiere',
        'school_year_id',
        'teacher_id',
        'salle_id',
        'annee_scolaire',
        'salle',
        'capacite',
        'effectif_minimum',
        'effectif_maximum',
    ];

    protected $casts = [
        'establishment_id' => 'integer',
        'school_year_id' => 'integer',
        'teacher_id' => 'integer',
        'salle_id' => 'integer',
        'capacite' => 'integer',
        'effectif_minimum' => 'integer',
        'effectif_maximum' => 'integer',
    ];

    /**
     * Établissement auquel appartient la classe.
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(
            Establishment::class,
            'establishment_id'
        );
    }

    /**
     * Année scolaire de la classe.
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(
            SchoolYear::class,
            'school_year_id'
        );
    }

    /**
     * Enseignant principal de la classe.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id'
        );
    }

    /**
     * Salle affectée à la classe.
     *
     * Relation principale.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(
            Salle::class,
            'salle_id'
        );
    }

    /**
     * Alias de room().
     *
     * Permet d'utiliser aussi :
     *
     * ClassRoom::with('salle')
     *
     * puisque la colonne de liaison est salle_id.
     */
    public function salle(): BelongsTo
    {
        return $this->belongsTo(
            Salle::class,
            'salle_id'
        );
    }

    /**
     * Inscriptions des étudiants dans cette classe.
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(
            Inscription::class,
            'class_room_id'
        );
    }

    /**
     * Associations classe-matière.
     */
    public function classRoomSubjects(): HasMany
    {
        return $this->hasMany(
            ClassRoomSubject::class,
            'class_room_id'
        );
    }

    /**
     * Matières affectées à la classe.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class,
            'class_room_subjects',
            'class_room_id',
            'subject_id'
        )
        ->withPivot('coefficient')
        ->withTimestamps();
    }

    /**
     * Associations enseignant-matière-classe.
     */
    public function teacherSubjects(): HasMany
    {
        return $this->hasMany(
            TeacherSubject::class,
            'class_room_id'
        );
    }
}
