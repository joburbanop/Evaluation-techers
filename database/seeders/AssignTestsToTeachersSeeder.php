<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\User;
use App\Models\TestAssignment;
use Database\Seeders\TestsSeeder;
use Database\Seeders\TestInteligenciaArtificialSeeder;

class AssignTestsToTeachersSeeder extends Seeder
{
    public function run()
    {
        // Primero ejecutamos los seeders de los tests si no existen
        $testCompetenciasDigitales = Test::where('name', 'Evaluación de Competencias Digitales Docentes')->first();
        if (!$testCompetenciasDigitales) {
            $this->call(TestsSeeder::class);
            $testCompetenciasDigitales = Test::where('name', 'Evaluación de Competencias Digitales Docentes')->first();
        }

        $testInteligenciaArtificial = Test::where('name', 'Test Inteligencia Artificial')->first();
        if (!$testInteligenciaArtificial) {
            $this->call(TestInteligenciaArtificialSeeder::class);
            $testInteligenciaArtificial = Test::where('name', 'Test Inteligencia Artificial')->first();
        }

        if (!$testCompetenciasDigitales || !$testInteligenciaArtificial) {
            $this->command->error('No se pudieron crear los tests necesarios');
            return;
        }

        // Obtener todos los usuarios que son docentes
        $teachers = User::role('Docente')->get();

        if ($teachers->isEmpty()) {
            $this->command->error('No se encontraron docentes en el sistema');
            return;
        }

        $this->command->info('Iniciando asignación de tests a ' . $teachers->count() . ' docentes...');

        foreach ($teachers as $teacher) {
            // Asignar test de Competencias Digitales
            TestAssignment::firstOrCreate(
                [
                    'test_id' => $testCompetenciasDigitales->id,
                    'user_id' => $teacher->id,
                ],
                [
                    'status' => 'pending'
                ]
            );

            // Asignar test de Inteligencia Artificial
            TestAssignment::firstOrCreate(
                [
                    'test_id' => $testInteligenciaArtificial->id,
                    'user_id' => $teacher->id,
                ],
                [
                    'status' => 'pending'
                ]
            );
        }

        $this->command->info('Tests asignados exitosamente a todos los docentes.');
    }
} 