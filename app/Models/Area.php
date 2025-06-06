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
                'name' => 'Participación profesional',
                'description' => 'Área enfocada en el desarrollo profesional continuo y la identificación de recursos digitales'
            ],
            [
                'name' => 'Tecnologias digitales',
                'description' => 'Área enfocada en la implementación de prácticas pedagógicas digitales y evaluación'
            ],
            [
                'name' => 'Eseñanza y aprendizaje',
                'description' => 'Área enfocada en el fomento del aprendizaje activo y el desarrollo de competencias digitales'
            ],
            [
                'name' => 'Evaluación',
                'description' => 'Área enfocada en la evaluación del aprendizaje y la retroalimentación efectiva'
            ],
            [
                'name' => 'Formacion de estudiantes',
                'description' => 'Área enfocada en empoderar a los estudiantes en su proceso de aprendizaje'
            ],
            [
                'name' => 'Promocion de la competencia digital del alumnado',
                'description' => 'Área enfocada en promover la competencia digital de los estudiantes'

            ],
            [
                'name' => 'Educación Abierta',
                'description' => 'Área enfocada en la promoción de la educación abierta y el acceso a recursos educativos abiertos'
            ],
            [
                'name'=> 'Información Socio-demográfica',
                'description' => 'Área enfocada en la recopilación y análisis de información socio-demográfica relevante para la educación'
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
