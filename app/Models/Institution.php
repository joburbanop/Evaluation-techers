<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Institution extends Model
{
    use HasFactory;

    protected $table = 'institutions';

    protected $fillable = [
        'nit',
        'name',
        'tipo',
        'address',
        'ciudad_id',
        'phone',
        'contact_person',
        'contact_position',
        'contact_phone',
        'contact_email',
        'email',
        'website',
        'logo',
        'additional_notes'
    ];

    /**
     * Los tipos de institución disponibles
     */
    public const TIPOS = [
        'colegio' => 'Colegio',
        'universidad' => 'Universidad'
    ];

    /**
     * Obtiene la ciudad a la que pertenece la institución.
     */
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }

    /**
     * Obtiene los usuarios de la institución.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Obtiene los tests asignados a la institución.
     */
    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }
}
