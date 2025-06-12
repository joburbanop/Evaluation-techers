<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
    public function competencyLevels(): HasMany
    {
        return $this->hasMany(AreaCompetencyLevel::class);
    }

    public static function initializeAreas(): void
    {
        // Desactiva claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        static::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
                'name' => 'Promocion de la competencia digital del alumnado',
                'description' => 'Área enfocada en promover la competencia digital de los estudiantes'
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
                'name' => 'Educación Abierta',
                'description' => 'Área enfocada en la promoción de la educación abierta y el acceso a recursos educativos abiertos'
            ],
            [
                'name'=> 'Información Socio-demográfica',
                'description' => 'Área enfocada en la recopilación y análisis de información socio-demográfica relevante para la educación'
            ]
        ];

        foreach ($areas as $area) {
            static::create($area);
        }
    }
}
