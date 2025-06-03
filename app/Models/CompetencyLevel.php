<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class CompetencyLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'min_score',
        'max_score',
        'description'
    ];

    protected $casts = [
        'min_score' => 'integer',
        'max_score' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        // Limpiar caché cuando se crea, actualiza o elimina un nivel
        static::created(function ($level) {
            static::clearCache();
        });

        static::updated(function ($level) {
            static::clearCache();
        });

        static::deleted(function ($level) {
            static::clearCache();
        });

        static::saving(function ($level) {
            // Validar que min_score sea menor que max_score
            if ($level->min_score >= $level->max_score) {
                throw ValidationException::withMessages([
                    'general' => ['La puntuación mínima debe ser menor que la puntuación máxima.']
                ]);
            }

            // Validar que no haya superposición de rangos
            $overlappingLevel = static::where('id', '!=', $level->id)
                ->where(function ($query) use ($level) {
                    $query->whereBetween('min_score', [$level->min_score, $level->max_score])
                        ->orWhereBetween('max_score', [$level->min_score, $level->max_score])
                        ->orWhere(function ($q) use ($level) {
                            $q->where('min_score', '<=', $level->min_score)
                                ->where('max_score', '>=', $level->max_score);
                        });
                })
                ->first();

            if ($overlappingLevel) {
                throw ValidationException::withMessages([
                    'general' => ['Existe superposición con otro nivel de competencia.']
                ]);
            }
        });
    }

    /**
     * Obtiene todos los niveles de competencia con caché
     */
    public static function getAllLevels()
    {
        return Cache::remember('competency_levels', 3600, function () {
            return static::orderBy('min_score', 'asc')->get();
        });
    }

    /**
     * Obtiene un nivel específico por puntuación con caché
     */
    public static function getLevelByScore(int $score): ?self
    {
        $cacheKey = "competency_level_score_{$score}";
        
        return Cache::remember($cacheKey, 3600, function () use ($score) {
        return static::where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();
        });
    }

    /**
     * Limpia toda la caché relacionada con los niveles
     */
    public static function clearCache()
    {
        Cache::forget('competency_levels');
        
        // Limpiar caché de niveles por puntuación
        $levels = static::all();
        foreach ($levels as $level) {
            for ($score = $level->min_score; $score <= $level->max_score; $score++) {
                Cache::forget("competency_level_score_{$score}");
            }
        }
    }

    public static function initializeLevels(): void
    {
        $levels = [
            [
                'name' => 'Novato',
                'code' => 'A1',
                'min_score' => 0,
                'max_score' => 19,
                'description' => 'Nivel inicial de competencia digital'
            ],
            [
                'name' => 'Explorador',
                'code' => 'A2',
                'min_score' => 20,
                'max_score' => 33,
                'description' => 'Nivel básico de competencia digital'
            ],
            [
                'name' => 'Integrador',
                'code' => 'B1',
                'min_score' => 34,
                'max_score' => 49,
                'description' => 'Nivel intermedio de competencia digital'
            ],
            [
                'name' => 'Experto',
                'code' => 'B2',
                'min_score' => 50,
                'max_score' => 65,
                'description' => 'Nivel avanzado de competencia digital'
            ],
            [
                'name' => 'Líder',
                'code' => 'C1',
                'min_score' => 66,
                'max_score' => 80,
                'description' => 'Nivel experto de competencia digital'
            ],
            [
                'name' => 'Pionero',
                'code' => 'C2',
                'min_score' => 81,
                'max_score' => 100,
                'description' => 'Nivel maestro de competencia digital'
            ],
        ];

        foreach ($levels as $level) {
            static::firstOrCreate(
                ['code' => $level['code']],
                $level
            );
        }

        // Limpiar caché después de inicializar
        static::clearCache();
    }
} 