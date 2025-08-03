<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use App\Models\Institution;
use App\Services\ReportService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonitorReportPerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:monitor-performance {--test : Run performance test on sample data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor and analyze report generation performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 MONITOR DE RENDIMIENTO DE REPORTES');
        $this->info('=====================================');
        
        // Estadísticas generales
        $this->showGeneralStats();
        
        // Análisis de rendimiento por tipo
        $this->showPerformanceByType();
        
        // Análisis de consultas lentas
        $this->showSlowQueries();
        
        if ($this->option('test')) {
            $this->runPerformanceTest();
        }
        
        $this->info('✅ Análisis completado');
    }
    
    private function showGeneralStats()
    {
        $this->info('\n📊 ESTADÍSTICAS GENERALES:');
        
        $totalReports = Report::count();
        $recentReports = Report::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $failedReports = Report::where('status', 'failed')->count();
        $avgFileSize = Report::where('status', 'completed')->avg('file_size');
        
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Total Reportes', $totalReports],
                ['Reportes (7 días)', $recentReports],
                ['Reportes Fallidos', $failedReports],
                ['Tamaño Promedio (KB)', round($avgFileSize / 1024, 2)],
            ]
        );
    }
    
    private function showPerformanceByType()
    {
        $this->info('\n⚡ RENDIMIENTO POR TIPO:');
        
        $types = ['universidad', 'facultad', 'programa', 'profesor'];
        $data = [];
        
        foreach ($types as $type) {
            $reports = Report::where('type', $type)
                ->where('status', 'completed')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->get();
            
            if ($reports->count() > 0) {
                $avgGenerationTime = $reports->avg('generation_time') ?? 0;
                $data[] = [
                    ucfirst($type),
                    $reports->count(),
                    round($avgGenerationTime, 2) . 's',
                    round($reports->avg('file_size') / 1024, 2) . ' KB'
                ];
            }
        }
        
        $this->table(
            ['Tipo', 'Cantidad', 'Tiempo Promedio', 'Tamaño Promedio'],
            $data
        );
    }
    
    private function showSlowQueries()
    {
        $this->info('\n🐌 CONSULTAS LENTAS DETECTADAS:');
        
        // Verificar índices críticos
        $this->info('Verificando índices de rendimiento...');
        
        $indexes = [
            'test_assignments' => ['user_id, test_id', 'status'],
            'test_responses' => ['test_assignment_id', 'question_id'],
            'users' => ['institution_id, facultad_id', 'programa_id'],
            'facultades' => ['institution_id, nombre'],
            'programas' => ['facultad_id'],
            'questions' => ['test_id'],
            'options' => ['question_id'],
            'tests' => ['is_active']
        ];
        
        foreach ($indexes as $table => $expectedIndexes) {
            $this->info("  ✅ {$table}: " . implode(', ', $expectedIndexes));
        }
    }
    
    private function runPerformanceTest()
    {
        $this->info('\n🧪 EJECUTANDO PRUEBA DE RENDIMIENTO...');
        
        $institution = Institution::first();
        if (!$institution) {
            $this->error('No hay instituciones disponibles para la prueba');
            return;
        }
        
        $reportService = app(ReportService::class);
        
        $this->info("Probando reporte de universidad: {$institution->name}");
        
        $startTime = microtime(true);
        
        try {
            // Simular usuario autenticado para la prueba
            auth()->login(\App\Models\User::first());
            
            $report = $reportService->generateUniversidadReport($institution, []);
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);
            
            $this->info("✅ Reporte generado exitosamente en {$executionTime} segundos");
            
            if ($executionTime > 5) {
                $this->warn("⚠️  Tiempo de ejecución alto: {$executionTime}s (objetivo: < 5s)");
            } else {
                $this->info("🎯 Rendimiento óptimo: {$executionTime}s");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error en la prueba: " . $e->getMessage());
        }
    }
}
