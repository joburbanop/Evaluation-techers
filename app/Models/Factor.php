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
                'area_id' => 2
            ],


            [
                'name' => 'Enseñanza y Aprendizaje',
                'description' => 'Evaluación y retroalimentación del aprendizaje',
                'area_id' => 3
            ],

            [
                'name' => 'Evaluación y retroalimentación',
                'description' => 'Fomento del aprendizaje activo y autónomo',
                'area_id' => 4
            ],

            [
                'name' => 'Empoderar a los estudiantes',
                'description' => 'Desarrollo de la competencia digital de los estudiantes',
                'area_id' => 5
            ],

            [
                'name' => 'Facilitar la competencia digital de los estudiantes',
                'description' => 'Fomentar el uso responsable y crítico de la tecnología',
                'area_id' => 6
            ],

            [
                'name' => 'Educación abierta',
                'description' => 'Promoción de la educación abierta y el acceso a recursos educativos abiertos',
                'area_id' => 7
            ],


            [
                'name' => 'Información Socio-demográfica' ,
                'description' => 'Información básica del docente para el análisis de resultados',
                'area_id' => 8
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
