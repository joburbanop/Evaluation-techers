<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\TestAreaCompetencyLevel;
use App\Models\Test;

class TestAreaCompetencyLevelsSeeder extends Seeder
{
    public function run()
    {
        $test = Test::where('name', 'Evaluación de Competencias Digitales Docentes')->first();

        if (!$test) {
            $this->command->error("No se encontró el test de Competencias Digitales Docentes");
            return;
        }

        $niveles = [
            'Participación profesional' => [
                ['A1', 'Novato', 0, 4, 'muy poca experiencia y contacto con la tecnología educativa. Necesita orientación continua para mejorar su nivel competencial digital docente.'],
                ['A2', 'Explorador', 5, 7, 'poco contacto con la tecnología educativa. No ha desarrollado estrategias específicas para incluir las TIC en el aula. Necesita orientación externa para mejorar su nivel competencial digital docente.'],
                ['B1', 'Integrador', 8, 10, 'experimenta con la tecnología educativa y reflexiona sobre su idoneidad para los distintos contextos educativos.'],
                ['B2', 'Experto', 11, 13, 'utiliza una amplia gama de tecnologías educativas con seguridad, confianza y creatividad. Busca la mejora continua de sus prácticas docentes.'],
                ['C1', 'Líder', 14, 15, 'capaz de adaptar a sus necesidades los distintos recursos, estrategias y conocimientos a su alcance. Es una fuente de inspiración para otros docentes.'],
                ['C2', 'Pionero', 16, 16, 'cuestiona las prácticas digitales y pedagógicas contemporáneas, de las que ellos mismos son expertos. Lideran la innovación con TIC y son un modelo a seguir para otros docentes.']
            ],
            'Tecnologias digitales' => [
                ['A1', 'Novato', 0, 3, 'muy poca experiencia y contacto con la tecnología educativa. Necesita orientación continua para mejorar su nivel competencial digital docente.'],
                ['A2', 'Explorador', 4, 5, 'poco contacto con la tecnología educativa. No ha desarrollado estrategias específicas para incluir las TIC en el aula. Necesita orientación externa para mejorar su nivel competencial digital docente.'],
                ['B1', 'Integrador', 6, 7, 'experimenta con la tecnología educativa y reflexiona sobre su idoneidad para los distintos contextos educativos.'],
                ['B2', 'Experto', 8, 9, 'utiliza una amplia gama de tecnologías educativas con seguridad, confianza y creatividad. Busca la mejora continua de sus prácticas docentes.'],
                ['C1', 'Líder', 10, 11, 'capaz de adaptar a sus necesidades los distintos recursos, estrategias y conocimientos a su alcance. Es una fuente de inspiración para otros docentes.'],
                ['C2', 'Pionero', 12, 12, 'cuestiona las prácticas digitales y pedagógicas contemporáneas, de las que ellos mismos son expertos. Lideran la innovación con TIC y son un modelo a seguir para otros docentes.']
            ],
            'Promocion de la competencia digital del alumnado' => [
                ['A1', 'Novato', 0, 6, 'muy poca experiencia y contacto con la tecnología educativa. Necesita orientación continua para mejorar su nivel competencial digital docente.'],
                ['A2', 'Explorador', 7, 8, 'poco contacto con la tecnología educativa. No ha desarrollado estrategias específicas para incluir las TIC en el aula. Necesita orientación externa para mejorar su nivel competencial digital docente.'],
                ['B1', 'Integrador', 9, 12, 'experimenta con la tecnología educativa y reflexiona sobre su idoneidad para los distintos contextos educativos.'],
                ['B2', 'Experto', 13, 16, 'utiliza una amplia gama de tecnologías educativas con seguridad, confianza y creatividad. Busca la mejora continua de sus prácticas docentes.'],
                ['C1', 'Líder', 17, 19, 'capaz de adaptar a sus necesidades los distintos recursos, estrategias y conocimientos a su alcance. Es una fuente de inspiración para otros docentes.'],
                ['C2', 'Pionero', 20, 20, 'cuestiona las prácticas digitales y pedagógicas contemporáneas, de las que ellos mismos son expertos. Lideran la innovación con TIC y son un modelo a seguir para otros docentes.']
            ],
            'Eseñanza y aprendizaje' => [
                ['A1', 'Novato', 0, 4, 'muy poca experiencia y contacto con la tecnología educativa. Necesita orientación continua para mejorar su nivel competencial digital docente.'],
                ['A2', 'Explorador', 5, 7, 'poco contacto con la tecnología educativa. No ha desarrollado estrategias específicas para incluir las TIC en el aula. Necesita orientación externa para mejorar su nivel competencial digital docente.'],
                ['B1', 'Integrador', 8, 10, 'experimenta con la tecnología educativa y reflexiona sobre su idoneidad para los distintos contextos educativos.'],
                ['B2', 'Experto', 11, 13, 'utiliza una amplia gama de tecnologías educativas con seguridad, confianza y creatividad. Busca la mejora continua de sus prácticas docentes.'],
                ['C1', 'Líder', 14, 15, 'capaz de adaptar a sus necesidades los distintos recursos, estrategias y conocimientos a su alcance. Es una fuente de inspiración para otros docentes.'],
                ['C2', 'Pionero', 16, 16, 'cuestiona las prácticas digitales y pedagógicas contemporáneas, de las que ellos mismos son expertos. Lideran la innovación con TIC y son un modelo a seguir para otros docentes.']
            ],
            'Evaluación' => [
                ['A1', 'Novato', 0, 3, 'muy poca experiencia y contacto con la tecnología educativa. Necesita orientación continua para mejorar su nivel competencial digital docente.'],
                ['A2', 'Explorador', 4, 5, 'poco contacto con la tecnología educativa. No ha desarrollado estrategias específicas para incluir las TIC en el aula. Necesita orientación externa para mejorar su nivel competencial digital docente.'],
                ['B1', 'Integrador', 6, 7, 'experimenta con la tecnología educativa y reflexiona sobre su idoneidad para los distintos contextos educativos.'],
                ['B2', 'Experto', 8, 9, 'utiliza una amplia gama de tecnologías educativas con seguridad, confianza y creatividad. Busca la mejora continua de sus prácticas docentes.'],
                ['C1', 'Líder', 10, 11, 'capaz de adaptar a sus necesidades los distintos recursos, estrategias y conocimientos a su alcance. Es una fuente de inspiración para otros docentes.'],
                ['C2', 'Pionero', 12, 12, 'cuestiona las prácticas digitales y pedagógicas contemporáneas, de las que ellos mismos son expertos. Lideran la innovación con TIC y son un modelo a seguir para otros docentes.']
            ],
            'Formacion de estudiantes' => [
                ['A1', 'Novato', 0, 3, 'muy poca experiencia y contacto con la tecnología educativa. Necesita orientación continua para mejorar su nivel competencial digital docente.'],
                ['A2', 'Explorador', 4, 5, 'poco contacto con la tecnología educativa. No ha desarrollado estrategias específicas para incluir las TIC en el aula. Necesita orientación externa para mejorar su nivel competencial digital docente.'],
                ['B1', 'Integrador', 6, 7, 'experimenta con la tecnología educativa y reflexiona sobre su idoneidad para los distintos contextos educativos.'],
                ['B2', 'Experto', 8, 9, 'utiliza una amplia gama de tecnologías educativas con seguridad, confianza y creatividad. Busca la mejora continua de sus prácticas docentes.'],
                ['C1', 'Líder', 10, 11, 'capaz de adaptar a sus necesidades los distintos recursos, estrategias y conocimientos a su alcance. Es una fuente de inspiración para otros docentes.'],
                ['C2', 'Pionero', 12, 12, 'cuestiona las prácticas digitales y pedagógicas contemporáneas, de las que ellos mismos son expertos. Lideran la innovación con TIC y son un modelo a seguir para otros docentes.']
            ]
        ];

        foreach ($niveles as $areaName => $rangos) {
            $area = Area::where('name', $areaName)->first();

            if (!$area) {
                $this->command->warn("Área no encontrada: {$areaName}");
                continue;
            }

            foreach ($rangos as [$code, $name, $min, $max, $desc]) {
                TestAreaCompetencyLevel::updateOrCreate([
                    'test_id' => $test->id,
                    'area_id' => $area->id,
                    'code' => $code,
                ], [
                    'name' => $name,
                    'min_score' => $min,
                    'max_score' => $max,
                    'description' => $desc,
                ]);
            }
        }

        $this->command->info("Niveles de competencia por área para el test de Competencias Digitales cargados correctamente.");
    }
}