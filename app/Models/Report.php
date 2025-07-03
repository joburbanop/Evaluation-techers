<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type', // 'facultad', 'programa', 'institution'
        'entity_id', // ID de la facultad, programa o institución
        'entity_type', // 'App\Models\Facultad', 'App\Models\Programa', 'App\Models\Institution'
        'file_path',
        'file_size',
        'generated_by',
        'parameters', // JSON con filtros aplicados
        'status', // 'pending', 'generating', 'completed', 'failed'
        'generated_at',
        'expires_at'
    ];

    protected $casts = [
        'parameters' => 'array',
        'generated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function entity()
    {
        return $this->morphTo();
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'generating' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'generating' => 'Generando',
            'completed' => 'Completado',
            'failed' => 'Fallido',
            default => 'Desconocido',
        };
    }
} 