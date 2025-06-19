<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facultad extends Model
{
    use HasFactory;

    protected $table = 'facultades';

    protected $fillable = [
        'nombre',
        'institution_id',
        // otros campos que tenga tu tabla facultades
    ];

    /**
     * Una Facultad pertenece a una Institution.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    /**
     * Una Facultad tiene muchos Programas.
     */
    public function programas(): HasMany
    {
        return $this->hasMany(Programa::class, 'facultad_id');
    }

    /**
     * Una Facultad tiene muchos Usuarios que pertenecen a ella.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'facultad_id');
    }
}