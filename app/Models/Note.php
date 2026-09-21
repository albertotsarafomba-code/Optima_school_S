<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'establishment_id',
        'teacher_id',
        'student_id',
        'subject_id',
        'class_room_id',
        'school_year_id',
        'note',
        'coefficient',
        'type_evaluation',
        'date_evaluation',
    ];

    protected function casts(): array
    {
        return [
            'note' => 'decimal:2',
            'coefficient' => 'decimal:2',
            'date_evaluation' => 'date',
        ];
    }

    /**
     * Établissement auquel appartient la note.
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * Enseignant ayant attribué la note.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Étudiant ayant reçu la note.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Matière concernée par la note.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Classe de l'étudiant.
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    /**
     * Année scolaire de la note.
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
