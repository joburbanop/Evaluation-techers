<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;
use App\Models\TestAreaCompetencyLevel;
use App\Models\Test;

class TestInteligenciaArtificialCompetencyLevelsSeeder extends Seeder
{
    public function run()
    {
        $test = Test::where('name', 'Test Inteligencia Artificial')->first();

        if (!$test) {
            $this->command->error("No se encontró el test de Inteligencia Artificial");
            return;
        }

        $niveles = [
            'Participación profesional' => [
                ['A1', 'Novato', 0, 3, 'Entiende lo básico de cómo la lA puede ser usada para el desarrollo profesional y la comunicación. - Comienza a interactuar con herramientas de lA para tareas simples como respuestas automáticas de correo
electrónico o análisis de datos básicos para la gestión del aula. - Conciencia del potencial de la lA en el desarrollo profesional y la red de contactos, pero con aplicación práctica
limitada.'],
                ['A2', 'Explorador', 4, 7, 'Explora activamente diferentes herramientas de lA para la comunicación y el desarrollo profesional. - Comienza a integrar la lA en tareas profesionales rutinarias, como el uso de lA para organizar reuniones o la
interpretación básica de datos. Participa en comunidades de aprendizaje profesional discutiendo sobre la lA en la educación, compartiendo
experiencias iniciales y aprendiendo de los compañeros.'],
                ['B1', 'Integrador', 8, 11, 'Usa regularmente herramientas de lA para una gama de actividades profesionales, incluido el análisis de datos
avanzado y la optimización de la comunicación.
- Integra la lA en planes de desarrollo profesional, buscando formación y recursos enfocados en lA.
- Colabora utilizando herramientas de lA, demostrando uso efectivo en proyectos de equipo o iniciativas colaborativas.'],
                ['B2', 'Experto', 12, 15, 'Navega expertamente una variedad de herramientas de lA para fines profesionales avanzados, como análisis
predictivos para el rendimiento estudiantil. Dirige sesiones de desarrollo profesional o talleres sobre lA para colegas, compartiendo experiencia y mejores
prácticas. - Utiliza innovadoramente la lA para el crecimiento personal y organizacional, contribuyendo a la comunidad educativa
más amplia a través de iniciativas mejoradas por IA.'],
                ['C1', 'Líder', 16, 18, 'Lidera la adopción de tecnologías de lA de vanguardia en entornos profesionales.
Da forma a políticas y prácticas organizacionales en torno al uso de la lA en el compromiso profesional. Orienta a otros en la integración de la lA en prácticas profesionales, impulsando el cambio y la innovación dentro de
la comunidad educativa.'],
                ['C2', 'Pionero', 19, 24, 'Avanza en el campo contribuyendo con ideas originales o investigación sobre el uso de la lA en el desarrollo
profesional y la comunicación. Lidera proyectos o iniciativas importantes que transforman el compromiso profesional a través de la lA a un nivel
sistémico. Es un experto reconocido y líder de pensamiento en el uso de la lA en entornos educativos, influenciando políticas,
prácticas y futuros desarrollos.']
            ],
            'Tecnologias digitales' => [
                ['A1', 'Novato', 0, 3, 'Entiende lo básico de cómo la lA puede ser usada para el desarrollo profesional y la comunicación. - Comienza a interactuar con herramientas de lA para tareas simples como respuestas automáticas de correo
electrónico o análisis de datos básicos para la gestión del aula. - Conciencia del potencial de la lA en el desarrollo profesional y la red de contactos, pero con aplicación práctica
limitada.'],
                ['A2', 'Explorador', 4, 7, 'Explora activamente diferentes herramientas de lA para la comunicación y el desarrollo profesional. - Comienza a integrar la lA en tareas profesionales rutinarias, como el uso de lA para organizar reuniones o la
interpretación básica de datos. Participa en comunidades de aprendizaje profesional discutiendo sobre la lA en la educación, compartiendo
experiencias iniciales y aprendiendo de los compañeros.'],
                ['B1', 'Integrador', 8, 11, 'Usa regularmente herramientas de lA para una gama de actividades profesionales, incluido el análisis de datos
avanzado y la optimización de la comunicación.
- Integra la lA en planes de desarrollo profesional, buscando formación y recursos enfocados en lA.
- Colabora utilizando herramientas de lA, demostrando uso efectivo en proyectos de equipo o iniciativas colaborativas.'],
                ['B2', 'Experto', 12, 15,  'Navega expertamente una variedad de herramientas de lA para fines profesionales avanzados, como análisis
predictivos para el rendimiento estudiantil. Dirige sesiones de desarrollo profesional o talleres sobre lA para colegas, compartiendo experiencia y mejores
prácticas. - Utiliza innovadoramente la lA para el crecimiento personal y organizacional, contribuyendo a la comunidad educativa
más amplia a través de iniciativas mejoradas por IA.'],
                ['C1', 'Líder', 16, 18, 'Lidera la adopción de tecnologías de lA de vanguardia en entornos profesionales.
Da forma a políticas y prácticas organizacionales en torno al uso de la lA en el compromiso profesional. Orienta a otros en la integración de la lA en prácticas profesionales, impulsando el cambio y la innovación dentro de
la comunidad educativa.'],
                ['C2', 'Pionero', 19, 24,  'Avanza en el campo contribuyendo con ideas originales o investigación sobre el uso de la lA en el desarrollo
profesional y la comunicación. Lidera proyectos o iniciativas importantes que transforman el compromiso profesional a través de la lA a un nivel
sistémico. Es un experto reconocido y líder de pensamiento en el uso de la lA en entornos educativos, influenciando políticas,
prácticas y futuros desarrollos.']
            ],
            'Promocion de la competencia digital del alumnado' => [
                ['A1', 'Novato', 0, 3,  'Entiende lo básico de cómo la lA puede ser usada para el desarrollo profesional y la comunicación. - Comienza a interactuar con herramientas de lA para tareas simples como respuestas automáticas de correo
electrónico o análisis de datos básicos para la gestión del aula. - Conciencia del potencial de la lA en el desarrollo profesional y la red de contactos, pero con aplicación práctica
limitada.'],
                ['A2', 'Explorador', 4, 7, 'Explora activamente diferentes herramientas de lA para la comunicación y el desarrollo profesional. - Comienza a integrar la lA en tareas profesionales rutinarias, como el uso de lA para organizar reuniones o la
interpretación básica de datos. Participa en comunidades de aprendizaje profesional discutiendo sobre la lA en la educación, compartiendo
experiencias iniciales y aprendiendo de los compañeros.'],
                ['B1', 'Integrador', 8, 11, 'Usa regularmente herramientas de lA para una gama de actividades profesionales, incluido el análisis de datos
avanzado y la optimización de la comunicación.
- Integra la lA en planes de desarrollo profesional, buscando formación y recursos enfocados en lA.
- Colabora utilizando herramientas de lA, demostrando uso efectivo en proyectos de equipo o iniciativas colaborativas.'],
                ['B2', 'Experto', 12, 15, 'Navega expertamente una variedad de herramientas de lA para fines profesionales avanzados, como análisis
predictivos para el rendimiento estudiantil. Dirige sesiones de desarrollo profesional o talleres sobre lA para colegas, compartiendo experiencia y mejores
prácticas. - Utiliza innovadoramente la lA para el crecimiento personal y organizacional, contribuyendo a la comunidad educativa
más amplia a través de iniciativas mejoradas por IA.'],
                ['C1', 'Líder', 16, 18, 'Lidera la adopción de tecnologías de lA de vanguardia en entornos profesionales.
Da forma a políticas y prácticas organizacionales en torno al uso de la lA en el compromiso profesional. Orienta a otros en la integración de la lA en prácticas profesionales, impulsando el cambio y la innovación dentro de
la comunidad educativa.'],
                ['C2', 'Pionero', 19, 24,  'Avanza en el campo contribuyendo con ideas originales o investigación sobre el uso de la lA en el desarrollo
profesional y la comunicación. Lidera proyectos o iniciativas importantes que transforman el compromiso profesional a través de la lA a un nivel
sistémico. Es un experto reconocido y líder de pensamiento en el uso de la lA en entornos educativos, influenciando políticas,
prácticas y futuros desarrollos.']
            ],
            'Eseñanza y aprendizaje' => [
                ['A1', 'Novato', 0, 3,  'Entiende lo básico de cómo la lA puede ser usada para el desarrollo profesional y la comunicación. - Comienza a interactuar con herramientas de lA para tareas simples como respuestas automáticas de correo
electrónico o análisis de datos básicos para la gestión del aula. - Conciencia del potencial de la lA en el desarrollo profesional y la red de contactos, pero con aplicación práctica
limitada.'],
                ['A2', 'Explorador', 4, 7, 'Explora activamente diferentes herramientas de lA para la comunicación y el desarrollo profesional. - Comienza a integrar la lA en tareas profesionales rutinarias, como el uso de lA para organizar reuniones o la
interpretación básica de datos. Participa en comunidades de aprendizaje profesional discutiendo sobre la lA en la educación, compartiendo
experiencias iniciales y aprendiendo de los compañeros.'],
                ['B1', 'Integrador', 8, 11, 'Usa regularmente herramientas de lA para una gama de actividades profesionales, incluido el análisis de datos
avanzado y la optimización de la comunicación.
- Integra la lA en planes de desarrollo profesional, buscando formación y recursos enfocados en lA.
- Colabora utilizando herramientas de lA, demostrando uso efectivo en proyectos de equipo o iniciativas colaborativas.'],
                ['B2', 'Experto', 12, 15, 'Navega expertamente una variedad de herramientas de lA para fines profesionales avanzados, como análisis
predictivos para el rendimiento estudiantil. Dirige sesiones de desarrollo profesional o talleres sobre lA para colegas, compartiendo experiencia y mejores
prácticas. - Utiliza innovadoramente la lA para el crecimiento personal y organizacional, contribuyendo a la comunidad educativa
más amplia a través de iniciativas mejoradas por IA.'],
                ['C1', 'Líder', 16, 18,  'Lidera la adopción de tecnologías de lA de vanguardia en entornos profesionales.
Da forma a políticas y prácticas organizacionales en torno al uso de la lA en el compromiso profesional. Orienta a otros en la integración de la lA en prácticas profesionales, impulsando el cambio y la innovación dentro de
la comunidad educativa.'],
                ['C2', 'Pionero', 19, 24, 'Avanza en el campo contribuyendo con ideas originales o investigación sobre el uso de la lA en el desarrollo
profesional y la comunicación. Lidera proyectos o iniciativas importantes que transforman el compromiso profesional a través de la lA a un nivel
sistémico. Es un experto reconocido y líder de pensamiento en el uso de la lA en entornos educativos, influenciando políticas,
prácticas y futuros desarrollos.']
            ],
            'Evaluación' => [
                ['A1', 'Novato', 0, 3, 'Entiende lo básico de cómo la lA puede ser usada para el desarrollo profesional y la comunicación. - Comienza a interactuar con herramientas de lA para tareas simples como respuestas automáticas de correo
electrónico o análisis de datos básicos para la gestión del aula. - Conciencia del potencial de la lA en el desarrollo profesional y la red de contactos, pero con aplicación práctica
limitada.'],
                ['A2', 'Explorador', 4, 7, 'Explora activamente diferentes herramientas de lA para la comunicación y el desarrollo profesional. - Comienza a integrar la lA en tareas profesionales rutinarias, como el uso de lA para organizar reuniones o la
interpretación básica de datos. Participa en comunidades de aprendizaje profesional discutiendo sobre la lA en la educación, compartiendo
experiencias iniciales y aprendiendo de los compañeros.'],
                ['B1', 'Integrador', 8, 11, 'Usa regularmente herramientas de lA para una gama de actividades profesionales, incluido el análisis de datos
avanzado y la optimización de la comunicación.
- Integra la lA en planes de desarrollo profesional, buscando formación y recursos enfocados en lA.
- Colabora utilizando herramientas de lA, demostrando uso efectivo en proyectos de equipo o iniciativas colaborativas.'],
                ['B2', 'Experto', 12, 15, 'Navega expertamente una variedad de herramientas de lA para fines profesionales avanzados, como análisis
predictivos para el rendimiento estudiantil. Dirige sesiones de desarrollo profesional o talleres sobre lA para colegas, compartiendo experiencia y mejores
prácticas. - Utiliza innovadoramente la lA para el crecimiento personal y organizacional, contribuyendo a la comunidad educativa
más amplia a través de iniciativas mejoradas por IA.'],
                ['C1', 'Líder', 16, 18,  'Lidera la adopción de tecnologías de lA de vanguardia en entornos profesionales.
Da forma a políticas y prácticas organizacionales en torno al uso de la lA en el compromiso profesional. Orienta a otros en la integración de la lA en prácticas profesionales, impulsando el cambio y la innovación dentro de
la comunidad educativa.'],
                ['C2', 'Pionero', 19, 24, 'Avanza en el campo contribuyendo con ideas originales o investigación sobre el uso de la lA en el desarrollo
profesional y la comunicación. Lidera proyectos o iniciativas importantes que transforman el compromiso profesional a través de la lA a un nivel
sistémico. Es un experto reconocido y líder de pensamiento en el uso de la lA en entornos educativos, influenciando políticas,
prácticas y futuros desarrollos.']
            ],
            'Formacion de estudiantes' => [
                ['A1', 'Novato', 0, 3, 'Entiende lo básico de cómo la lA puede ser usada para el desarrollo profesional y la comunicación. - Comienza a interactuar con herramientas de lA para tareas simples como respuestas automáticas de correo
electrónico o análisis de datos básicos para la gestión del aula. - Conciencia del potencial de la lA en el desarrollo profesional y la red de contactos, pero con aplicación práctica
limitada.'],
                ['A2', 'Explorador', 4, 7,'Explora activamente diferentes herramientas de lA para la comunicación y el desarrollo profesional. - Comienza a integrar la lA en tareas profesionales rutinarias, como el uso de lA para organizar reuniones o la
interpretación básica de datos. Participa en comunidades de aprendizaje profesional discutiendo sobre la lA en la educación, compartiendo
experiencias iniciales y aprendiendo de los compañeros.'],
                ['B1', 'Integrador', 8, 11, 'Usa regularmente herramientas de lA para una gama de actividades profesionales, incluido el análisis de datos
avanzado y la optimización de la comunicación.
- Integra la lA en planes de desarrollo profesional, buscando formación y recursos enfocados en lA.
- Colabora utilizando herramientas de lA, demostrando uso efectivo en proyectos de equipo o iniciativas colaborativas.'],
                ['B2', 'Experto', 12, 15, 'Navega expertamente una variedad de herramientas de lA para fines profesionales avanzados, como análisis
predictivos para el rendimiento estudiantil. Dirige sesiones de desarrollo profesional o talleres sobre lA para colegas, compartiendo experiencia y mejores
prácticas. - Utiliza innovadoramente la lA para el crecimiento personal y organizacional, contribuyendo a la comunidad educativa
más amplia a través de iniciativas mejoradas por IA.'],
                ['C1', 'Líder', 16, 18,  'Lidera la adopción de tecnologías de lA de vanguardia en entornos profesionales.
Da forma a políticas y prácticas organizacionales en torno al uso de la lA en el compromiso profesional. Orienta a otros en la integración de la lA en prácticas profesionales, impulsando el cambio y la innovación dentro de
la comunidad educativa.'],
                ['C2', 'Pionero', 19, 24,'Avanza en el campo contribuyendo con ideas originales o investigación sobre el uso de la lA en el desarrollo
profesional y la comunicación. Lidera proyectos o iniciativas importantes que transforman el compromiso profesional a través de la lA a un nivel
sistémico. Es un experto reconocido y líder de pensamiento en el uso de la lA en entornos educativos, influenciando políticas,
prácticas y futuros desarrollos.']
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

        $this->command->info("Niveles de competencia por área para el test de Inteligencia Artificial cargados correctamente.");
    }
} 