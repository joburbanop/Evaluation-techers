<?php

namespace App\Services;

use App\Models\Report;
use App\Models\TestAssignment;
use App\Models\Facultad;
use App\Models\Programa;
use App\Models\Institution;
use App\Models\User;
use App\Models\TestCompetencyLevel;
use App\Models\TestAreaCompetencyLevel;
use App\Models\EvaluacionPorArea;
use App\Models\EvaluacionPorInstitucion;
use App\Models\EvaluacionPorProfesor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use Carbon\Carbon;

class ReportService
{
    public function __construct()
    {
        // Eliminada la validación redundante del rol de administrador
    }

    public function generateFacultadReport(Facultad $facultad, array $parameters = [])
    {
        $report = Report::create([
            'name' => "Reporte de Facultad: {$facultad->nombre}",
            'type' => 'facultad',
            'entity_id' => $facultad->id,
            'entity_type' => Facultad::class,
            'generated_by' => auth()->id(),
            'parameters' => $parameters,
            'status' => 'generating',
        ]);

        try {
            $data = $this->getFacultadData($facultad, $parameters);
            $pdf = $this->generateFacultadPDF($facultad, $data);
            
            $fileName = "reporte_facultad_{$facultad->id}_" . now()->format('Y-m-d_H-i-s') . '.pdf';
            $filePath = "reports/facultades/{$fileName}";
            
            Storage::put($filePath, $pdf->output());
            
            $report->update([
                'file_path' => $filePath,
                'file_size' => Storage::size($filePath),
                'status' => 'completed',
                'generated_at' => now(),
                'expires_at' => now()->addDays(30), // Reportes expiran en 30 días
            ]);

            return $report;
        } catch (\Exception $e) {
            $report->update([
                'status' => 'failed',
                'generated_at' => now(),
            ]);
            
            throw $e;
        }
    }

    public function generateProgramaReport(Programa $programa, array $parameters = [])
    {
        $report = Report::create([
            'name' => "Reporte de Programa: {$programa->nombre}",
            'type' => 'programa',
            'entity_id' => $programa->id,
            'entity_type' => Programa::class,
            'generated_by' => auth()->id(),
            'parameters' => $parameters,
            'status' => 'generating',
        ]);

        try {
            $data = $this->getProgramaData($programa, $parameters);
            $pdf = $this->generateProgramaPDF($programa, $data);
            
            $fileName = "reporte_programa_{$programa->id}_" . now()->format('Y-m-d_H-i-s') . '.pdf';
            $filePath = "reports/programas/{$fileName}";
            
            Storage::put($filePath, $pdf->output());
            
            $report->update([
                'file_path' => $filePath,
                'file_size' => Storage::size($filePath),
                'status' => 'completed',
                'generated_at' => now(),
                'expires_at' => now()->addDays(30),
            ]);

            return $report;
        } catch (\Exception $e) {
            $report->update([
                'status' => 'failed',
                'generated_at' => now(),
            ]);
            
            throw $e;
        }
    }

    public function generateUniversidadReport(Institution $institution, array $parameters = [])
    {
        $report = Report::create([
            'name' => "Reporte de Universidad: {$institution->name}",
            'type' => 'universidad',
            'entity_id' => $institution->id,
            'entity_type' => Institution::class,
            'generated_by' => auth()->id(),
            'parameters' => $parameters,
            'status' => 'generating',
        ]);

        try {
            $data = $this->getUniversidadData($institution, $parameters);
            $pdf = $this->generateUniversidadPDF($institution, $data);
            
            $fileName = "reporte_universidad_{$institution->id}_" . now()->format('Y-m-d_H-i-s') . '.pdf';
            $filePath = "reports/universidades/{$fileName}";
            
            Storage::put($filePath, $pdf->output());
            
            $report->update([
                'file_path' => $filePath,
                'file_size' => Storage::size($filePath),
                'status' => 'completed',
                'generated_at' => now(),
                'expires_at' => now()->addDays(30),
            ]);

            return $report;
        } catch (\Exception $e) {
            $report->update([
                'status' => 'failed',
                'generated_at' => now(),
            ]);
            
            throw $e;
        }
    }

    public function generateProfesorReport(User $profesor, array $parameters = [])
    {
        $report = Report::create([
            'name' => "Reporte de Profesor: {$profesor->name} {$profesor->apellido1}",
            'type' => 'profesor',
            'entity_id' => $profesor->id,
            'entity_type' => User::class,
            'generated_by' => auth()->id(),
            'parameters' => $parameters,
            'status' => 'generating',
        ]);

        try {
            $data = $this->getProfesorData($profesor, $parameters);
            $pdf = $this->generateProfesorPDF($profesor, $data);
            
            $fileName = "reporte_profesor_{$profesor->id}_" . now()->format('Y-m-d_H-i-s') . '.pdf';
            $filePath = "reports/profesores/{$fileName}";
            
            Storage::put($filePath, $pdf->output());
            
            $report->update([
                'file_path' => $filePath,
                'file_size' => Storage::size($filePath),
                'status' => 'completed',
                'generated_at' => now(),
                'expires_at' => now()->addDays(30),
            ]);

            return $report;
        } catch (\Exception $e) {
            $report->update([
                'status' => 'failed',
                'generated_at' => now(),
            ]);
            
            throw $e;
        }
    }

