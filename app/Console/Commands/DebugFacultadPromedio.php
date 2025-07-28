<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Facultad;
use App\Models\Programa;
use App\Models\User;
use App\Models\EvaluacionPorArea;

class DebugFacultadPromedio extends Command
{
    protected $signature = 'debug:facultad-promedio {facultad_id}';
    protected $description = 'Debug el cálculo del promedio de facultad';

    public function handle()
    {
        $facultadId = $this->argument('facultad_id');
        
        $facultad = Facultad::find($facultadId);
        if (!$facultad) {
            $this->error("Facultad con ID {$facultadId} no encontrada");
            return;
        }
        
        $this->info("Facultad: {$facultad->nombre}");
        
        // Obtener todos los programas de la facultad
        $programas = Programa::where('facultad_id', $facultadId)->get();
        $this->info("Total programas: {$programas->count()}");
        
        // Obtener todas las evaluaciones de la facultad
        $evaluacionesFacultad = EvaluacionPorArea::byFacultad($facultadId)->get();
        $this->info("Total evaluaciones en la facultad: {$evaluacionesFacultad->count()}");
        
        // Calcular promedio de la facultad
        $promedioFacultad = $evaluacionesFacultad->avg('score') ?? 0;
        $this->info("Promedio de la facultad: {$promedioFacultad}");
        
        // Analizar por programa
        $this->info("\nAnálisis por programa:");
        foreach ($programas as $programa) {
            $evaluacionesPrograma = $evaluacionesFacultad->where('programa_id', $programa->id);
            $promedioPrograma = $evaluacionesPrograma->avg('score') ?? 0;
            $countEvaluaciones = $evaluacionesPrograma->count();
            
            $this->line("- Programa: {$programa->nombre}");
            $this->line("  Evaluaciones: {$countEvaluaciones}");
            $this->line("  Promedio: {$promedioPrograma}");
            $this->line("");
        }
        
        // Verificar si solo hay datos de un programa
        $programasConDatos = $evaluacionesFacultad->unique('programa_id')->count();
        $this->info("Programas con datos: {$programasConDatos}");
        
        if ($programasConDatos == 1) {
            $this->warn("⚠️  Solo hay datos de un programa. Esto explica por qué el promedio de facultad y programa son iguales.");
        }
        
        // Mostrar algunos registros de ejemplo
        $this->info("\nPrimeros 5 registros de evaluaciones:");
        foreach ($evaluacionesFacultad->take(5) as $evaluacion) {
            $this->line("- ID: {$evaluacion->id}, Programa: {$evaluacion->programa_id}, Score: {$evaluacion->score}");
        }
    }
} 