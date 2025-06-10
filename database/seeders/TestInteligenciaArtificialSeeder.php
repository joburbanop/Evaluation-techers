<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Question;
use App\Models\Option;

class TestInteligenciaArtificialSeeder extends Seeder
{
    public function run()
    {
        $test = Test::create([
            'name' => 'Test Inteligencia Artificial',
            'description' => 'Evaluación de competencias sobre inteligencia artificial en el contexto educativo',
            'is_active' => true,
        ]);

        $question1 = Question::create([
            'test_id' => $test->id,
            'question' => "Contribuyo activamente al desarrollo del campo educativo mediante investigaciones, liderazgo de proyectos innovadores con IA y la generación de ideas que impactan políticas y prácticas institucionales.",
            'area_id' => 1,
            'factor_id' => 1,
            'order' => 1,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question1->id,
            'option' => "Siempre",
            'score' => 4,
        ]);

        $question2 = Question::create([
            'test_id' => $test->id,
            'question' => "Utilizo herramientas de inteligencia artificial (IA) en su labor docente y para mejorar la comunicación educativa.",
            'area_id' => 1,
            'factor_id' => 1,
            'order' => 2,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question2->id,
            'option' => "Siempre",
            'score' => 4,
        ]);

        $question3 = Question::create([
            'test_id' => $test->id,
            'question' => "Integro herramientas de inteligencia artificial (IA) en tareas rutinarias y participo en espacios colaborativos.",
            'area_id' => 1,
            'factor_id' => 1,
            'order' => 3,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question3->id,
            'option' => "Siempre",
            'score' => 4,
        ]);

        $question4 = Question::create([
            'test_id' => $test->id,
            'question' => "Aplica herramientas de inteligencia artificial (IA) de forma independiente e innovadora para fortalecer su crecimiento profesional,",
            'area_id' => 1,
            'factor_id' => 1,
            'order' => 4,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question4->id,
            'option' => "Siempre",
            'score' => 4,
        ]);

        $question5 = Question::create([
            'test_id' => $test->id,
            'question' => "Asume un rol activo en la implementación de tecnologías de inteligencia artificial (IA), orienta a sus colegas en su uso y participa en la definición de políticas educativas relacionadas con su integración.",
            'area_id' => 1,
            'factor_id' => 1,
            'order' => 5,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question5->id,
            'option' => "Siempre",
            'score' => 4,
        ]);

        $question6 = Question::create([
            'test_id' => $test->id,
            'question' => "Contribuyo activamente al desarrollo del campo educativo mediante investigaciones, liderazgo de proyectos innovadores con IA y la generación de ideas que impactan políticas y prácticas institucionales.",
            'area_id' => 1,
            'factor_id' => 1,
            'order' => 6,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question6->id,
            'option' => "Siempre",
            'score' => 4,
        ]);


        $question7 = Question::create([
            'test_id' => $test->id,
            'question' => "Exploro herramientas de inteligencia artificial (IA) para la creación básica de contenidos digitales y empiezo a familiarizarme con su potencial educativo.",
            'area_id' => 2,
            'factor_id' => 2,
            'order' => 7,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question7->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question7->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question8 = Question::create([
            'test_id' => $test->id,
            'question' => "Durante la planificación de clases, integro recursos con IA que facilitan el aprendizaje activo.",
            'area_id' => 2,
            'factor_id' => 2,
            'order' => 8,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question8->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question8->id,
            'option' => "Siempre",
            'score' => 4,
        ]);




        $question9 = Question::create([
            'test_id' => $test->id,
            'question' => "Modifico y creo recursos educativos con el apoyo de herramientas de IA, evalúo su pertinencia pedagógica y colaboro con colegas compartiendo materiales y experiencias.",
            'area_id' => 2,
            'factor_id' => 2,
            'order' => 9,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question9->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question9->id,
            'option' => "Siempre",
            'score' => 4,
        ]);




        $question10 = Question::create([
            'test_id' => $test->id,
            'question' => "Participo en el diseño, aplicación y socialización de recursos digitales mejorados con inteligencia artificial (IA) en contextos institucionales donde se promueve la innovación educativa.",
            'area_id' => 2,
            'factor_id' => 2,
            'order' => 10,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question10->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question10->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question11 = Question::create([
            'test_id' => $test->id,
            'question' => "En contextos institucionales donde se promueve la innovación educativa, ¿cuál ha sido su nivel de participación en el diseño, aplicación y socialización de recursos digitales mejorados con inteligencia artificial (IA)?",
            'area_id' => 2,
            'factor_id' => 2,
            'order' => 11,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question11->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question11->id,
            'option' => "Siempre",
            'score' => 4,
        ]);


        $question12 = Question::create([
            'test_id' => $test->id,
            'question' => "Es reconocido(a) en su institución o comunidad educativa por su liderazgo en el uso de inteligencia artificial (IA) para innovar en la creación y gestión de recursos digitales.",
            'area_id' => 2,
            'factor_id' => 2,
            'order' => 12,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question12->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question12->id,
            'option' => "Siempre",
            'score' => 4,
        ]);




        $question13 = Question::create([
            'test_id' => $test->id,
            'question' => "Exploro y aplico la inteligencia artificial (IA) en contextos educativos.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 13,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question13->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question13->id,
            'option' => "Siempre",
            'score' => 4,
        ]);




        $question14 = Question::create([
            'test_id' => $test->id,
            'question' => "Inicia activamente su proceso de exploración y aplicación de la inteligencia artificial (IA) en contextos educativos.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 14,
        ]);

        Option::create([
            'question_id' =>$question14->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' =>$question14->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' =>$question14->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' =>$question14->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' =>$question14->id,
            'option' => "Siempre",
            'score' => 4,
        ]);





        $question15 = Question::create([
            'test_id' => $test->id,
            'question' => "Personalizo la enseñanza con apoyo de inteligencia artificial (IA).",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 15,
        ]);

        Option::create([
            'question_id' =>$question15->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question15->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question15->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question16 = Question::create([
            'test_id' => $test->id,
            'question' => "Reconozco la importancia del uso de la inteligencia artificial (IA) en mi práctica pedagógica para personalizar el aprendizaje, innovar en metodologías y contribuir a la formación de otros docentes. ",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 16,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question16->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question16->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question17 = Question::create([
            'test_id' => $test->id,
            'question' => "Integro la inteligencia artificial (IA) en los programas educativos, actuando como referente para otros docentes y liderando procesos de transformación pedagógica institucional.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 17,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question17->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question17->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question18 = Question::create([
            'test_id' => $test->id,
            'question' => "Utilizo de manera innovadora la inteligencia artificial (IA) en la educación, contribuyendo con investigaciones, promoviendo cambios en políticas educativas y participando activamente en espacios de influencia nacional o internacional.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 18,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question18->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question18->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question19 = Question::create([
            'test_id' => $test->id,
            'question' => "Exploro de forma inicial el uso de la inteligencia artificial en la evaluación, utilizando herramientas simples como la calificación automática o la retroalimentación básica, y comienzo a comprender los datos que generan.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 19,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question19->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question19->id,
            'option' => "Siempre",
            'score' => 4,
        ]);




        $question20 = Question::create([
            'test_id' => $test->id,
            'question' => "Integro algunas herramientas de inteligencia artificial en mis evaluaciones, empiezo a interpretar los datos que generan para comprender el rendimiento de los estudiantes y utilizo esa información para ofrecer retroalimentación básica.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 20,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question20->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question20->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question21 = Question::create([
            'test_id' => $test->id,
            'question' => "Utilizo regularmente herramientas de inteligencia artificial para mejorar la evaluación, ajusto mi enseñanza con base en los datos generados y comparto mis experiencias con otros docentes.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 21,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question21->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question21->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question22 = Question::create([
            'test_id' => $test->id,
            'question' => "Diseño y aplico estrategias innovadoras de evaluación apoyadas en inteligencia artificial, adaptándolas a las necesidades individuales del estudiantado, y participo activamente en espacios de formación o colaboración para mejorar estas prácticas.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 22,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question22->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question22->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question23 = Question::create([
            'test_id' => $test->id,
            'question' => "Lidero la integración estratégica de la inteligencia artificial en los procesos de evaluación de mi institución, facilito la formación de colegas y realizo análisis para ajustar políticas internas que mejoren la equidad educativa.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 23,
        ]);

        Option::create([
            'question_id' => $question23->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question23->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question23->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question23->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question23->id,
            'option' => "Siempre",
            'score' => 4,
        ]);




        $question24 = Question::create([
            'test_id' => $test->id,
            'question' => "Diseño e implemento políticas y proyectos innovadores de evaluación con inteligencia artificial a nivel nacional o internacional, liderando investigaciones y representando a mi país en foros especializados.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 24,
        ]);

        Option::create([
            'question_id' => $question24->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question24->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question24->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question24->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question24->id,
            'option' => "Siempre",
            'score' => 4,
        ]);




        $question25 = Question::create([
            'test_id' => $test->id,
            'question' => "Me informo y exploro sobre la inteligencia artificial en educación, aunque aún no la aplico ni participo activamente en procesos relacionados.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 25,
        ]);

        Option::create([
            'question_id' => $question25->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question25->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question25->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question25->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question25->id,
            'option' => "Siempre",
            'score' => 4,
        ]);






        $question26 = Question::create([
            'test_id' => $test->id,
            'question' => "Pruebo y utilizo algunas herramientas básicas de inteligencia artificial en mi enseñanza, haciendo ajustes según la retroalimentación de mis estudiantes.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 26,
        ]);

        Option::create([
            'question_id' => $question26->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question26->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question26->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question26->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question26->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question27 = Question::create([
            'test_id' => $test->id,
            'question' => "Exploro y aprendo sobre la inteligencia artificial en educación, para aplicarla en personalizar el aprendizaje, usar datos, recibir retroalimentación, colaborar con colegas o atender la diversidad.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 27,
        ]);

        Option::create([
            'question_id' => $question27->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question27->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question27->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question27->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question27->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question28 = Question::create([
            'test_id' => $test->id,
            'question' => "Integro herramientas avanzadas de inteligencia artificial para innovar en mis prácticas pedagógicas, liderar talleres y rediseñar estrategias educativas centradas en las necesidades de mis estudiantes.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 28,
        ]);

        Option::create([
            'question_id' => $question28->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question28->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question28->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question28->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question28->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question29 = Question::create([
            'test_id' => $test->id,
            'question' => "Integro y lidero estratégicamente la implementación de la inteligencia artificial en el currículo y la política institucional para empoderar a los estudiantes y promover su uso efectivo entre colegas.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 29,
        ]);

        Option::create([
            'question_id' => $question29->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question29->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question29->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question29->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question29->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question30 = Question::create([
            'test_id' => $test->id,
            'question' => "Contribuyo regularmente con investigaciones, liderazgo en proyectos de alto impacto y en la formulación de políticas nacionales e internacionales para transformar la educación mediante la inteligencia artificial y empoderar a los estudiantes.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 30,
        ]);

        Option::create([
            'question_id' => $question30->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question30->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question30->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question30->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question30->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



         $question31 = Question::create([
            'test_id' => $test->id,
            'question' => "Conozco de forma general el papel de la inteligencia artificial en la competencia digital y estoy comenzando a explorar cómo integrar herramientas básicas de IA para fomentar la alfabetización digital en mis estudiantes.",
            'area_id' => 3,
            'factor_id' => 3,
            'order' => 31,
        ]);

        Option::create([
            'question_id' => $question31->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question31->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question31->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question31->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question31->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question32 = Question::create([
            'test_id' => $test->id,
            'question' => "Integro herramientas básicas y conceptos fundamentales de IA para apoyar la alfabetización digital de mis estudiantes, ajustando mi enseñanza según su experiencia y explicando la relevancia de las habilidades digitales en el mundo actual.",
            'area_id' => 4,
            'factor_id' => 4,
            'order' => 32,
        ]);

        Option::create([
            'question_id' => $question32->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question32->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question32->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question32->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question32->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question33 = Question::create([
            'test_id' => $test->id,
            'question' => "Uso datos de IA para adaptar la enseñanza, desarrollo proyectos colaborativos con IA, promuevo habilidades digitales y evalúo su impacto, integrando la IA regularmente en mis clases.",
            'area_id' => 4,
            'factor_id' => 4,
            'order' => 33,
        ]);

        Option::create([
            'question_id' => $question33->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question33->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question33->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question33->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question33->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question34 = Question::create([
            'test_id' => $test->id,
            'question' => "Aplico herramientas avanzadas de IA, diseño currículos innovadores, formo a colegas, promuevo el pensamiento crítico sobre IA y la incluyo en mis clases.",
            'area_id' => 4,
            'factor_id' => 4,
            'order' => 34,
        ]);

        Option::create([
            'question_id' => $question34->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question34->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question34->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question34->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question34->id,
            'option' => "Siempre",
            'score' => 4,
        ]);


        $question35 = Question::create([
            'test_id' => $test->id,
            'question' => "Defino estrategias y políticas educativas que integran IA, mentoreo y lidero procesos de formación docente, lidero programas estratégicos de integración de IA, desarrollo indicadores institucionales para evaluar su impacto y impulso una cultura institucional basada en el uso efectivo de la IA.",
            'area_id' => 4,
            'factor_id' => 4,
            'order' => 35,
        ]);

        Option::create([
            'question_id' => $question35->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question35->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question35->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question35->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question35->id,
            'option' => "Siempre",
            'score' => 4,
        ]);



        $question36 = Question::create([
            'test_id' => $test->id,
            'question' => "Desarrollo investigaciones originales y prácticas transformadoras con IA, impulso cambios significativos en políticas educativas, soy referente nacional e internacional en IA educativa, diseño estrategias pedagógicas innovadoras y lidero la transformación educativa con IA a gran escala.",
            'area_id' => 4,
            'factor_id' => 4,
            'order' => 36,
        ]);

        Option::create([
            'question_id' => $question36->id,
            'option' => "Nunca",
            'score' => 0,
        ]);

        Option::create([
            'question_id' => $question36->id,
            'option' => "Rara vez",
            'score' => 1,
        ]);

        Option::create([
            'question_id' => $question36->id,
            'option' => "Algunas veces",
            'score' => 2,
        ]);
         Option::create([
            'question_id' => $question36->id,
            'option' => "A Menudo",
            'score' => 3,
        ]);

        Option::create([
            'question_id' => $question36->id,
            'option' => "Siempre",
            'score' => 4,
        ]);






    }
}
