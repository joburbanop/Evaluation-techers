<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    use HasFactory;

    protected $table = 'ciudades';

    protected $fillable = [
        'name',
        'departamento_id',
    ];

    /**
     * Obtiene el departamento al que pertenece la ciudad.
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Obtiene las instituciones de la ciudad.
     */
    public function instituciones(): HasMany
    {
        return $this->hasMany(Institution::class, 'ciudad_id');
    }
} 