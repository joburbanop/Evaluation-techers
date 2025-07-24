<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReportService;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GenerateProfesoresCompletadosReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:profesores-completados 
                            {--date_from= : Fecha de inicio (YYYY-MM-DD)}
                            {--date_to= : Fecha de fin (YYYY-MM-DD)}
                            {--filtro=todos : Filtro (todos, completados, pendientes)}
                            {--user_id=1 : ID del usuario que genera el reporte}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera reporte de participación en evaluación de competencias de manera optimizada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Aumentar límites de memoria para este proceso
        ini_set('memory_limit', '1G');
        set_time_limit(600); // 10 minutos
        
        $this->info('Iniciando generación de reporte de profesores completados...');
        
        try {
            // Crear registro del reporte
            $parameters = [
                'date_from' => $this->option('date_from'),
                'date_to' => $this->option('date_to'),
                'filtro' => $this->option('filtro'),
            ];
            
            $report = Report::create([
                'name' => "Reporte de Participación en Evaluación de Competencias",
                'type' => 'profesores_completados',
                'entity_id' => null,
                'entity_type' => null,
                'generated_by' => $this->option('user_id'),
                'parameters' => $parameters,
                'status' => 'generating',
            ]);
            
            $this->info("Reporte creado con ID: {$report->id}");
            
            // Generar reporte usando el servicio optimizado
            $reportService = new ReportService();
            
            // Simular autenticación para el comando
            $user = \App\Models\User::find($this->option('user_id'));
            if (!$user) {
                throw new \Exception('Usuario no encontrado');
            }
            
            // Establecer el usuario autenticado para el comando
            auth()->login($user);
            
            $data = $reportService->getProfesoresCompletadosData($parameters);
            
            $this->info("Datos procesados: {$data['total_profesores']} profesores");
            
            // Generar PDF
            $this->info('Generando PDF...');
            $pdf = $reportService->generateProfesoresCompletadosPDF($data);
            
            $fileName = "reporte_participacion_evaluacion_{$report->id}_" . now()->format('Y-m-d_H-i-s') . '.pdf';
            $filePath = "reports/profesores_completados/{$fileName}";
            
            Storage::put($filePath, $pdf->output());
            
            // Actualizar reporte
            $report->update([
                'file_path' => $filePath,
                'file_size' => Storage::size($filePath),
                'status' => 'completed',
                'generated_at' => now(),
                'expires_at' => now()->addDays(30),
            ]);
            
            $this->info("✅ Reporte generado exitosamente!");
            $this->info("📊 Estadísticas:");
            $this->info("   - Total profesores: {$data['total_profesores']}");
            $this->info("   - Profesores completados: {$data['profesores_completados']}");
            $this->info("   - Profesores pendientes: {$data['profesores_pendientes']}");
            $this->info("   - Total evaluaciones completadas: {$data['total_tests_completados']}");
            $this->info("📁 Archivo guardado en: {$filePath}");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("❌ Error al generar reporte: " . $e->getMessage());
            Log::error('Error en comando GenerateProfesoresCompletadosReport', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (isset($report)) {
                $report->update([
                    'status' => 'failed',
                    'generated_at' => now(),
                ]);
            }
            
            return 1;
        }
    }
}
