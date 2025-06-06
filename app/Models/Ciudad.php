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
        // otros campos si los tuvieras
    ];

    /**
     * Relación: Ciudad pertenece a un Departamento.
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    /**
     * Relación: Ciudad tiene muchas Instituciones.
     */
    public function instituciones(): HasMany
    {
        return $this->hasMany(Institution::class, 'ciudad_id');
    }

    /**
     * Relación: Ciudad tiene muchos Usuarios.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'ciudad_id');
    }
}