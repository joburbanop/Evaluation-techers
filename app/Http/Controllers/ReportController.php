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
        // Solo los administradores pueden descargar reportes

        if ($report->status !== 'completed') {
            abort(404, 'El reporte no está disponible para descarga');
        }

        if (!$report->file_path || !Storage::exists($report->file_path)) {
            abort(404, 'El archivo del reporte no se encuentra');
        }

        $fileName = $report->name . '.pdf';
        
        return Storage::download($report->file_path, $fileName);
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
} 