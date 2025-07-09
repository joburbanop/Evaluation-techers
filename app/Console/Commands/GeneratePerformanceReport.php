<?php

namespace App\Console\Commands;

use App\Models\EvaluacionPorArea;
use App\Models\EvaluacionPorInstitucion;
use App\Models\EvaluacionPorProfesor;
use App\Models\Report;
use App\Models\TestAssignment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeneratePerformanceReport extends Command
{
    protected $signature = 'reports:performance {--days=30 : Días para analizar}';
    protected $description = 'Generar reporte de rendimiento del sistema de reportes';

    public function handle()
    {
        // Verificar que el usuario que ejecuta el comando sea administrador
        if (!auth()->user() || !auth()->user()->hasRole('Administrador')) {
            $this->error('Solo los administradores pueden ejecutar este comando');
            return 1;
        }

        $days = $this->option('days');
        $startDate = Carbon::now()->subDays($days);

        $this->info("Generando reporte de rendimiento (últimos {$days} días)...");

        // Estadísticas de reportes generados
        $reportsGenerated = Report::where('created_at', '>=', $startDate)->count();
        $reportsCompleted = Report::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->count();
        $reportsFailed = Report::where('status', 'failed')
            ->where('created_at', '>=', $startDate)
            ->count();

        // Tiempo promedio de generación
        $avgGenerationTime = Report::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('generated_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(SECOND, created_at, generated_at)) as avg_time')
            ->first();

        // Estadísticas de evaluaciones
        $totalEvaluaciones = TestAssignment::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->count();

        $totalUsuarios = TestAssignment::where('status', 'completed')
            ->where('created_at', '>=', $startDate)
            ->distinct('user_id')
            ->count();

        // Rendimiento de vistas
        $this->testViewPerformance();

        // Mostrar resultados
        $this->displayResults([
            'reports_generated' => $reportsGenerated,
            'reports_completed' => $reportsCompleted,
            'reports_failed' => $reportsFailed,
            'success_rate' => $reportsGenerated > 0 ? round(($reportsCompleted / $reportsGenerated) * 100, 2) : 0,
            'avg_generation_time' => $avgGenerationTime->avg_time ?? 0,
            'total_evaluaciones' => $totalEvaluaciones,
            'total_usuarios' => $totalUsuarios,
        ]);
    }

    private function testViewPerformance()
    {
        $this->info("\nProbando rendimiento de vistas...");

        // Test vista por área
        $startTime = microtime(true);
        $areaCount = EvaluacionPorArea::count();
        $areaTime = microtime(true) - $startTime;

        // Test vista por institución
        $startTime = microtime(true);
        $institutionCount = EvaluacionPorInstitucion::count();
        $institutionTime = microtime(true) - $startTime;

        // Test vista por profesor
        $startTime = microtime(true);
        $professorCount = EvaluacionPorProfesor::count();
        $professorTime = microtime(true) - $startTime;

        $this->table(
            ['Vista', 'Registros', 'Tiempo (ms)'],
            [
                ['Evaluaciones por Área', $areaCount, round($areaTime * 1000, 2)],
                ['Evaluaciones por Institución', $institutionCount, round($institutionTime * 1000, 2)],
                ['Evaluaciones por Profesor', $professorCount, round($professorTime * 1000, 2)],
            ]
        );
    }

    private function displayResults($data)
    {
        $this->info("\n=== REPORTE DE RENDIMIENTO ===");
        
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Reportes Generados', $data['reports_generated']],
                ['Reportes Completados', $data['reports_completed']],
                ['Reportes Fallidos', $data['reports_failed']],
                ['Tasa de Éxito (%)', $data['success_rate'] . '%'],
                ['Tiempo Promedio (seg)', round($data['avg_generation_time'], 2)],
                ['Total Evaluaciones', $data['total_evaluaciones']],
                ['Total Usuarios', $data['total_usuarios']],
            ]
        );

        // Recomendaciones
        $this->info("\n=== RECOMENDACIONES ===");
        
        if ($data['success_rate'] < 90) {
            $this->warn("⚠️  La tasa de éxito es baja. Revisar logs de errores.");
        }
        
        if ($data['avg_generation_time'] > 60) {
            $this->warn("⚠️  El tiempo de generación es alto. Considerar optimizaciones.");
        }
        
        if ($data['reports_generated'] > 0) {
            $this->info("✅ Sistema funcionando correctamente.");
        }
    }
} 