    public function generateProfesoresCompletadosReport(array $parameters = [])
    {
        $report = Report::create([
            'name' => "Reporte de Participación en Evaluación de Competencias",
            'type' => 'profesores_completados',
            'entity_id' => null,
            'entity_type' => null,
            'generated_by' => auth()->id(),
            'parameters' => $parameters,
            'status' => 'generating',
        ]);

        try {
            $data = $this->getProfesoresCompletadosData($parameters);
            $pdf = $this->generateProfesoresCompletadosPDF($data);
            
            $fileName = "reporte_participacion_evaluacion_" . now()->format('Y-m-d_H-i-s') . '.pdf';
            $filePath = "reports/profesores_completados/{$fileName}";
            
            Storage::put($filePath, $pdf->output());
            
            $report->update([
                'file_path' => $filePath,
                'file_size' => Storage::size($filePath),
                'status' => 'completed',
                'generated_at' => now(),
                'expires_at' => now()->addDays(30),
            ]);

            return $report;
        } catch (\Exception $e) {
            $report->update([
                'status' => 'failed',
                'generated_at' => now(),
            ]);
            
            throw $e;
        }
    }

    public function getPreviewData(string $tipoReporte, array $parameters = [])
    {
        \Log::info('getPreviewData llamado:', ['tipo' => $tipoReporte, 'parametros' => $parameters]);
        
        switch ($tipoReporte) {
            case 'universidad':
                if (!isset($parameters['institution_id'])) {
                    \Log::error('institution_id no encontrado en parámetros');
                    return null;
                }
                $institution = Institution::find($parameters['institution_id']);
                if (!$institution) {
                    \Log::error('Institución no encontrada:', ['id' => $parameters['institution_id']]);
                    return null;
                }
                \Log::info('Institución encontrada:', ['id' => $institution->id, 'name' => $institution->name]);
                return $this->getUniversidadPreviewData($institution, $parameters);

            case 'facultad':
                if (!isset($parameters['facultad_id'])) {
                    return null;
                }
                $facultad = Facultad::find($parameters['facultad_id']);
                if (!$facultad) {
                    return null;
                }
                return $this->getFacultadPreviewData($facultad, $parameters);

            case 'programa':
                if (!isset($parameters['programa_id'])) {
                    return null;
                }
                $programa = Programa::find($parameters['programa_id']);
                if (!$programa) {
                    return null;
                }
                return $this->getProgramaPreviewData($programa, $parameters);

            case 'profesor':
                if (!isset($parameters['profesor_id'])) {
                    return null;
                }
                $profesor = User::find($parameters['profesor_id']);
                if (!$profesor) {
                    return null;
                }
                return $this->getProfesorPreviewData($profesor, $parameters);

            case 'profesores_completados':
                return $this->getProfesoresCompletadosPreviewData($parameters);

            default:
                return $this->getGeneralPreviewData($parameters);
        }
    }

