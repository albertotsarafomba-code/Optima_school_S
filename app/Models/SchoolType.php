<?php

namespace App\Models;

use App\Models\Establishment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function establishments(): HasMany
    {
        return $this->hasMany(Establishment::class);
    }
}
