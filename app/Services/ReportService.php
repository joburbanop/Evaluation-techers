<?php

namespace App\Services;

use App\Models\Report;
use App\Models\TestAssignment;
use App\Models\Facultad;
use App\Models\Programa;
use App\Models\Institution;
use App\Models\TestCompetencyLevel;
use App\Models\TestAreaCompetencyLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use Carbon\Carbon;

class ReportService
{
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

    private function getFacultadData(Facultad $facultad, array $parameters = [])
    {
        $query = TestAssignment::with([
            'user.programa',
            'test.questions.area',
            'responses.option.question.area',
            'responses.option'
        ])
        ->whereHas('user.programa', function ($q) use ($facultad) {
            $q->where('facultad_id', $facultad->id);
        })
        ->where('status', 'completed');

        // Aplicar filtros de fecha si se especifican
        if (isset($parameters['date_from'])) {
            $query->where('created_at', '>=', $parameters['date_from']);
        }
        if (isset($parameters['date_to'])) {
            $query->where('created_at', '<=', $parameters['date_to']);
        }

        $assignments = $query->get();

        // Estadísticas generales
        $totalEvaluaciones = $assignments->count();
        $totalUsuarios = $assignments->unique('user_id')->count();
        
        // Calcular estadísticas por área
        $areas = \App\Models\Area::where('id', '!=', 8)->get(); // Excluir área sociodemográfica
        $areaStats = [];
        
        foreach ($areas as $area) {
            $areaAssignments = $assignments->filter(function ($assignment) use ($area) {
                return $assignment->responses->where('question.area_id', $area->id)->count() > 0;
            });
            
            if ($areaAssignments->count() > 0) {
                $scores = $areaAssignments->map(function ($assignment) use ($area) {
                    $areaResponses = $assignment->responses->where('question.area_id', $area->id);
                    return $areaResponses->sum('option.score');
                });
                
                $areaStats[$area->name] = [
                    'total_evaluaciones' => $areaAssignments->count(),
                    'promedio_score' => round($scores->avg(), 2),
                    'max_score' => $scores->max(),
                    'min_score' => $scores->min(),
                    'niveles' => $this->calculateNiveles($areaAssignments, $facultad->id, $area->id),
                ];
            }
        }

        // Estadísticas por programa
        $programas = $facultad->programas;
        $programaStats = [];
        
        foreach ($programas as $programa) {
            $programaAssignments = $assignments->where('user.programa_id', $programa->id);
            
            if ($programaAssignments->count() > 0) {
                $scores = $programaAssignments->map(function ($assignment) {
                    $nonSociodemographicResponses = $assignment->responses->filter(function($response) {
                        return $response->question->area_id !== 8;
                    });
                    return $nonSociodemographicResponses->sum('option.score');
                });
                
                $programaStats[$programa->nombre] = [
                    'total_evaluaciones' => $programaAssignments->count(),
                    'promedio_score' => round($scores->avg(), 2),
                    'max_score' => $scores->max(),
                    'min_score' => $scores->min(),
                    'niveles' => $this->calculateNiveles($programaAssignments, $facultad->id, null, $programa->id),
                ];
            }
        }

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
        $query = TestAssignment::with([
            'user',
            'test.questions.area',
            'responses.option.question.area',
            'responses.option'
        ])
        ->whereHas('user', function ($q) use ($programa) {
            $q->where('programa_id', $programa->id);
        })
        ->where('status', 'completed');

        // Aplicar filtros de fecha si se especifican
        if (isset($parameters['date_from'])) {
            $query->where('created_at', '>=', $parameters['date_from']);
        }
        if (isset($parameters['date_to'])) {
            $query->where('created_at', '<=', $parameters['date_to']);
        }

        $assignments = $query->get();

        // Estadísticas generales
        $totalEvaluaciones = $assignments->count();
        $totalUsuarios = $assignments->unique('user_id')->count();
        
        // Calcular estadísticas por área
        $areas = \App\Models\Area::where('id', '!=', 8)->get();
        $areaStats = [];
        
        foreach ($areas as $area) {
            $areaAssignments = $assignments->filter(function ($assignment) use ($area) {
                return $assignment->responses->where('question.area_id', $area->id)->count() > 0;
            });
            
            if ($areaAssignments->count() > 0) {
                $scores = $areaAssignments->map(function ($assignment) use ($area) {
                    $areaResponses = $assignment->responses->where('question.area_id', $area->id);
                    return $areaResponses->sum('option.score');
                });
                
                $areaStats[$area->name] = [
                    'total_evaluaciones' => $areaAssignments->count(),
                    'promedio_score' => round($scores->avg(), 2),
                    'max_score' => $scores->max(),
                    'min_score' => $scores->min(),
                    'niveles' => $this->calculateNiveles($areaAssignments, null, $area->id, $programa->id),
                ];
            }
        }

        // Top 10 mejores evaluados
        $topEvaluados = $assignments->map(function ($assignment) {
            $nonSociodemographicResponses = $assignment->responses->filter(function($response) {
                return $response->question->area_id !== 8;
            });
            $score = $nonSociodemographicResponses->sum('option.score');
            
            return [
                'user' => $assignment->user,
                'score' => $score,
                'fecha' => $assignment->created_at,
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

    private function generateFacultadPDF(Facultad $facultad, array $data)
    {
        return PDF::loadView('reports.facultad', $data);
    }

    private function generateProgramaPDF(Programa $programa, array $data)
    {
        return PDF::loadView('reports.programa', $data);
    }
} 