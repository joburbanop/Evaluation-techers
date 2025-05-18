<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Si más adelante normalizas y usas claves foráneas:
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class);
    }
}