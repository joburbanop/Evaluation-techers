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

    }
}
