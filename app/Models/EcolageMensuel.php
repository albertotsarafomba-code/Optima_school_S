<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcolageMensuel extends Model
{
    protected $fillable = [
        'establishment_id',
        'school_year_id',
        'student_id',
        'montant_mensuel',
    ];

    protected $casts = [
        'montant_mensuel' => 'decimal:2',
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
     * Étudiant concerné.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}