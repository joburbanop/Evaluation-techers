<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class UpdateQuestionsOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'questions:update-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el orden de las preguntas existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Actualizando orden de preguntas...');

        // Obtener todos los tests
        $tests = DB::table('tests')->get();

        foreach ($tests as $test) {
            $this->info("Procesando test ID: {$test->id}");
            
            // Obtener preguntas del test y actualizar su orden
            $questions = Question::where('test_id', $test->id)->get();
            
            foreach ($questions as $index => $question) {
                $question->order = $index + 1;
                $question->save();
                $this->info("Pregunta ID: {$question->id} actualizada con orden: " . ($index + 1));
            }
        }

        $this->info('¡Proceso completado!');
    }
}
