<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Establishment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_type_id',
        'nom',
        'adresse',
        'telephone',
        'email',
        'logo',
    ];

    /**
     * Administrateur propriétaire de l'établissement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Type d'établissement :
     * Primaire, Collège, Lycée, Université.
     */
    public function schoolType(): BelongsTo
    {
        return $this->belongsTo(SchoolType::class, 'school_type_id');
    }

    /**
     * Utilisateurs affectés à cet établissement.
     *
     * Exemple :
     * - Secrétaire
     * - Enseignant
     * - Élève
     * - Parent
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'establishment_user',
            'establishment_id',
            'user_id'
        )->withTimestamps();
    }

    /**
     * Années scolaires.
     */
    public function schoolYears(): HasMany
    {
        return $this->hasMany(
            SchoolYear::class,
            'establishment_id'
        );
    }

    /**
     * Étudiants / élèves.
     */
    public function students(): HasMany
    {
        return $this->hasMany(
            Student::class,
            'establishment_id'
        );
    }

    /**
     * Enseignants.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(
            Teacher::class,
            'establishment_id'
        );
    }

    /**
     * Classes.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(
            ClassRoom::class,
            'establishment_id'
        );
    }

    /**
     * Matières.
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(
            Subject::class,
            'establishment_id'
        );
    }

    /**
     * Inscriptions.
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(
            Inscription::class,
            'establishment_id'
        );
    }

    /**
     * Notes.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(
            Note::class,
            'establishment_id'
        );
    }

    /**
     * Paiements.
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(
            Paiement::class,
            'establishment_id'
        );
    }

    /**
     * Parents.
     *
     * IMPORTANT :
     * On utilise ParentModel et non Parent,
     * car "parent" est un mot réservé en PHP.
     */
    /**
     * Abonnement logiciel de cet établissement.
     * Distinct des paiements scolaires.
     */
    public function abonnement(): HasOne
    {
        return $this->hasOne(Abonnement::class, 'establishment_id');
    }

    public function parents(): HasMany
    {
        return $this->hasMany(
            ParentModel::class,
            'establishment_id'
        );
    }
}
