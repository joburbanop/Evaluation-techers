<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Obtiene las ciudades del departamento.
     */
    public function ciudades(): HasMany
    {
        return $this->hasMany(Ciudad::class);
    }
} 