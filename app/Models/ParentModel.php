<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Élèves rattachés à ce parent.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            "parent_student",
            "parent_id",
            "student_id"
        )->withTimestamps();
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
