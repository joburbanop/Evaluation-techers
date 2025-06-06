<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamentos';

    protected $fillable = [
        'name',
        // otros campos propios de departamento si los tuvieras
    ];

    /**
     * Un Departamento tiene muchas Ciudades.
     */
    public function ciudades(): HasMany
    {
        return $this->hasMany(Ciudad::class, 'departamento_id');
    }

    /**
     * Un Departamento tiene muchos Usuarios.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'departamento_id');
    }
}