<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Factor extends Model
{
    protected $fillable = [
        'name',
        'description',
        'area_id'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public static function initializeFactors(): void
    {
        $factors = [
            [
                'name' => 'Compromiso profesional',
                'description' => 'Compromiso con el desarrollo profesional continuo',
                'area_id' => 1
            ],
            [
                'name' => 'Recursos digitales',
                'description' => 'Identificación, evaluación y uso de recursos digitales',
                'area_id' => 1
            ],
            [
                'name' => 'Práctica pedagógica',
                'description' => 'Planificación e implementación de prácticas pedagógicas digitales',
                'area_id' => 2
            ],
            [
                'name' => 'Evaluación',
                'description' => 'Evaluación y retroalimentación del aprendizaje',
                'area_id' => 2
            ],
            [
                'name' => 'Empoderamiento de los estudiantes',
                'description' => 'Fomento del aprendizaje activo y autónomo',
                'area_id' => 3
            ],
            [
                'name' => 'Facilitación de la competencia digital',
                'description' => 'Desarrollo de la competencia digital de los estudiantes',
                'area_id' => 3
            ]
        ];

        foreach ($factors as $factor) {
            static::firstOrCreate(
                ['name' => $factor['name']],
                $factor
            );
        }
    }
} 