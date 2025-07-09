<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionPorInstitucion extends Model
{
    protected $table = 'vw_evaluaciones_por_institucion';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;

    // Proteger contra escritura
    public function save(array $options = []) { return false; }
    public function delete() { return false; }
    public function update(array $attributes = [], array $options = []) { return false; }

    // Relaciones
    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    // Scopes
    public function scopeByDateRange($query, $dateFrom, $dateTo)
    {
        return $query->whereBetween('created_at', [$dateFrom, $dateTo]);
    }

    public function scopeByAcademicCharacter($query, $character)
    {
        return $query->where('academic_character', $character);
    }

    public function scopeByDepartamento($query, $departamento)
    {
        return $query->where('departamento_domicilio', $departamento);
    }
} 