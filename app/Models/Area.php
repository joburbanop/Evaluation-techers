<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function factors(): HasMany
    {
        return $this->hasMany(Factor::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public static function initializeAreas(): void
    {
        $areas = [
            [
                'name' => 'Compromiso profesional',
                'description' => 'Área enfocada en el desarrollo profesional continuo y la identificación de recursos digitales'
            ],
            [
                'name' => 'Práctica pedagógica',
                'description' => 'Área enfocada en la implementación de prácticas pedagógicas digitales y evaluación'
            ],
            [
                'name' => 'Empoderamiento de los estudiantes',
                'description' => 'Área enfocada en el fomento del aprendizaje activo y el desarrollo de competencias digitales'
            ]
        ];

        foreach ($areas as $area) {
            static::firstOrCreate(
                ['name' => $area['name']],
                $area
            );
        }
    }
} 