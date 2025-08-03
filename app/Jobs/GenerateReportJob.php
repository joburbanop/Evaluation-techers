<?php

namespace App\Jobs;

use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutos
    public $tries = 3;

    protected $reportId;
    protected $type;
    protected $entityId;
    protected $entityType;
    protected $parameters;

    public function __construct($reportId, $type, $entityId, $entityType, $parameters = [])
    {
        $this->reportId = $reportId;
        $this->type = $type;
        $this->entityId = $entityId;
        $this->entityType = $entityType;
        $this->parameters = $parameters;
    }

    public function handle(ReportService $reportService)
    {
        try {
            Log::info("Iniciando generación de reporte", [
                'report_id' => $this->reportId,
                'type' => $this->type,
                'entity_id' => $this->entityId
            ]);

            $report = Report::find($this->reportId);
            
            if (!$report) {
                throw new \Exception("Reporte no encontrado");
            }

            // Actualizar estado a generando
            $report->update(['status' => 'generating']);

            // ✅ OPTIMIZACIÓN: Generar el reporte según el tipo con cache inteligente
            $entity = $this->entityType::find($this->entityId);
            
            switch ($this->type) {
                case 'universidad':
                    $reportService->generateUniversidadReport($entity, $this->parameters);
                    break;
                case 'facultad':
                    $reportService->generateFacultadReport($entity, $this->parameters);
                    break;
                case 'programa':
                    $reportService->generateProgramaReport($entity, $this->parameters);
                    break;
                case 'profesor':
                    $reportService->generateProfesorReport($entity, $this->parameters);
                    break;
                default:
                    throw new \Exception("Tipo de reporte no soportado: {$this->type}");
            }

            Log::info("Reporte generado exitosamente", [
                'report_id' => $this->reportId
            ]);

        } catch (\Exception $e) {
            Log::error("Error generando reporte", [
                'report_id' => $this->reportId,
                'error' => $e->getMessage()
            ]);

            // Actualizar estado a fallido
            if (isset($report)) {
                $report->update([
                    'status' => 'failed',
                    'generated_at' => now()
                ]);
            }

            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error("Job de generación de reporte falló", [
            'report_id' => $this->reportId,
            'error' => $exception->getMessage()
        ]);

        // Actualizar estado a fallido
        $report = Report::find($this->reportId);
        if ($report) {
            $report->update([
                'status' => 'failed',
                'generated_at' => now()
            ]);
        }
    }
} 