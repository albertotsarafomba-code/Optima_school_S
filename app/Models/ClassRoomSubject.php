<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassRoomSubject extends Model
{
    protected $fillable = [
        'class_room_id',
        'subject_id',
        'coefficient',
    ];

    protected $casts = [
        'coefficient' => 'integer',
    ];

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

    /**
     * Matière concernée.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }
}