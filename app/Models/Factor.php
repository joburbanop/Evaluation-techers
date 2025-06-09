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
                'description' => 'se centra en el entorno de trabajo de los docentes. La competencia digital de los docentes se expresa en su
                                    capacidad para utilizar las tecnologías digitales no solo para mejorar la
                                    enseñanza, sino también para interaccionar profesionalmente con
                                    compañeros, alumnado, familia y distintos agentes de la comunidad
                                    educativa.',
                'area_id' => 1
            ],

            [
                'name' => 'Recursos digitales',
                'description' => 'relacionada con las fuentes, creación y distribución
                                    de recursos digitales. Una de las competencias clave que cualquier
                                    docente debe desarrollar es identificar buenos recursos educativos.
                                    Además, debe ser capaz de modificarlos, crearlos y compartirlos para
                                    que se ajusten a sus objetivos, alumnado y estilo de enseñanza. Al mismo
                                    tiempo, debe saber cómo usar y administrar de manera responsable el
                                    contenido digital, respetando las normas de derechos de autor y
                                    protegiendo los datos personales.',
                'area_id' => 2
            ],


            [
                'name' => 'Enseñanza y Aprendizaje',
                'description' => 'Evaluación y retroalimentación del aprendizaje',
                'area_id' => 3
            ],

            [
                'name' => 'Evaluación y retroalimentación',
                'description' => 'vinculada al uso de herramientas y
                                    estrategias digitales en la evaluación y mejora de los procesos de
                                    enseñanza-aprendizaje. Las tecnologías digitales pueden mejorar las
                                    estrategias de evaluación existentes y dar lugar a nuevos y mejores
                                    métodos de evaluación.',
                'area_id' => 4
            ],

            [
                'name' => 'Empoderar a los estudiantes',
                'description' => 'una de las fortalezas clave de las
                                tecnologías digitales en la educación es su potencial para impulsar la
                                participación activa de los estudiantes en el proceso de aprendizaje y su
                                autonomía sobre el mismo. Además, las tecnologías digitales se pueden
                                utilizar para ofrecer actividades de aprendizaje adaptadas al nivel de
                                competencia de cada estudiante, sus intereses y necesidades de
                                aprendizaje.',
                'area_id' => 5
            ],

            [
                'name' => 'Facilitar la competencia digital de los estudiantes',
                'description' => 'sobre cómo desarrollar y facilitar la competencia digital ciudadana del alumnado.',
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
