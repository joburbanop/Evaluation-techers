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
} 