    private function getGeneralPreviewData(array $parameters = [])
    {
        return [
            'total_evaluaciones' => 0,
            'total_usuarios' => 0,
            'promedio_general' => 0,
            'nivel_satisfaccion' => $this->calculateSatisfactionLevel(0),
            'areas_evaluadas' => 0,
            'area_stats' => collect([]),
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
    }

    private function getFacultadPreviewData(Facultad $facultad, array $parameters = [])
    {
        // Usar la misma lógica que getFacultadData para obtener datos consistentes
        $query = EvaluacionPorArea::byFacultad($facultad->id);

        // Aplicar filtros de fecha si se especifican
        if (isset($parameters['date_from'])) {
            $query->byDateRange($parameters['date_from'], $parameters['date_to'] ?? now());
        }

        $evaluaciones = $query->get();

        // Estadísticas generales usando vista optimizada
        $totalEvaluaciones = $evaluaciones->count();
        $totalUsuarios = $evaluaciones->unique('user_id')->count();
        $promedioGeneral = $evaluaciones->avg('score') ?? 0;
        
        // Calcular estadísticas por área usando vista
        $areaStats = $evaluaciones->groupBy('area_id')->map(function ($areaEvaluaciones, $areaId) {
            $area = $areaEvaluaciones->first();
            return [
                'area_name' => $area->area_name,
                'total_evaluaciones' => $areaEvaluaciones->count(),
                'promedio_score' => round($areaEvaluaciones->avg('score'), 2),
                'max_score' => $areaEvaluaciones->max('score'),
                'min_score' => $areaEvaluaciones->min('score'),
                'niveles' => $this->calculateNivelesFromVista($areaEvaluaciones, $areaId),
            ];
        });

        // Estadísticas por programa usando vista
        $programaStats = $evaluaciones->groupBy('programa_id')->map(function ($programaEvaluaciones, $programaId) {
            $programa = $programaEvaluaciones->first();
            return [
                'programa_nombre' => $programa->programa_nombre,
                'total_evaluaciones' => $programaEvaluaciones->count(),
                'promedio_score' => round($programaEvaluaciones->avg('score'), 2),
                'max_score' => $programaEvaluaciones->max('score'),
                'min_score' => $programaEvaluaciones->min('score'),
                'niveles' => $this->calculateNivelesFromVista($programaEvaluaciones),
            ];
        });



        return [
            'entidad' => $facultad,
            'total_evaluaciones' => $totalEvaluaciones,
            'total_usuarios' => $totalUsuarios,
            'promedio_general' => round($promedioGeneral, 2),
            'max_score' => $evaluaciones->max('score') ?? 0,
            'min_score' => $evaluaciones->min('score') ?? 0,
            'nivel_satisfaccion' => $this->calculateSatisfactionLevel($promedioGeneral),
            'areas_evaluadas' => $evaluaciones->unique('area_id')->count() ?: 4,
            'area_stats' => $areaStats,
            'programa_stats' => $programaStats,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
    }

    private function getProgramaPreviewData(Programa $programa, array $parameters = [])
    {
        // Usar la misma lógica que getProgramaData para obtener datos consistentes
        $query = EvaluacionPorArea::byPrograma($programa->id);

        // Aplicar filtros de fecha si se especifican
        if (isset($parameters['date_from'])) {
            $query->byDateRange($parameters['date_from'], $parameters['date_to'] ?? now());
        }

        $evaluaciones = $query->get();

        // Estadísticas generales usando vista optimizada
        $totalEvaluaciones = $evaluaciones->count();
        $totalUsuarios = $evaluaciones->unique('user_id')->count();
        $promedioGeneral = $evaluaciones->avg('score') ?? 0;
        
        // Calcular estadísticas por área usando vista
        $areaStats = $evaluaciones->groupBy('area_id')->map(function ($areaEvaluaciones, $areaId) {
            $area = $areaEvaluaciones->first();
            return [
                'area_name' => $area->area_name,
                'total_evaluaciones' => $areaEvaluaciones->count(),
                'promedio_score' => round($areaEvaluaciones->avg('score'), 2),
                'max_score' => $areaEvaluaciones->max('score'),
                'min_score' => $areaEvaluaciones->min('score'),
                'niveles' => $this->calculateNivelesFromVista($areaEvaluaciones, $areaId),
            ];
        });

        // Top 10 mejores evaluados usando vista
        $topEvaluados = $evaluaciones->groupBy('user_id')->map(function ($userEvaluaciones, $userId) {
            $user = $userEvaluaciones->first();
            $score = $userEvaluaciones->sum('score');
            
            return [
                'user' => (object)[
                    'name' => $user->user_name,
                    'apellido1' => $user->apellido1,
                    'apellido2' => $user->apellido2,
                    'full_name' => trim($user->user_name . ' ' . $user->apellido1 . ' ' . $user->apellido2),
                ],
                'score' => $score,
                'fecha' => \Carbon\Carbon::parse($userEvaluaciones->first()->created_at),
            ];
        })
        ->sortByDesc('score')
        ->take(10);



        return [
            'entidad' => $programa,
            'facultad' => $programa->facultad,
            'total_evaluaciones' => $totalEvaluaciones,
            'total_usuarios' => $totalUsuarios,
            'promedio_general' => round($promedioGeneral, 2),
            'max_score' => $evaluaciones->max('score') ?? 0,
            'min_score' => $evaluaciones->min('score') ?? 0,
            'nivel_satisfaccion' => $this->calculateSatisfactionLevel($promedioGeneral),
            'areas_evaluadas' => $evaluaciones->unique('area_id')->count() ?: 4,
            'area_stats' => $areaStats,
            'top_evaluados' => $topEvaluados,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
    }

    private function getUniversidadPreviewData(Institution $institution, array $parameters = [])
    {
        // Usar la misma lógica que getUniversidadData para obtener datos consistentes
        $query = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->where('u.institution_id', $institution->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8);

        if (isset($parameters['date_from'])) {
            $query->whereBetween('ta.created_at', [
                $parameters['date_from'], 
                $parameters['date_to'] ?? now()
            ]);
        }

        $stats = $query->select(
            DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones'),
            DB::raw('COUNT(DISTINCT ta.user_id) as total_usuarios'),
            DB::raw('AVG(tr.score) as promedio_score'),
            DB::raw('MAX(tr.score) as max_score'),
            DB::raw('MIN(tr.score) as min_score')
        )->first();

        // Estadísticas por área
        $areas = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->join('areas as a', 'q.area_id', '=', 'a.id')
            ->where('u.institution_id', $institution->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8)
            ->select(
                'a.id as area_id',
                'a.name as area_name',
                DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones'),
                DB::raw('AVG(tr.score) as promedio_score'),
                DB::raw('MAX(tr.score) as max_score'),
                DB::raw('MIN(tr.score) as min_score')
            )
            ->groupBy('a.id', 'a.name')
            ->get();

        // Top 10 mejores profesores
        $topProfesores = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('facultades as f', 'u.facultad_id', '=', 'f.id')
            ->join('programas as p', 'u.programa_id', '=', 'p.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->where('u.institution_id', $institution->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8)
            ->select(
                'u.id as user_id',
                'u.name as user_name',
                'u.apellido1',
                'u.apellido2',
                'f.nombre as facultad_nombre',
                'p.nombre as programa_nombre',
                DB::raw('AVG(tr.score) as promedio_general'),
                DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones')
            )
            ->groupBy('u.id', 'u.name', 'u.apellido1', 'u.apellido2', 'f.nombre', 'p.nombre')
            ->orderBy('promedio_general', 'desc')
            ->limit(10)
            ->get();



        return [
            'entidad' => $institution,
            'stats' => $stats,
            'areas' => $areas,
            'top_profesores' => $topProfesores,
            'total_evaluaciones' => $stats->total_evaluaciones,
            'total_usuarios' => $stats->total_usuarios,
            'promedio_general' => round($stats->promedio_score, 2),
            'max_score' => $stats->max_score,
            'min_score' => $stats->min_score,
            'nivel_satisfaccion' => $this->calculateSatisfactionLevel($stats->promedio_score),
            'areas_evaluadas' => $areas->count(),
            'area_stats' => $areas,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
    }

    private function getProfesorPreviewData(User $profesor, array $parameters = [])
    {
        // Usar la misma lógica que getProfesorData para obtener datos consistentes
        $query = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->where('ta.user_id', $profesor->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8);

        if (isset($parameters['date_from'])) {
            $query->whereBetween('ta.created_at', [
                $parameters['date_from'], 
                $parameters['date_to'] ?? now()
            ]);
        }

        $stats = $query->select(
            DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones'),
            DB::raw('AVG(tr.score) as promedio_score'),
            DB::raw('MAX(tr.score) as max_score'),
            DB::raw('MIN(tr.score) as min_score'),
            DB::raw('COUNT(tr.id) as total_respuestas')
        )->first();

        // Estadísticas por área
        $areas = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->join('areas as a', 'q.area_id', '=', 'a.id')
            ->where('ta.user_id', $profesor->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8)
            ->select(
                'a.id as area_id',
                'a.name as area_name',
                DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones'),
                DB::raw('AVG(tr.score) as promedio_score'),
                DB::raw('MAX(tr.score) as max_score'),
                DB::raw('MIN(tr.score) as min_score'),
                DB::raw('COUNT(tr.id) as total_respuestas')
            )
            ->groupBy('a.id', 'a.name')
            ->get()
            ->map(function ($area) {
                return [
                    'area_name' => $area->area_name,
                    'total_evaluaciones' => $area->total_evaluaciones,
                    'promedio_score' => round($area->promedio_score, 2),
                    'max_score' => $area->max_score,
                    'min_score' => $area->min_score,
                    'total_respuestas' => $area->total_respuestas,
                ];
            });

        // Historial de evaluaciones
        $historial = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->join('areas as a', 'q.area_id', '=', 'a.id')
            ->where('ta.user_id', $profesor->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8)
            ->select(
                'ta.created_at',
                'a.name as area_name',
                DB::raw('AVG(tr.score) as promedio_score'),
                DB::raw('COUNT(tr.id) as total_respuestas')
            )
            ->groupBy('ta.created_at', 'a.name')
            ->orderBy('ta.created_at')
            ->get()
            ->map(function ($evaluacion) {
                return [
                    'fecha' => \Carbon\Carbon::parse($evaluacion->created_at)->format('d/m/Y'),
                    'area' => $evaluacion->area_name,
                    'score' => $evaluacion->promedio_score,
                    'total_respuestas' => $evaluacion->total_respuestas,
                ];
            });

        // Comparación con otros profesores del mismo programa
        $comparacion = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->where('u.programa_id', $profesor->programa_id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8)
            ->select(
                DB::raw('AVG(tr.score) as promedio_programa'),
                DB::raw('MAX(tr.score) as max_programa'),
                DB::raw('MIN(tr.score) as min_programa')
            )
            ->first();



        return [
            'entidad' => $profesor,
            'stats' => $stats,
            'areas' => $areas,
            'historial' => $historial,
            'comparacion' => $comparacion,
            'total_evaluaciones' => $stats->total_evaluaciones,
            'promedio_general' => round($stats->promedio_score, 2),
            'max_score' => $stats->max_score,
            'min_score' => $stats->min_score,
            'total_respuestas' => $stats->total_respuestas,
            'nivel_satisfaccion' => $this->calculateSatisfactionLevel($stats->promedio_score),
            'areas_evaluadas' => $areas->count(),
            'area_stats' => $areas,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
    }

    private function getAreaStats($evaluaciones)
    {
        return $evaluaciones->groupBy('area_id')->map(function ($areaEvaluaciones, $areaId) {
            $area = $areaEvaluaciones->first();
            return [
                'area_name' => $area->area_name,
                'promedio_score' => round($areaEvaluaciones->avg('score'), 2),
                'total_evaluaciones' => $areaEvaluaciones->count(),
            ];
        });
    }

    private function calculateSatisfactionLevel($promedio)
    {
        $percentage = ($promedio / 5.0) * 100;
        $level = $promedio >= 4.0 ? 'Excelente' : ($promedio >= 3.0 ? 'Bueno' : 'Necesita Mejora');
        return ['percentage' => round($percentage, 1), 'level' => $level];
    }

    private function getFacultadData(Facultad $facultad, array $parameters = [])
    {
        // Usar vista optimizada en lugar de consultas complejas
        $query = EvaluacionPorArea::byFacultad($facultad->id);

        // Aplicar filtros de fecha si se especifican
        if (isset($parameters['date_from'])) {
            $query->byDateRange($parameters['date_from'], $parameters['date_to'] ?? now());
        }

        $evaluaciones = $query->get();

        // Estadísticas generales usando vista optimizada
        $totalEvaluaciones = $evaluaciones->count();
        $totalUsuarios = $evaluaciones->unique('user_id')->count();
        
        // Calcular estadísticas por área usando vista
        $areaStats = $evaluaciones->groupBy('area_id')->map(function ($areaEvaluaciones, $areaId) {
            $area = $areaEvaluaciones->first();
            return [
                'area_name' => $area->area_name,
                'total_evaluaciones' => $areaEvaluaciones->count(),
                'promedio_score' => round($areaEvaluaciones->avg('score'), 2),
                'max_score' => $areaEvaluaciones->max('score'),
                'min_score' => $areaEvaluaciones->min('score'),
                'niveles' => $this->calculateNivelesFromVista($areaEvaluaciones, $areaId),
            ];
        });

        // Estadísticas por programa usando vista
        $programaStats = $evaluaciones->groupBy('programa_id')->map(function ($programaEvaluaciones, $programaId) {
            $programa = $programaEvaluaciones->first();
            return [
                'programa_nombre' => $programa->programa_nombre,
                'total_evaluaciones' => $programaEvaluaciones->count(),
                'promedio_score' => round($programaEvaluaciones->avg('score'), 2),
                'max_score' => $programaEvaluaciones->max('score'),
                'min_score' => $programaEvaluaciones->min('score'),
                'niveles' => $this->calculateNivelesFromVista($programaEvaluaciones),
            ];
        });

        return [
            'facultad' => $facultad,
            'total_evaluaciones' => $totalEvaluaciones,
            'total_usuarios' => $totalUsuarios,
            'area_stats' => $areaStats,
            'programa_stats' => $programaStats,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
    }

    private function getProgramaData(Programa $programa, array $parameters = [])
    {
        // Usar vista optimizada
        $query = EvaluacionPorArea::byPrograma($programa->id);

        // Aplicar filtros de fecha si se especifican
        if (isset($parameters['date_from'])) {
            $query->byDateRange($parameters['date_from'], $parameters['date_to'] ?? now());
        }

        $evaluaciones = $query->get();

        // Estadísticas generales usando vista optimizada
        $totalEvaluaciones = $evaluaciones->count();
        $totalUsuarios = $evaluaciones->unique('user_id')->count();
        
        // Calcular estadísticas por área usando vista
        $areaStats = $evaluaciones->groupBy('area_id')->map(function ($areaEvaluaciones, $areaId) {
            $area = $areaEvaluaciones->first();
            return [
                'area_name' => $area->area_name,
                'total_evaluaciones' => $areaEvaluaciones->count(),
                'promedio_score' => round($areaEvaluaciones->avg('score'), 2),
                'max_score' => $areaEvaluaciones->max('score'),
                'min_score' => $areaEvaluaciones->min('score'),
                'niveles' => $this->calculateNivelesFromVista($areaEvaluaciones, $areaId),
            ];
        });

        // Top 10 mejores evaluados usando vista
        $topEvaluados = $evaluaciones->groupBy('user_id')->map(function ($userEvaluaciones, $userId) {
            $user = $userEvaluaciones->first();
            $score = $userEvaluaciones->sum('score');
            
            return [
                'user' => (object)[
                    'name' => $user->user_name,
                    'apellido1' => $user->apellido1,
                    'apellido2' => $user->apellido2,
                    'full_name' => trim($user->user_name . ' ' . $user->apellido1 . ' ' . $user->apellido2),
                ],
                'score' => $score,
                'fecha' => Carbon::parse($userEvaluaciones->first()->created_at),
            ];
        })
        ->sortByDesc('score')
        ->take(10);

        return [
            'programa' => $programa,
            'facultad' => $programa->facultad,
            'total_evaluaciones' => $totalEvaluaciones,
            'total_usuarios' => $totalUsuarios,
            'area_stats' => $areaStats,
            'top_evaluados' => $topEvaluados,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
    }

    private function calculateNiveles($assignments, $facultadId = null, $areaId = null, $programaId = null)
    {
        $niveles = [];
        
        foreach ($assignments as $assignment) {
            $score = 0;
            
            if ($areaId) {
                // Calcular score por área
                $areaResponses = $assignment->responses->where('question.area_id', $areaId);
                $score = $areaResponses->sum('option.score');
                $nivel = TestAreaCompetencyLevel::getLevelByScore($assignment->test_id, $areaId, $score);
            } else {
                // Calcular score global
                $nonSociodemographicResponses = $assignment->responses->filter(function($response) {
                    return $response->question->area_id !== 8;
                });
                $score = $nonSociodemographicResponses->sum('option.score');
                $nivel = TestCompetencyLevel::getLevelForScore($assignment->test_id, $score);
            }
            
            $nivelCode = $nivel ? $nivel->code : 'NA';
            $niveles[$nivelCode] = ($niveles[$nivelCode] ?? 0) + 1;
        }
        
        return $niveles;
    }

    private function calculateNivelesFromVista($evaluaciones, $areaId = null)
    {
        $niveles = [];
        
        foreach ($evaluaciones as $evaluacion) {
            $score = $evaluacion->score;
            
            // Determinar nivel basado en el score (simplificado)
            if ($score >= 4.5) {
                $nivelCode = 'C2';
            } elseif ($score >= 3.5) {
                $nivelCode = 'C1';
            } elseif ($score >= 2.5) {
                $nivelCode = 'B2';
            } elseif ($score >= 1.5) {
                $nivelCode = 'B1';
            } elseif ($score >= 0.5) {
                $nivelCode = 'A2';
            } else {
                $nivelCode = 'A1';
            }
            
            $niveles[$nivelCode] = ($niveles[$nivelCode] ?? 0) + 1;
        }
        
        return $niveles;
    }

    private function generateFacultadPDF(Facultad $facultad, array $data)
    {
        return PDF::loadView('reports.facultad', $data);
    }

    private function generateProgramaPDF(Programa $programa, array $data)
    {
        return PDF::loadView('reports.programa', $data);
    }

    private function getUniversidadData(Institution $institution, array $parameters = [])
    {
        // Consulta simple sin vistas para evitar problemas
        $query = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->where('u.institution_id', $institution->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8);

        if (isset($parameters['date_from'])) {
            $query->whereBetween('ta.created_at', [
                $parameters['date_from'], 
                $parameters['date_to'] ?? now()
            ]);
        }

        $stats = $query->select(
            DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones'),
            DB::raw('COUNT(DISTINCT ta.user_id) as total_usuarios'),
            DB::raw('AVG(tr.score) as promedio_score'),
            DB::raw('MAX(tr.score) as max_score'),
            DB::raw('MIN(tr.score) as min_score')
        )->first();

        // Estadísticas por facultad
        $facultades = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('facultades as f', 'u.facultad_id', '=', 'f.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->where('u.institution_id', $institution->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8)
            ->select(
                'f.id as facultad_id',
                'f.nombre as facultad_nombre',
                DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones'),
                DB::raw('COUNT(DISTINCT ta.user_id) as total_usuarios'),
                DB::raw('AVG(tr.score) as promedio_score'),
                DB::raw('MAX(tr.score) as max_score'),
                DB::raw('MIN(tr.score) as min_score')
            )
            ->groupBy('f.id', 'f.nombre')
            ->get();

        // Estadísticas por área
        $areas = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->join('areas as a', 'q.area_id', '=', 'a.id')
            ->where('u.institution_id', $institution->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8)
            ->select(
                'a.id as area_id',
                'a.name as area_name',
                DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones'),
                DB::raw('AVG(tr.score) as promedio_score'),
                DB::raw('MAX(tr.score) as max_score'),
                DB::raw('MIN(tr.score) as min_score')
            )
            ->groupBy('a.id', 'a.name')
            ->get();

        // Top 10 mejores profesores
        $topProfesores = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('facultades as f', 'u.facultad_id', '=', 'f.id')
            ->join('programas as p', 'u.programa_id', '=', 'p.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->where('u.institution_id', $institution->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8)
            ->select(
                'u.id as user_id',
                'u.name as user_name',
                'u.apellido1',
                'u.apellido2',
                'f.nombre as facultad_nombre',
                'p.nombre as programa_nombre',
                DB::raw('AVG(tr.score) as promedio_general'),
                DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones')
            )
            ->groupBy('u.id', 'u.name', 'u.apellido1', 'u.apellido2', 'f.nombre', 'p.nombre')
            ->orderBy('promedio_general', 'desc')
            ->limit(10)
            ->get();

        return [
            'institution' => $institution,
            'stats' => $stats,
            'facultades' => $facultades,
            'areas' => $areas,
            'top_profesores' => $topProfesores,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
    }

    private function getProfesorData(User $profesor, array $parameters = [])
    {
        // Consulta simple sin vistas para evitar problemas
        $query = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->where('ta.user_id', $profesor->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8);

        if (isset($parameters['date_from'])) {
            $query->whereBetween('ta.created_at', [
                $parameters['date_from'], 
                $parameters['date_to'] ?? now()
            ]);
        }

        $stats = $query->select(
            DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones'),
            DB::raw('AVG(tr.score) as promedio_score'),
            DB::raw('MAX(tr.score) as max_score'),
            DB::raw('MIN(tr.score) as min_score'),
            DB::raw('COUNT(tr.id) as total_respuestas')
        )->first();

        // Estadísticas por área
        $areas = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->join('areas as a', 'q.area_id', '=', 'a.id')
            ->where('ta.user_id', $profesor->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8)
            ->select(
                'a.id as area_id',
                'a.name as area_name',
                DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones'),
                DB::raw('AVG(tr.score) as promedio_score'),
                DB::raw('MAX(tr.score) as max_score'),
                DB::raw('MIN(tr.score) as min_score'),
                DB::raw('COUNT(tr.id) as total_respuestas')
            )
            ->groupBy('a.id', 'a.name')
            ->get()
            ->map(function ($area) {
                return [
                    'area_name' => $area->area_name,
                    'total_evaluaciones' => $area->total_evaluaciones,
                    'promedio_score' => round($area->promedio_score, 2),
                    'max_score' => $area->max_score,
                    'min_score' => $area->min_score,
                    'total_respuestas' => $area->total_respuestas,
                ];
            });

        // Historial de evaluaciones
        $historial = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->join('areas as a', 'q.area_id', '=', 'a.id')
            ->where('ta.user_id', $profesor->id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8)
            ->select(
                'ta.created_at',
                'a.name as area_name',
                DB::raw('AVG(tr.score) as promedio_score'),
                DB::raw('COUNT(tr.id) as total_respuestas')
            )
            ->groupBy('ta.created_at', 'a.name')
            ->orderBy('ta.created_at')
            ->get()
            ->map(function ($evaluacion) {
                return [
                    'fecha' => \Carbon\Carbon::parse($evaluacion->created_at)->format('d/m/Y'),
                    'area' => $evaluacion->area_name,
                    'score' => $evaluacion->promedio_score,
                    'total_respuestas' => $evaluacion->total_respuestas,
                ];
            });

        // Comparación con otros profesores del mismo programa
        $comparacion = DB::table('test_assignments as ta')
            ->join('users as u', 'ta.user_id', '=', 'u.id')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->where('u.programa_id', $profesor->programa_id)
            ->where('ta.status', 'completed')
            ->where('q.area_id', '!=', 8)
            ->select(
                DB::raw('AVG(tr.score) as promedio_programa'),
                DB::raw('MAX(tr.score) as max_programa'),
                DB::raw('MIN(tr.score) as min_programa')
            )
            ->first();

        return [
            'profesor' => $profesor,
            'stats' => $stats,
            'areas' => $areas,
            'historial' => $historial,
            'comparacion' => $comparacion,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
    }

    private function generateUniversidadPDF(Institution $institution, array $data)
    {
        return PDF::loadView('reports.universidad', $data);
    }

    private function generateProfesorPDF(User $profesor, array $data)
    {
        return PDF::loadView('reports.profesor', $data);
    }

    public function getProfesoresCompletadosData(array $parameters = [])
    {
        $user = auth()->user();
        $filtro = $parameters['filtro'] ?? 'todos';
        
        \Log::info('getProfesoresCompletadosData iniciado', [
            'user_id' => $user->id,
            'user_role' => $user->roles->pluck('name'),
            'filtro' => $filtro,
            'parameters' => $parameters
        ]);
        
        // Query base para obtener profesores con paginación para optimizar memoria
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'Docente');
        })->with(['institution', 'facultad', 'programa', 'testAssignments']);
        
        // Aplicar filtros según el rol del usuario
        if ($user->hasRole('Coordinador')) {
            if ($user->institution_id) {
                $query->where('institution_id', $user->institution_id);
            }
            if ($user->facultad_id) {
                $query->where('facultad_id', $user->facultad_id);
            }
            // El coordinador puede ver todos los docentes de su facultad, no solo de su programa específico
        }
        
        // Obtener total de profesores para estadísticas
        $totalProfesores = $query->count();
        
        \Log::info('Profesores encontrados', [
            'total_profesores' => $totalProfesores
        ]);
        
        // Procesar profesores en lotes para optimizar memoria
        $datosReporte = collect();
        $profesoresCompletados = 0;
        $profesoresPendientes = 0;
        $totalTestsCompletados = 0;
        
        $query->chunk(50, function($profesores) use (&$datosReporte, &$profesoresCompletados, &$profesoresPendientes, &$totalTestsCompletados, $parameters, $filtro) {
            foreach ($profesores as $profesor) {
                // Filtrar test assignments por fecha si se especifican
                $testAssignments = $profesor->testAssignments;
                if (isset($parameters['date_from']) || isset($parameters['date_to'])) {
                    $testAssignments = $testAssignments->filter(function($assignment) use ($parameters) {
                        $createdAt = $assignment->created_at;
                        

                        
                        if (isset($parameters['date_from'])) {
                            $dateFrom = \Carbon\Carbon::parse($parameters['date_from'])->startOfDay();
                            if ($createdAt < $dateFrom) {
                                return false;
                            }
                        }
                        if (isset($parameters['date_to'])) {
                            $dateTo = \Carbon\Carbon::parse($parameters['date_to'])->endOfDay();
                            if ($createdAt > $dateTo) {
                                return false;
                            }
                        }
                        return true;
                    });
                }
                
                $testCompletados = $testAssignments->where('status', 'completed');
                $testPendientes = $testAssignments->where('status', 'pending');
                $testEnProgreso = $testAssignments->where('status', 'in_progress');
                
                $profesorData = [
                    'id' => $profesor->id,
                    'identificacion' => $profesor->document_number ?? 'N/A',
                    'email' => $profesor->email,
                    'nombres_completos' => $profesor->full_name,
                    'programa' => $profesor->programa ? $profesor->programa->nombre : 'N/A',
                    'facultad' => $profesor->facultad ? $profesor->facultad->nombre : 'N/A',
                    'institucion' => $profesor->institution ? $profesor->institution->name : 'N/A',
                    'total_tests' => $testAssignments->count(),
                    'tests_completados' => $testCompletados->count(),
                    'tests_pendientes' => $testPendientes->count(),
                    'tests_en_progreso' => $testEnProgreso->count(),
                    'ultimo_test' => $testCompletados->sortByDesc('created_at')->first()?->created_at?->format('d/m/Y H:i'),
                    'estado' => $testCompletados->count() > 0 ? 'Completado' : 'Pendiente'
                ];
                
                // Aplicar filtro
                $incluir = true;
                if ($filtro !== 'todos') {
                    switch ($filtro) {
                        case 'completados':
                            $incluir = $profesorData['tests_completados'] > 0;
                            break;
                        case 'pendientes':
                            $incluir = $profesorData['tests_completados'] === 0;
                            break;
                    }
                }
                

                
                if ($incluir) {
                    $datosReporte->push($profesorData);
                    
                    // Actualizar contadores
                    if ($profesorData['tests_completados'] > 0) {
                        $profesoresCompletados++;
                    } else {
                        $profesoresPendientes++;
                    }
                    $totalTestsCompletados += $profesorData['tests_completados'];
                }
                
                // Liberar memoria
                unset($profesor->testAssignments);
                unset($profesor->institution);
                unset($profesor->facultad);
                unset($profesor->programa);
            }
        });
        
                        // Si no hay datos que cumplan el filtro específico, mantener la colección vacía
                // pero agregar una bandera para indicar que no hay datos
                if ($datosReporte->count() === 0 && $filtro !== 'todos') {
                    \Log::info('No hay datos que cumplan el filtro seleccionado');
                }
        
        $resultado = [
            'profesores' => $datosReporte,
            'total_profesores' => $datosReporte->count(),
            'profesores_completados' => $profesoresCompletados,
            'profesores_pendientes' => $profesoresPendientes,
            'total_tests_completados' => $totalTestsCompletados,
            'filtro_aplicado' => $filtro,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
        
        \Log::info('Resultado final', [
            'total_profesores' => $resultado['total_profesores'],
            'profesores_completados' => $resultado['profesores_completados'],
            'profesores_pendientes' => $resultado['profesores_pendientes'],
            'total_tests_completados' => $resultado['total_tests_completados']
        ]);
        
        return $resultado;
    }

    private function getProfesoresCompletadosPreviewData(array $parameters = [])
    {
        $data = $this->getProfesoresCompletadosData($parameters);
        
        return [
            'profesores' => $data['profesores']->take(5), // Solo mostrar los primeros 5 para la vista previa
            'total_profesores' => $data['total_profesores'],
            'profesores_completados' => $data['profesores_completados'],
            'profesores_pendientes' => $data['profesores_pendientes'],
            'total_tests_completados' => $data['total_tests_completados'],
            'filtro_aplicado' => $data['filtro_aplicado'],
            'fecha_generacion' => $data['fecha_generacion'],
            'parametros' => $data['parametros'],
            'es_vista_previa' => true,
        ];
    }

    public function generateProfesoresCompletadosPDF(array $data)
    {
        // Configurar PDF para usar menos memoria
        $pdf = PDF::loadView('reports.profesores-completados', compact('data'));
        
        // Configuraciones para optimizar memoria
        $pdf->setOption('isRemoteEnabled', false);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isPhpEnabled', false);
        $pdf->setOption('memoryLimit', '256M');
        
        return $pdf;
    }
} 