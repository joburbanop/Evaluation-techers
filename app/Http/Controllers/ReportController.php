<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Facultad;
use App\Models\Programa;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
        $this->middleware('auth');
    }

    public function index()
    {
        $reports = Report::with(['generatedBy', 'entity'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $facultades = Facultad::with('institution')->get();
        $programas = Programa::with('facultad.institution')->get();
        
        return view('reports.create', compact('facultades', 'programas'));
    }

    public function generateFacultad(Request $request)
    {
        $request->validate([
            'facultad_id' => 'required|exists:facultades,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $facultad = Facultad::findOrFail($request->facultad_id);
        
        $parameters = [];
        if ($request->date_from) {
            $parameters['date_from'] = $request->date_from;
        }
        if ($request->date_to) {
            $parameters['date_to'] = $request->date_to;
        }

        try {
            $report = $this->reportService->generateFacultadReport($facultad, $parameters);
            
            return response()->json([
                'success' => true,
                'message' => 'Reporte generado exitosamente',
                'report' => $report,
                'download_url' => route('reports.download', $report->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generatePrograma(Request $request)
    {
        $request->validate([
            'programa_id' => 'required|exists:programas,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $programa = Programa::findOrFail($request->programa_id);
        
        $parameters = [];
        if ($request->date_from) {
            $parameters['date_from'] = $request->date_from;
        }
        if ($request->date_to) {
            $parameters['date_to'] = $request->date_to;
        }

        try {
            $report = $this->reportService->generateProgramaReport($programa, $parameters);
            
            return response()->json([
                'success' => true,
                'message' => 'Reporte generado exitosamente',
                'report' => $report,
                'download_url' => route('reports.download', $report->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el reporte: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download(Report $report)
    {
        // Solo los administradores y coordinadores pueden descargar reportes
        $user = Auth::user();
        if (!$user->hasAnyRole(['Administrador', 'Coordinador'])) {
            abort(403, 'No tienes permisos para descargar reportes');
        }

        if ($report->status !== 'completed') {
            abort(404, 'El reporte no está disponible para descarga');
        }

        try {
            // Regenerar el PDF con el formato correcto usando getPreviewData
            $parameters = $report->parameters ?? [];
            
            // Agregar el ID de la entidad a los parámetros si no existe
            if ($report->entity_id) {
                switch ($report->type) {
                    case 'universidad':
                        if (!isset($parameters['institution_id'])) {
                            $parameters['institution_id'] = $report->entity_id;
                        }
                        break;
                    case 'facultad':
                        if (!isset($parameters['facultad_id'])) {
                            $parameters['facultad_id'] = $report->entity_id;
                        }
                        break;
                    case 'programa':
                        if (!isset($parameters['programa_id'])) {
                            $parameters['programa_id'] = $report->entity_id;
                        }
                        break;
                    case 'profesor':
                        if (!isset($parameters['profesor_id'])) {
                            $parameters['profesor_id'] = $report->entity_id;
                        }
                        break;
                }
            }

            // Obtener datos con el formato correcto
            $previewData = $this->reportService->getPreviewData($report->type, $parameters);
            
            // Generar el PDF usando la vista correcta según el tipo de reporte
            switch ($report->type) {

                case 'profesor':
                    $pdf = \PDF::loadView('reports.profesor', compact('previewData'));
                    $pdf->setPaper('A4', 'portrait');
                    break;
                case 'programa':
                    $pdf = \PDF::loadView('reports.programa', compact('previewData'));
                    $pdf->setPaper('A4', 'landscape');
                    break;
                case 'facultad':
                    $pdf = \PDF::loadView('reports.facultad', compact('previewData'));
                    $pdf->setPaper('A4', 'landscape');
                    break;
                case 'universidad':
                    $pdf = \PDF::loadView('reports.universidad', compact('previewData'));
                    $pdf->setPaper('A4', 'landscape');
                    break;
                default:
                    // Fallback: usar el archivo existente
                    if (!$report->file_path || !Storage::exists($report->file_path)) {
                        abort(404, 'El archivo del reporte no se encuentra');
                    }
                    $fileName = $report->name . '.pdf';
                    return Storage::download($report->file_path, $fileName);
            }

            // Configurar opciones del PDF
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isHtml5ParserEnabled', true);

            // Generar nombre del archivo
            $fileName = $report->name . '.pdf';
            
            // Forzar la descarga del PDF
            return $pdf->download($fileName);

        } catch (\Exception $e) {
            \Log::error('Error al descargar reporte: ' . $e->getMessage(), [
                'report_id' => $report->id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback: intentar descargar el archivo existente
            if ($report->file_path && Storage::exists($report->file_path)) {
                $fileName = $report->name . '.pdf';
                return Storage::download($report->file_path, $fileName);
            }

            abort(500, 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    public function destroy(Report $report)
    {
        // Solo los administradores pueden eliminar reportes

        // Eliminar el archivo físico
        if ($report->file_path && Storage::exists($report->file_path)) {
            Storage::delete($report->file_path);
        }

        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reporte eliminado exitosamente'
        ]);
    }

    public function status(Report $report)
    {
        return response()->json([
            'status' => $report->status,
            'status_label' => $report->status_label,
            'generated_at' => $report->generated_at?->format('d/m/Y H:i:s'),
            'download_url' => $report->status === 'completed' ? route('reports.download', $report->id) : null
        ]);
    }

    public function generatePDFFromModal(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Verificar que el usuario tenga el rol de Administrador o Coordinador
        $user = Auth::user();
        if (!$user->hasAnyRole(['Administrador', 'Coordinador'])) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para generar reportes. Se requiere rol de Administrador o Coordinador.'
            ], 403);
        }

        $request->validate([
            'tipo_reporte' => 'required|in:universidad,facultad,programa,profesor',
            'entidad_id' => 'nullable|integer',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        try {
            $parameters = [];
            if ($request->date_from) {
                $parameters['date_from'] = $request->date_from;
            }
            if ($request->date_to) {
                $parameters['date_to'] = $request->date_to;
            }
            if ($request->filtro) {
                $parameters['filtro'] = $request->filtro;
            }

            $report = null;

            switch ($request->tipo_reporte) {
                case 'universidad':
                    $institution = \App\Models\Institution::findOrFail($request->entidad_id);
                    $report = $this->reportService->generateUniversidadReport($institution, $parameters);
                    break;
                case 'facultad':
                    $facultad = \App\Models\Facultad::findOrFail($request->entidad_id);
                    $report = $this->reportService->generateFacultadReport($facultad, $parameters);
                    break;
                case 'programa':
                    $programa = \App\Models\Programa::findOrFail($request->entidad_id);
                    $report = $this->reportService->generateProgramaReport($programa, $parameters);
                    break;
                case 'profesor':
                    $profesor = \App\Models\User::findOrFail($request->entidad_id);
                    $report = $this->reportService->generateProfesorReport($profesor, $parameters);
                    break;

            }

            if (!$report) {
                throw new \Exception('Error al generar el reporte');
            }

            // Devolver respuesta JSON con información del reporte generado
            return response()->json([
                'success' => true,
                'message' => 'Reporte generado exitosamente',
                'report' => [
                    'id' => $report->id,
                    'name' => $report->name,
                    'status' => $report->status,
                    'download_url' => route('reports.download', $report->id),
                    'redirect_url' => '/admin/reports'
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al generar PDF desde modal: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al generar el PDF: ' . $e->getMessage()
            ], 500);
        }
    }
} 