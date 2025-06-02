<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetencyLevel extends Model
{
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

    public static function getLevelByScore(int $score): ?self
    {
        return static::where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();
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
    }
} 