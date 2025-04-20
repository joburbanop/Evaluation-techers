<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Question;
use App\Models\Option;

class TestsSeeder extends Seeder
{
    public function run()
    {
        // Creamos 2 tests
        $test1 = Test::create([
            'name' => 'Test de Conocimientos Generales',
            'category' => 'General',
            'description' => 'Este es el primer test de ejemplo'
        ]);

        // Agregamos 20 preguntas
        for ($i = 1; $i <= 20; $i++) {
            $question = Question::create([
                'test_id' => $test1->id,
                'question' => "Pregunta $i del Test 1"
            ]);

            // Cada pregunta tendrá 4 opciones, con 1 correcta
            for ($j = 1; $j <= 4; $j++) {
                Option::create([
                    'question_id' => $question->id,
                    'option' => "Opción $j para la Pregunta $i",
                    'is_correct' => $j === 1, // Marca la primera como correcta
                ]);
            }
        }

        $test2 = Test::create([
            'name' => 'Test de Habilidades Específicas',
            'category' => 'Especializado',
            'description' => 'Este es el segundo test de ejemplo'
        ]);

        // Agregamos 20 preguntas
        for ($i = 1; $i <= 20; $i++) {
            $question = Question::create([
                'test_id' => $test2->id,
                'question' => "Pregunta $i del Test 2"
            ]);

            // 4 opciones, 1 correcta
            for ($j = 1; $j <= 4; $j++) {
                Option::create([
                    'question_id' => $question->id,
                    'option' => "Opción $j para la Pregunta $i",
                    'is_correct' => $j === 1,
                ]);
            }
        }
    }
}