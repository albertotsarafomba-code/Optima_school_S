<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    protected $fillable = [
        'establishment_id',
        'school_year_id',
        'student_id',
        'teacher_id',
        'montant',
        'type_paiement',
        'mois',
        'annee',
        'mode_paiement',
        'date_paiement',
        'reference',
        'numero_recu',
        'description',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
        'mois' => 'integer',
        'annee' => 'integer',
    ];

    /**
     * Établissement concerné.
     */
    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    /**
     * Année scolaire concernée.
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    /**
     * Élève concerné.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Enseignant concerné.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Retourne le nom du mois.
     */
    public function getNomMoisAttribute(): ?string
    {
        if (!$this->mois) {
            return null;
        }

        $mois = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        ];

        return $mois[$this->mois] ?? null;
    }

    /**
     * Vérifie si le paiement concerne un écolage mensuel.
     */
    public function isEcolageMensuel(): bool
{
    $type = trim($this->type_paiement ?? '');

    return $type === 'Écolage mensuel'
        || $type === 'Ecolage mensuel'
        || $type === 'écolage mensuel'
        || $type === 'ecolage mensuel';
}
}