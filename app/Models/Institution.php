<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_character',
        'departamento_domicilio',
        'municipio_domicilio',
        'programas_vigentes',
        'active_programs',
        'is_accredited',
        'departamento_id',
        'ciudad_id',
        'contact_person',
        'contact_position',
        'contact_phone',
        'contact_email',
        'test_id',
        'additional_notes',
    ];

    /**
     * Relación: Institution pertenece a un Departamento.
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    /**
     * Relación: Institution pertenece a una Ciudad.
     */
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    /**
     * Relación: Institution tiene muchas Facultades.
     */
    public function facultades(): HasMany
    {
        return $this->hasMany(Facultad::class, 'institution_id');
    }

    /**
     * Relación: Institution tiene muchos Usuarios.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'institution_id');
    }

    /**
     * Si quieres mantener la relación de tests (muchos a muchos)
     */
    public function tests()
    {
        return $this->belongsToMany(Test::class);
    }
}