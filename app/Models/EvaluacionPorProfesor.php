<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionPorProfesor extends Model
{
    protected $table = 'vw_evaluaciones_por_profesor';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;

    // Proteger contra escritura
    public function save(array $options = []) { return false; }
    public function delete() { return false; }
    public function update(array $attributes = [], array $options = []) { return false; }

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function facultad()
    {
        return $this->belongsTo(Facultad::class, 'facultad_id');
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'programa_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    // Scopes
    public function scopeByInstitution($query, $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }

    public function scopeByFacultad($query, $facultadId)
    {
        return $query->where('facultad_id', $facultadId);
    }

    public function scopeByPrograma($query, $programaId)
    {
        return $query->where('programa_id', $programaId);
    }

    public function scopeByArea($query, $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    public function scopeByDateRange($query, $dateFrom, $dateTo)
    {
        return $query->whereBetween('created_at', [$dateFrom, $dateTo]);
    }

    public function scopeTopPerformers($query, $limit = 10)
    {
        return $query->orderBy('promedio_score', 'desc')->limit($limit);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return trim($this->user_name . ' ' . $this->apellido1 . ' ' . $this->apellido2);
    }

    public function getPerformanceLevelAttribute()
    {
        if ($this->promedio_score >= 4.5) return 'Excelente';
        if ($this->promedio_score >= 3.5) return 'Bueno';
        if ($this->promedio_score >= 2.5) return 'Regular';
        return 'Necesita Mejora';
    }
} 