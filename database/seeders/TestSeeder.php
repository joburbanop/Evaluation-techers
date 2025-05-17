<?php
namespace Database\Seeders;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tests')->insert([
            [
                'id' => 1,
                'name' => 'Evaluación Docente 2025',
                'description' => 'Evaluación de desempeño docente para el año 2025',
                'category' => 'evaluacion_docente',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        // Competencia Pedagógica (10 preguntas)
        $pedagogicalTest = \App\Models\Test::create([
            'name' => 'Competencia Pedagógica',
            'category' => 'competencia_pedagogica',
            'description' => 'Evaluación sobre las estrategias pedagógicas y teorías del aprendizaje.',
        ]);

        $pedagogicalQuestions = [
            '¿Qué enfoque pedagógico se basa en el aprendizaje colaborativo?',
            '¿Cómo se define el aprendizaje significativo en el contexto educativo?',
            '¿Cuál es la diferencia entre aprendizaje activo y pasivo?',
            '¿Qué es la educación inclusiva y cómo se implementa?',
            '¿Qué características definen a un docente reflexivo?',
            '¿Cuál es el objetivo principal de la evaluación formativa?',
            '¿Qué papel juega la motivación en el aprendizaje de los estudiantes?',
            '¿Qué metodologías pueden usarse para enseñar en entornos virtuales?',
            '¿Cómo influye el uso de las TIC en el aprendizaje pedagógico?',
            '¿Qué modelos pedagógicos se utilizan para desarrollar habilidades críticas?'
        ];

        $pedagogicalOptions = [
            ['Constructivismo', 'Conductismo', 'Cognitivismo', 'Conectivismo'],
            ['Cuando los estudiantes memorizan contenidos', 'Relacionando nuevos conocimientos con los previos', 'Solo cuando se utiliza tecnología', 'Cuando se enseñan hechos aislados'],
            ['El aprendizaje activo implica solo escuchar', 'El aprendizaje activo involucra participación directa', 'El aprendizaje pasivo no requiere concentración', 'Ninguna de las anteriores'],
            ['La educación inclusiva se enfoca solo en estudiantes con discapacidades', 'La educación inclusiva busca que todos los estudiantes participen activamente', 'La educación inclusiva solo se aplica en clases de arte', 'Ninguna de las anteriores'],
            ['Siempre sigue el mismo plan de enseñanza', 'Evalúa constantemente su práctica pedagógica', 'No se preocupa por la retroalimentación', 'Es el experto que nunca necesita mejorar'],
            ['Determinar la nota final del estudiante', 'Medir el rendimiento en un examen', 'Proveer retroalimentación para mejorar el aprendizaje', 'Ninguna de las anteriores'],
            ['La motivación es irrelevante para el aprendizaje', 'La motivación aumenta el interés y la participación', 'La motivación es importante solo para los estudiantes de excelencia', 'Ninguna de las anteriores'],
            ['Exposición única de conceptos', 'Aprendizaje basado en proyectos, aprendizaje colaborativo y gamificación', 'Ninguna de las anteriores', 'Solo lecturas sin interacción'],
            ['Las TIC no tienen influencia alguna', 'Las TIC permiten una enseñanza más interactiva y accesible', 'Las TIC solo sirven para entretenimiento', 'Ninguna de las anteriores'],
            ['El aprendizaje basado en problemas (ABP)', 'El aprendizaje memorístico', 'El aprendizaje cooperativo', 'Ninguna de las anteriores']
        ];

        foreach ($pedagogicalQuestions as $index => $questionText) {
            $question = $pedagogicalTest->questions()->create([
                'question' => $questionText,
            ]);

            // Opciones de respuesta
            foreach ($pedagogicalOptions[$index] as $key => $option) {
                Option::create([
                    'question_id' => $question->id,
                    'option' => $option,
                    'is_correct' => $key === 0, // Establecer la respuesta correcta
                ]);
            }
        }

        // Competencia Comunicativa (15 preguntas)
        $communicativeTest = \App\Models\Test::create([
            'name' => 'Competencia Comunicativa',
            'category' => 'competencia_comunicativa',
            'description' => 'Evaluación sobre las habilidades comunicativas en el entorno educativo.',
        ]);

        $communicativeQuestions = [
            '¿Qué elemento es clave para una comunicación efectiva?',
            '¿Qué se entiende por comunicación asertiva?',
            '¿Cuál es la principal diferencia entre comunicación verbal y no verbal?',
            '¿Cómo se puede mejorar la escucha activa en el aula?',
            '¿Qué técnicas se utilizan para fomentar la participación estudiantil en clase?',
            '¿Qué papel juega el feedback en la comunicación educativa?',
            '¿Qué estrategias se emplean para resolver conflictos en el aula?',
            '¿Cómo puede la empatía mejorar la comunicación entre docente y estudiante?',
            '¿Cuáles son los beneficios de la comunicación interpersonal en el aprendizaje?',
            '¿Por qué es importante la retroalimentación en la educación?',
            '¿Cómo afectan las barreras lingüísticas a la comunicación en el aula?',
            '¿Qué es la comunicación intercultural y por qué es relevante en el aula?',
            '¿Qué importancia tiene la claridad y la precisión en las instrucciones dadas a los estudiantes?',
            '¿Qué medios de comunicación digital pueden usarse para mejorar la enseñanza?',
            '¿Cómo se puede utilizar la comunicación no verbal en la enseñanza de conceptos complejos?'
        ];

        $communicativeOptions = [
            ['La claridad del mensaje', 'El uso de jergas', 'Hablar sin escuchar', 'Solo el tono de voz'],
            ['Expresar lo que se siente de manera honesta y respetuosa', 'Gritar para hacerse escuchar', 'No expresar nada', 'Hablar solo cuando se es invitado a hacerlo'],
            ['La comunicación verbal se usa solo en el aula', 'La comunicación verbal es hablada, la no verbal es gestual o física', 'No hay diferencia', 'Ninguna de las anteriores'],
            ['Ignorando al interlocutor', 'Escuchando sin interrumpir y proporcionando retroalimentación', 'No mirando a la persona', 'Hablando mientras se escucha'],
            ['El uso de castigos', 'Preguntas abiertas, discusiones y actividades colaborativas', 'Ignorar a los estudiantes que no participan', 'Solo exámenes escritos'],
            ['El feedback no tiene importancia', 'El feedback proporciona información sobre el desempeño para mejorar', 'El feedback es solo para calificar', 'Ninguna de las anteriores'],
            ['Ignorar el conflicto', 'Mediación, diálogo y negociación', 'Usar la autoridad sin escuchar', 'No intervenir'],
            ['Ignorando los sentimientos del estudiante', 'Entendiendo y compartiendo las emociones del estudiante', 'Hablando sin escuchar', 'Ninguna de las anteriores'],
            ['Fomenta la comprensión mutua y el desarrollo emocional', 'No tiene importancia', 'Solo mejora la enseñanza del docente', 'Solo aumenta el estrés'],
            ['Porque ayuda a los estudiantes a mejorar sus habilidades', 'No tiene importancia', 'Solo es para calificar', 'Ninguna de las anteriores'],
            ['Pueden dificultar la comprensión y el aprendizaje', 'Mejoran la enseñanza', 'No tienen efecto', 'Ninguna de las anteriores'],
            ['La comunicación entre personas de diferentes culturas, importante para promover el respeto y entendimiento', 'Comunicación con los padres', 'Comunicación solo en el aula', 'Ninguna de las anteriores'],
            ['Es crucial para evitar malentendidos y confusión', 'No tiene importancia', 'Solo es relevante para la evaluación', 'Ninguna de las anteriores'],
            ['Correo electrónico, plataformas de aprendizaje, videos educativos', 'Solo redes sociales', 'Ninguno', 'Solo videollamadas'],
            ['Mediante gestos, expresiones y ejemplos visuales', 'No se debe usar comunicación no verbal', 'Solo usando palabras', 'Ninguna de las anteriores']
        ];

        foreach ($communicativeQuestions as $index => $questionText) {
            $question = $communicativeTest->questions()->create([
                'question' => $questionText,
            ]);

            // Opciones de respuesta
            foreach ($communicativeOptions[$index] as $key => $option) {
                Option::create([
                    'question_id' => $question->id,
                    'option' => $option,
                    'is_correct' => $key === 0, // Establecer la respuesta correcta
                ]);
            }
        }
    }
}
