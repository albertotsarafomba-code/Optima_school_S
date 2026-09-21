<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Champs pouvant être remplis avec User::create().
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Champs cachés.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Casts.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Retourne les initiales de l'utilisateur.
     */
    public function initials(): string
    {
        $name = trim($this->name);

        if ($name === '') {
            return 'U';
        }

        $parts = preg_split('/\s+/', $name);

        if (count($parts) === 1) {
            return strtoupper(mb_substr($parts[0], 0, 1));
        }

        return strtoupper(
            mb_substr($parts[0], 0, 1) .
            mb_substr($parts[count($parts) - 1], 0, 1)
        );
    }

    /**
     * Établissements dont l'utilisateur est propriétaire.
     *
     * Principalement utilisé pour les administrateurs.
     */
    public function establishments(): HasMany
    {
        return $this->hasMany(
            Establishment::class,
            'user_id'
        );
    }

    /**
     * Établissements auxquels l'utilisateur est affecté.
     *
     * Principalement utilisé pour :
     * - secrétariat
     * - enseignants
     * - élèves
     * - parents
     */
    public function assignedEstablishments(): BelongsToMany
    {
        return $this->belongsToMany(
            Establishment::class,
            'establishment_user',
            'user_id',
            'establishment_id'
        )->withTimestamps();
    }

    /**
     * Profil étudiant / élève lié à ce compte.
     */
    public function student(): HasOne
    {
        return $this->hasOne(
            Student::class,
            'user_id'
        );
    }

    /**
     * Profil enseignant lié à ce compte.
     */
    public function teacher(): HasOne
    {
        return $this->hasOne(
            Teacher::class,
            'user_id'
        );
    }

    /**
     * Profil parent lié à ce compte.
     *
     * IMPORTANT :
     * Le modèle s'appelle ParentModel,
     * car Parent est réservé en PHP.
     */
    public function parent(): HasOne
    {
        return $this->hasOne(
            ParentModel::class,
            'user_id'
        );
    }
}
