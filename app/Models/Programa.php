<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programa extends Model
{
    use HasFactory;

    protected $table = 'programas';

    protected $fillable = ['nombre', 'tipo', 'facultad_id'];

    /**
     * Un Programa pertenece a una Facultad.
     */
    public function facultad(): BelongsTo
    {
        return $this->belongsTo(Facultad::class, 'facultad_id');
    }

    /**
     * Un Programa tiene muchos Usuarios que lo cursan.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'programa_id');
    }
}