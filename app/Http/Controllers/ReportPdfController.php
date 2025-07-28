<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Support\Facades\Auth;

class ReportPdfController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
        $this->middleware('auth');
    }

    public function generate(Request $request)
    {
        // Verificar que el usuario tenga el rol de Administrador o Coordinador
        if (!Auth::user()->hasAnyRole(['Administrador', 'Coordinador'])) {
            abort(403, 'No tienes permisos para generar reportes.');
        }

        $request->validate([
            'tipo_reporte' => 'required|in:universidad,facultad,programa,profesor,profesores_completados',
            'entidad_id' => 'nullable|integer',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        // Validar que entidad_id esté presente cuando sea necesario
        if ($request->tipo_reporte !== 'profesores_completados' && (!$request->entidad_id || $request->entidad_id === '')) {
            abort(400, 'El ID de la entidad es requerido para este tipo de reporte.');
        }

        try {
            $parameters = [];
            if ($request->date_from) {
                $parameters['date_from'] = $request->date_from;
            }
            if ($request->date_to) {
                $parameters['date_to'] = $request->date_to;
            }

            $report = null;
            $entityName = '';

            switch ($request->tipo_reporte) {
                case 'universidad':
                    $institution = \App\Models\Institution::findOrFail($request->entidad_id);
                    $report = $this->reportService->generateUniversidadReport($institution, $parameters);
                    $entityName = $institution->name;
                    break;
                case 'facultad':
                    $facultad = \App\Models\Facultad::findOrFail($request->entidad_id);
                    $report = $this->reportService->generateFacultadReport($facultad, $parameters);
                    $entityName = $facultad->nombre;
                    break;
                case 'programa':
                    $programa = \App\Models\Programa::findOrFail($request->entidad_id);
                    $report = $this->reportService->generateProgramaReport($programa, $parameters);
                    $entityName = $programa->nombre;
                    break;
                case 'profesor':
                    $profesor = \App\Models\User::findOrFail($request->entidad_id);
                    $report = $this->reportService->generateProfesorReport($profesor, $parameters);
                    $entityName = $profesor->full_name;
                    break;
                case 'profesores_completados':
                    // Crear el reporte directamente usando el servicio optimizado
                    $parameters['filtro'] = $request->get('filtro', 'todos');
                    $report = $this->reportService->generateProfesoresCompletadosReport($parameters);
                    $entityName = 'Reporte de Participación en Evaluación de Competencias';
                    break;
            }

            if (!$report) {
                throw new \Exception('Error al generar el reporte');
            }

            // Actualizar el reporte con los parámetros correctos
            $report->update([
                'parameters' => $parameters
            ]);

            // Preparar parámetros para getPreviewData y guardar en el reporte
            $previewParameters = $parameters;
            switch ($request->tipo_reporte) {
                case 'universidad':
                    $previewParameters['institution_id'] = $request->entidad_id;
                    $parameters['institution_id'] = $request->entidad_id;
                    break;
                case 'facultad':
                    $previewParameters['facultad_id'] = $request->entidad_id;
                    $parameters['facultad_id'] = $request->entidad_id;
                    break;
                case 'programa':
                    $previewParameters['programa_id'] = $request->entidad_id;
                    $parameters['programa_id'] = $request->entidad_id;
                    break;
                case 'profesor':
                    $previewParameters['profesor_id'] = $request->entidad_id;
                    $parameters['profesor_id'] = $request->entidad_id;
                    break;
                case 'profesores_completados':
                    $previewParameters['filtro'] = $request->get('filtro', 'todos');
                    $parameters['filtro'] = $request->get('filtro', 'todos');
                    break;
            }

            // Generar el PDF usando la vista correcta según el tipo de reporte
            $previewData = $this->reportService->getPreviewData($request->tipo_reporte, $previewParameters);
            
            switch ($request->tipo_reporte) {
                case 'profesores_completados':
                    // Usar la vista específica para profesores completados
                    $data = $this->reportService->getProfesoresCompletadosData($previewParameters);
                    $pdf = PDF::loadView('reports.profesores-completados', compact('data'));
                    break;
                case 'profesor':
                    $pdf = PDF::loadView('reports.profesor', compact('previewData'));
                    break;
                case 'programa':
                    $pdf = PDF::loadView('reports.programa', compact('previewData'));
                    break;
                case 'facultad':
                    $pdf = PDF::loadView('reports.facultad', compact('previewData'));
                    break;
                case 'universidad':
                    $pdf = PDF::loadView('reports.universidad', compact('previewData'));
                    break;
                default:
                    // Fallback a la vista del modal
                    $pdf = PDF::loadView('filament.modals.report-preview', [
                        'tipo_reporte' => $request->tipo_reporte,
                        'entityName' => $entityName,
                        'data' => $request->all(),
                        'previewData' => $previewData,
                        'error' => null
                    ]);
                    break;
            }

            // Configurar el PDF según el tipo de reporte
            if ($request->tipo_reporte === 'profesores_completados') {
                // Configuraciones optimizadas para reportes grandes
                $pdf->setPaper('A4', 'landscape'); // Usar orientación horizontal para más columnas
                $pdf->setOption('isRemoteEnabled', false);
                $pdf->setOption('isHtml5ParserEnabled', true);
                $pdf->setOption('isPhpEnabled', false);
                $pdf->setOption('memoryLimit', '512M');
            } else {
                // Configuraciones estándar para otros reportes
                $pdf->setPaper('A4', 'portrait');
                $pdf->setOption('isRemoteEnabled', true);
                $pdf->setOption('isHtml5ParserEnabled', true);
            }

            // Generar nombre del archivo
            if ($request->tipo_reporte === 'profesores_completados') {
                $fileName = 'reporte_participacion_evaluacion_' . date('Y-m-d_H-i-s') . '.pdf';
            } else {
                $fileName = 'reporte_' . $request->tipo_reporte . '_' . date('Y-m-d_H-i-s') . '.pdf';
            }

            // Si la petición tiene el parámetro redirect, redirigir a la lista de reportes según el rol
            if ($request->has('redirect')) {
                $redirectUrl = Auth::user()->hasRole('Coordinador') ? '/coordinador/reports' : '/admin/reports';
                return redirect($redirectUrl)->with('success', 'Reporte generado exitosamente');
            }

            // Forzar la descarga del PDF
            return $pdf->download($fileName);

        } catch (\Exception $e) {
            \Log::error('Error al generar PDF de reporte: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            abort(500, 'Error al generar el PDF: ' . $e->getMessage());
        }
    }
} 