<?php

namespace App\Services;

use App\Models\Report;
use App\Models\Test;
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
use Illuminate\Support\Facades\Cache;
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
            // Usar getPreviewData para obtener el formato correcto
            $previewData = $this->getFacultadPreviewData($facultad, $parameters);
            $pdf = PDF::loadView('reports.facultad', compact('previewData'));
            $pdf->setPaper('A4', 'landscape');
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            
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
            // Usar getPreviewData para obtener el formato correcto
            $previewData = $this->getProgramaPreviewData($programa, $parameters);
            $pdf = PDF::loadView('reports.programa', compact('previewData'));
            $pdf->setPaper('A4', 'landscape');
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            
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
        // ✅ OPTIMIZACIÓN 25: Cache de resultados por 1 hora
        $cacheKey = "universidad_report_{$institution->id}_" . md5(json_encode($parameters));
        
        // Intentar obtener del cache primero
        $cachedReport = Cache::get($cacheKey);
        if ($cachedReport) {
            return $cachedReport;
        }
        
        $report = Report::create([
            'name' => "Reporte de Universidad: {$institution->name}",
            'type' => 'universidad',
            'entity_id' => $institution->id,
            'entity_type' => Institution::class,
            'generated_by' => auth()->id(),
            'parameters' => $parameters,
            'status' => 'queued', // Cambiar a 'queued' para procesamiento asíncrono
        ]);

        // ✅ OPTIMIZACIÓN 28: Procesamiento asíncrono para reportes pesados
        if ($this->shouldUseAsyncProcessing($institution)) {
            GenerateReportJob::dispatch(
                $report->id,
                'universidad',
                $institution->id,
                Institution::class,
                $parameters
            )->onQueue('reports');
            
            return $report;
        }

        try {
            // ✅ OPTIMIZACIÓN 26: Usar getPreviewData optimizado
            $previewData = $this->getUniversidadPreviewData($institution, $parameters);
            $pdf = PDF::loadView('reports.universidad', compact('previewData'));
            $pdf->setPaper('A4', 'landscape');
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            
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
            // Usar getPreviewData para obtener el formato correcto
            $previewData = $this->getProfesorPreviewData($profesor, $parameters);
            $pdf = PDF::loadView('reports.profesor', compact('previewData'));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            
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
        try {
            // ✅ OPTIMIZACIÓN 1: Obtener programas con consulta directa
            $programasFacultad = Programa::where('facultad_id', $facultad->id)->get();
            $totalProgramas = $programasFacultad->count();
            
            // ✅ OPTIMIZACIÓN 2: Obtener profesores con consulta optimizada
            $profesoresFacultad = User::whereHas('roles', function($q) {
                $q->where('name', 'Docente');
            })->where('facultad_id', $facultad->id)->get();
            
            $totalProfesores = $profesoresFacultad->count();
            
            // ✅ OPTIMIZACIÓN 3: Obtener tests activos
            $testsActivos = Test::where('is_active', true)->get();
            $totalTestsActivos = $testsActivos->count();
            
            // ✅ OPTIMIZACIÓN 4: Eager Loading Inteligente con todas las asignaciones
            $asignacionesFacultad = TestAssignment::whereHas('user', function($q) use ($facultad) {
                $q->where('facultad_id', $facultad->id);
            })->with([
                'user.programa',
                'user.roles',
                'test.questions.options',
                'responses.question'
            ])->get();
            
            // ✅ OPTIMIZACIÓN 5: Agrupar asignaciones para acceso O(1)
            $asignacionesAgrupadas = $asignacionesFacultad->groupBy(['test_id', 'user_id']);
            $asignacionesCompletadas = $asignacionesFacultad->where('status', 'completed');
            
            // ✅ OPTIMIZACIÓN 6: Liberar memoria
            unset($asignacionesFacultad);
            gc_collect_cycles();
        
        // Contar profesores que han completado todos los tests
        $profesoresCompletados = $profesoresFacultad->filter(function($profesor) use ($testsActivos, $asignacionesCompletadas, $totalTestsActivos) {
            $testsCompletadosPorProfesor = $asignacionesCompletadas
                ->where('user_id', $profesor->id)
                ->count();
            return $testsCompletadosPorProfesor >= $totalTestsActivos;
        });
        
        $totalProfesoresCompletados = $profesoresCompletados->count();
        $totalProfesoresPendientes = $totalProfesores - $totalProfesoresCompletados;
        
        // Obtener evaluaciones por área para la facultad
        $query = EvaluacionPorArea::byFacultad($facultad->id);
        $evaluaciones = $query->get();
        
        // Calcular estadísticas generales de la facultad
        $promedioFacultad = $evaluaciones->avg('score') ?? 0;
        $puntuacionMaxima = $evaluaciones->max('score') ?? 0;
        $puntuacionMinima = $evaluaciones->min('score') ?? 0;
        $fechaAplicacion = now()->format('d/m/Y');
        
        // ✅ OPTIMIZACIÓN 7: Cache de puntajes máximos de tests
        $puntajesMaximos = [];
        foreach ($testsActivos as $test) {
            $cacheKey = "test_max_score_{$test->id}";
            $puntajesMaximos[$test->id] = Cache::remember($cacheKey, 3600, function() use ($test) {
                return DB::table('questions as q')
                    ->join('options as o', 'q.id', '=', 'o.question_id')
                    ->where('q.test_id', $test->id)
                    ->groupBy('q.id')
                    ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                    ->get()
                    ->sum('max_score_per_question');
            });
        }
        
        // ✅ OPTIMIZACIÓN 8: Calcular promedios por test de forma optimizada
        $totalPuntajeObtenidoFacultad = 0;
        $totalPuntajeMaximoFacultad = 0;
        $promediosPorTest = [];
        
        foreach ($testsActivos as $test) {
            $puntajeObtenidoTest = 0;
            $profesoresConTest = 0;
            $puntajeMaximoTest = $puntajesMaximos[$test->id];
            
            // ✅ OPTIMIZACIÓN 9: Usar asignaciones agrupadas en lugar de consultas individuales
            if (isset($asignacionesAgrupadas[$test->id])) {
                foreach ($asignacionesAgrupadas[$test->id] as $userId => $assignments) {
                    $profesoresConTest++;
                    $assignment = $assignments->first();
                    
                    if ($assignment && $assignment->status === 'completed') {
                        // ✅ OPTIMIZACIÓN 10: Cache de puntajes por asignación
                        $puntajeCacheKey = "assignment_score_{$assignment->id}";
                        $puntaje = Cache::remember($puntajeCacheKey, 1800, function() use ($assignment) {
                            return DB::table('test_responses as tr')
                                ->where('test_assignment_id', $assignment->id)
                                ->sum('tr.score');
                        });
                        $puntajeObtenidoTest += $puntaje;
                    }
                }
            }
            
            $puntajeMaximoTotalTest = $puntajeMaximoTest * $profesoresConTest;
            $totalPuntajeObtenidoFacultad += $puntajeObtenidoTest;
            $totalPuntajeMaximoFacultad += $puntajeMaximoTotalTest;
            
            $promedioTest = $puntajeMaximoTotalTest > 0 ? round(($puntajeObtenidoTest / $puntajeMaximoTotalTest) * 100, 2) : 0;
            $promediosPorTest[] = [
                'test_name' => $test->name,
                'promedio' => $promedioTest
            ];
        }
        
        // Calcular promedio general de la facultad
        $promedioFacultad = $totalPuntajeMaximoFacultad > 0 ? round(($totalPuntajeObtenidoFacultad / $totalPuntajeMaximoFacultad) * 100, 2) : 0;
        
        // ✅ OPTIMIZACIÓN 11: Calcular resultados por programa usando datos ya cargados
        $resultadosPorPrograma = $programasFacultad->map(function($programa) use ($asignacionesCompletadas, $totalTestsActivos, $testsActivos, $asignacionesAgrupadas, $puntajesMaximos, $profesoresFacultad) {
            // ✅ OPTIMIZACIÓN 12: Usar profesores ya filtrados por programa
            $profesoresPrograma = $profesoresFacultad->where('programa_id', $programa->id);
            $totalProfesoresPrograma = $profesoresPrograma->count();
            
            // ✅ OPTIMIZACIÓN 13: Contar profesores completados eficientemente
            $profesoresCompletadosPrograma = $profesoresPrograma->filter(function($profesor) use ($asignacionesCompletadas, $totalTestsActivos) {
                $testsCompletadosPorProfesor = $asignacionesCompletadas
                    ->where('user_id', $profesor->id)
                    ->count();
                return $testsCompletadosPorProfesor >= $totalTestsActivos;
            });
            
            $totalProfesoresCompletadosPrograma = $profesoresCompletadosPrograma->count();
            $totalProfesoresPendientesPrograma = $totalProfesoresPrograma - $totalProfesoresCompletadosPrograma;
            
            // ✅ OPTIMIZACIÓN 14: Calcular promedio usando asignaciones agrupadas y puntajes cacheados
            $totalPuntajeObtenidoPrograma = 0;
            $totalPuntajeMaximoPrograma = 0;
            
            foreach ($testsActivos as $test) {
                $profesoresConTest = 0;
                $puntajeMaximoTest = $puntajesMaximos[$test->id];
                
                foreach ($profesoresPrograma as $profesor) {
                    // ✅ OPTIMIZACIÓN 15: Usar asignaciones agrupadas en lugar de consultas
                    if (isset($asignacionesAgrupadas[$test->id][$profesor->id])) {
                        $profesoresConTest++;
                        $assignment = $asignacionesAgrupadas[$test->id][$profesor->id]->first();
                        
                        if ($assignment && $assignment->status === 'completed') {
                            $puntajeCacheKey = "assignment_score_{$assignment->id}";
                            $puntaje = Cache::remember($puntajeCacheKey, 1800, function() use ($assignment) {
                                return DB::table('test_responses as tr')
                                    ->where('test_assignment_id', $assignment->id)
                                    ->sum('tr.score');
                            });
                            $totalPuntajeObtenidoPrograma += $puntaje;
                        }
                    }
                }
                
                $totalPuntajeMaximoPrograma += $puntajeMaximoTest * $profesoresConTest;
            }
            
            $promedioGeneralPrograma = $totalPuntajeMaximoPrograma > 0 ? round(($totalPuntajeObtenidoPrograma / $totalPuntajeMaximoPrograma) * 100, 2) : 0;
            
            // ✅ OPTIMIZACIÓN 16: Calcular promedios por test de forma optimizada
            $promediosPorTestPrograma = [];
            foreach ($testsActivos as $test) {
                $puntajeObtenidoTest = 0;
                $profesoresConTest = 0;
                $puntajeMaximoTest = $puntajesMaximos[$test->id];
                
                foreach ($profesoresPrograma as $profesor) {
                    if (isset($asignacionesAgrupadas[$test->id][$profesor->id])) {
                        $profesoresConTest++;
                        $assignment = $asignacionesAgrupadas[$test->id][$profesor->id]->first();
                        
                        if ($assignment && $assignment->status === 'completed') {
                            $puntajeCacheKey = "assignment_score_{$assignment->id}";
                            $puntaje = Cache::remember($puntajeCacheKey, 1800, function() use ($assignment) {
                                return DB::table('test_responses as tr')
                                    ->where('test_assignment_id', $assignment->id)
                                    ->sum('tr.score');
                            });
                            $puntajeObtenidoTest += $puntaje;
                        }
                    }
                }
                
                $puntajeMaximoTotalTest = $puntajeMaximoTest * $profesoresConTest;
                $promedioTest = $puntajeMaximoTotalTest > 0 ? round(($puntajeObtenidoTest / $puntajeMaximoTotalTest) * 100, 2) : 0;
                $promediosPorTestPrograma[] = [
                    'test_name' => $test->name,
                    'promedio' => $promedioTest
                ];
            }
            
            return [
                'programa' => $programa,
                'nombre_programa' => $programa->nombre,
                'total_profesores' => $totalProfesoresPrograma,
                'profesores_completados' => $totalProfesoresCompletadosPrograma,
                'profesores_pendientes' => $totalProfesoresPendientesPrograma,
                'promedio_general' => $promedioGeneralPrograma,
                'promedios_por_test' => $promediosPorTestPrograma,
                'ha_completado_todos' => $totalProfesoresCompletadosPrograma > 0,
                'tests_completados' => $totalProfesoresCompletadosPrograma,
                'total_tests' => $totalProfesoresPrograma
            ];
        })->sortByDesc('promedio_general')->values();
        
        return [
            'entidad' => $facultad,
            'facultad' => [
                'id' => $facultad->id,
                'nombre' => $facultad->nombre
            ],
            'institution' => $facultad->institution ? [
                'id' => $facultad->institution->id,
                'name' => $facultad->institution->name
            ] : null,
            'total_programas' => $totalProgramas,
            'total_profesores' => $totalProfesores,
            'total_profesores_completados' => $totalProfesoresCompletados,
            'total_profesores_pendientes' => $totalProfesoresPendientes,
            'promedio_facultad' => round($promedioFacultad, 2),
            'promedios_por_test' => $promediosPorTest,
            'puntuacion_maxima' => $puntuacionMaxima,
            'puntuacion_minima' => $puntuacionMinima,
            'fecha_aplicacion' => $fechaAplicacion,
            'resultados_por_programa' => $resultadosPorPrograma,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
        
        } catch (\Exception $e) {
            \Log::error('Error en getFacultadPreviewData:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Error al obtener datos de la facultad: ' . $e->getMessage());
        }
    }

    private function getProgramaPreviewData(Programa $programa, array $parameters = [])
    {
        // Obtener todas las áreas disponibles del sistema PRIMERO (excluyendo Información Socio-demográfica y Educación Abierta)
        $todasLasAreas = \App\Models\Area::whereNotIn('name', ['Información Socio-demográfica', 'Educación Abierta'])->get();
        
        // Obtener todos los profesores del programa
        $profesoresPrograma = User::whereHas('roles', function($q) {
            $q->where('name', 'Docente');
        })->where('programa_id', $programa->id)->get();
        
        $totalProfesores = $profesoresPrograma->count();
        
        // Obtener todos los tests activos
        $testsActivos = Test::where('is_active', true)->get();
        $totalTestsActivos = $testsActivos->count();
        
        // Obtener asignaciones completadas por profesor en este programa
        $asignacionesCompletadas = TestAssignment::whereHas('user', function($q) use ($programa) {
            $q->where('programa_id', $programa->id);
        })->where('status', 'completed')->get();
        
        // Contar profesores que han completado todos los tests
        $profesoresCompletados = $profesoresPrograma->filter(function($profesor) use ($testsActivos, $asignacionesCompletadas, $totalTestsActivos) {
            $testsCompletadosPorProfesor = $asignacionesCompletadas
                ->where('user_id', $profesor->id)
                ->count();
            return $testsCompletadosPorProfesor >= $totalTestsActivos;
        });
        
        $totalProfesoresCompletados = $profesoresCompletados->count();
        $totalProfesoresPendientes = $totalProfesores - $totalProfesoresCompletados;
        
        // Obtener evaluaciones por área para el programa
        $query = EvaluacionPorArea::byPrograma($programa->id);
        
        $evaluaciones = $query->get();
        
        // Calcular estadísticas generales del programa
        $promedioPrograma = $evaluaciones->avg('score') ?? 0;
        $puntuacionMaxima = $evaluaciones->max('score') ?? 0;
        $puntuacionMinima = $evaluaciones->min('score') ?? 0;
        $fechaAplicacion = now()->format('d/m/Y');
        
        // Calcular promedio del programa basado en TODOS los tests asignados a TODOS los profesores
        $totalPuntajeObtenidoPrograma = 0;
        $totalPuntajeMaximoPrograma = 0;
        $promediosPorTest = [];
        
        // Calcular promedios por test individual
        foreach ($testsActivos as $test) {
            $puntajeObtenidoTest = 0;
            $puntajeMaximoTest = 0;
            $profesoresConTest = 0;
            
            foreach ($profesoresPrograma as $profesor) {
                $assignment = TestAssignment::where('user_id', $profesor->id)
                    ->where('test_id', $test->id)
                    ->first();
                
                if ($assignment) {
                    $profesoresConTest++;
                    
                    if ($assignment->status === 'completed') {
                        $puntaje = DB::table('test_responses as tr')
                            ->where('test_assignment_id', $assignment->id)
                            ->sum('tr.score');
                        $puntajeObtenidoTest += $puntaje;
                    }
                    
                    // Calcular puntaje máximo para este test
                    $puntajeMaximo = DB::table('questions as q')
                        ->join('options as o', 'q.id', '=', 'o.question_id')
                        ->where('q.test_id', $test->id)
                        ->groupBy('q.id')
                        ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                        ->get()
                        ->sum('max_score_per_question');
                    
                    $puntajeMaximoTest += $puntajeMaximo;
                }
            }
            
            // Agregar a totales del programa
            $totalPuntajeObtenidoPrograma += $puntajeObtenidoTest;
            $totalPuntajeMaximoPrograma += $puntajeMaximoTest;
            
            // Calcular promedio para este test
            $promedioTest = $puntajeMaximoTest > 0 ? round(($puntajeObtenidoTest / $puntajeMaximoTest) * 100, 2) : 0;
            $promediosPorTest[] = [
                'test_name' => $test->name,
                'promedio' => $promedioTest
            ];
        }
        
        // Calcular promedio general del programa
        $promedioPrograma = $totalPuntajeMaximoPrograma > 0 ? round(($totalPuntajeObtenidoPrograma / $totalPuntajeMaximoPrograma) * 100, 2) : 0;
        
        // Obtener resultados por profesor ordenados de mayor a menor - TODOS los profesores
        $resultadosPorProfesor = $profesoresPrograma->map(function($profesor) use ($asignacionesCompletadas, $totalTestsActivos, $todasLasAreas) {
            // Obtener todos los tests asignados al profesor
            $testsAsignados = TestAssignment::where('user_id', $profesor->id)
                ->with(['test'])
                ->get();
            
            $testsCompletadosPorProfesor = $testsAsignados->where('status', 'completed')->count();
            $haCompletadoTodos = $testsCompletadosPorProfesor >= $totalTestsActivos;
            
            // Calcular promedio general basado en TODOS los tests asignados (completados y pendientes)
            $promedioGeneral = 0;
            $totalPuntajeObtenido = 0;
            $totalPuntajeMaximo = 0;
            
            // Sumar puntajes de tests completados
            foreach ($testsAsignados->where('status', 'completed') as $assignment) {
                $puntaje = DB::table('test_responses as tr')
                    ->where('test_assignment_id', $assignment->id)
                    ->sum('tr.score');
                
                $puntajeMaximo = DB::table('test_assignments as ta')
                    ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
                    ->join('questions as q', 'tr.question_id', '=', 'q.id')
                    ->join('options as o', 'q.id', '=', 'o.question_id')
                    ->where('ta.id', $assignment->id)
                    ->where('ta.status', 'completed')
                    ->groupBy('q.id')
                    ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                    ->get()
                    ->sum('max_score_per_question');
                
                $totalPuntajeObtenido += $puntaje;
                $totalPuntajeMaximo += $puntajeMaximo;
            }
            
            // Sumar puntajes máximos de tests pendientes
            foreach ($testsAsignados as $assignment) {
                if ($assignment->status !== 'completed') {
                    $puntajeMaximoPendiente = DB::table('test_assignments as ta')
                        ->join('questions as q', 'ta.test_id', '=', 'q.test_id')
                        ->join('options as o', 'q.id', '=', 'o.question_id')
                        ->where('ta.id', $assignment->id)
                        ->groupBy('q.id')
                        ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                        ->get()
                        ->sum('max_score_per_question');
                    
                    $totalPuntajeMaximo += $puntajeMaximoPendiente;
                }
            }
            
            $promedioGeneral = $totalPuntajeMaximo > 0 ? round(($totalPuntajeObtenido / $totalPuntajeMaximo) * 100, 2) : 0;
            
            // Obtener resultados por área (global para el profesor)
            $resultadosPorArea = $todasLasAreas->map(function($area) use ($profesor) {
                $puntajeObtenido = DB::table('test_assignments as ta')
                    ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
                    ->join('questions as q', 'tr.question_id', '=', 'q.id')
                    ->where('ta.user_id', $profesor->id)
                    ->where('ta.status', 'completed')
                    ->where('q.area_id', $area->id)
                    ->sum('tr.score');
                
                $puntajeMaximo = DB::table('test_assignments as ta')
                    ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
                    ->join('questions as q', 'tr.question_id', '=', 'q.id')
                    ->join('options as o', 'q.id', '=', 'o.question_id')
                    ->where('ta.user_id', $profesor->id)
                    ->where('ta.status', 'completed')
                    ->where('q.area_id', $area->id)
                    ->groupBy('q.id')
                    ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                    ->get()
                    ->sum('max_score_per_question');
                
                $porcentaje = $puntajeMaximo > 0 ? round(($puntajeObtenido / $puntajeMaximo) * 100, 1) : 0;
                
                return [
                    'area_name' => $area->name,
                    'area_id' => $area->id,
                    'puntaje' => floor($puntajeObtenido),
                    'total_posible' => floor($puntajeMaximo),
                    'porcentaje' => $porcentaje
                ];
            })->toArray();
            
            // Obtener información de tests individuales
            $testsIndividuales = $testsAsignados->where('status', 'completed')->map(function ($assignment) {
                $puntaje = DB::table('test_responses as tr')
                    ->where('test_assignment_id', $assignment->id)
                    ->sum('tr.score');
                
                $puntajeMaximo = DB::table('test_assignments as ta')
                    ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
                    ->join('questions as q', 'tr.question_id', '=', 'q.id')
                    ->join('options as o', 'q.id', '=', 'o.question_id')
                    ->where('ta.id', $assignment->id)
                    ->where('ta.status', 'completed')
                    ->groupBy('q.id')
                    ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                    ->get()
                    ->sum('max_score_per_question');
                
                $porcentaje = $puntajeMaximo > 0 ? round(($puntaje / $puntajeMaximo) * 100, 1) : 0;
                
                return [
                    'test_name' => $assignment->test->name,
                    'puntaje' => floor($puntaje),
                    'puntaje_maximo' => floor($puntajeMaximo),
                    'porcentaje' => $porcentaje
                ];
            })->toArray();
            
            return [
                'profesor' => $profesor,
                'nombre_completo' => "{$profesor->name} {$profesor->apellido1}",
                'promedio_general' => $promedioGeneral,
                'ha_completado_todos' => $haCompletadoTodos,
                'tests_completados' => $testsCompletadosPorProfesor,
                'total_tests' => $totalTestsActivos,
                'resultados_por_area' => $resultadosPorArea,
                'tests_individuales' => $testsIndividuales
            ];
        })->sortByDesc('promedio_general')->values();
        
        // Calcular estadísticas por área
        $areaStats = $todasLasAreas->map(function ($area) use ($evaluaciones) {
            $areaEvaluaciones = $evaluaciones->where('area_id', $area->id);
            return [
                'area_name' => $area->name,
                'area_id' => $area->id,
                'total_evaluaciones' => $areaEvaluaciones->count(),
                'promedio_score' => round($areaEvaluaciones->avg('score'), 2),
                'max_score' => $areaEvaluaciones->max('score'),
                'min_score' => $areaEvaluaciones->min('score'),
            ];
        });

        return [
            'entidad' => $programa,
            'facultad' => $programa->facultad,
            'total_profesores' => $totalProfesores,
            'total_profesores_completados' => $totalProfesoresCompletados,
            'total_profesores_pendientes' => $totalProfesoresPendientes,
            'promedio_programa' => round($promedioPrograma, 2),
            'promedios_por_test' => $promediosPorTest,
            'puntuacion_maxima' => $puntuacionMaxima,
            'puntuacion_minima' => $puntuacionMinima,
            'fecha_aplicacion' => $fechaAplicacion,
            'resultados_por_profesor' => $resultadosPorProfesor,
            'area_stats' => $areaStats,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
            'is_preview' => true,
        ];
    }

    private function getUniversidadPreviewData(Institution $institution, array $parameters = [])
    {
        try {
            // ✅ OPTIMIZACIÓN 1: Obtener IDs de facultades reales de una vez
            $facultadIds = Facultad::where('institution_id', $institution->id)
                ->where(function($query) {
                    $query->where('nombre', 'like', '%Facultad%')
                          ->orWhere('nombre', 'like', '%School%')
                          ->orWhere('nombre', 'like', '%College%');
                })->pluck('id');
        
        $totalFacultades = $facultadIds->count();
        
        // ✅ OPTIMIZACIÓN 2: Obtener facultades con una sola consulta
        $facultadesInstitucion = Facultad::whereIn('id', $facultadIds)->get();
        
        // ✅ OPTIMIZACIÓN 3: Obtener programas con consulta optimizada
        $programasInstitucion = Programa::whereIn('facultad_id', $facultadIds)->get();
        $totalProgramas = $programasInstitucion->count();
        
        // ✅ OPTIMIZACIÓN 4: Obtener profesores con consulta directa
        $profesoresInstitucion = User::whereHas('roles', function($q) {
            $q->where('name', 'Docente');
        })->whereIn('facultad_id', $facultadIds)->get();
        
        $totalProfesores = $profesoresInstitucion->count();
        
        // ✅ OPTIMIZACIÓN 5: Obtener tests activos
        $testsActivos = Test::where('is_active', true)->get();
        $totalTestsActivos = $testsActivos->count();
        
        // ✅ OPTIMIZACIÓN 6: Eager Loading Inteligente con relaciones completas
        $asignacionesInstitucion = TestAssignment::whereHas('user', function($q) use ($facultadIds) {
            $q->whereIn('facultad_id', $facultadIds);
        })->with([
            'user.facultad',
            'user.programa', 
            'user.roles',
            'test.questions.options',
            'responses.question'
        ])->get();
        
        // ✅ OPTIMIZACIÓN 7: Agrupar asignaciones para acceso O(1) con optimización de memoria
        $asignacionesAgrupadas = $asignacionesInstitucion->groupBy(['test_id', 'user_id']);
        $asignacionesCompletadas = $asignacionesInstitucion->where('status', 'completed');
        
        // ✅ OPTIMIZACIÓN 29: Liberar memoria de colecciones grandes
        unset($asignacionesInstitucion);
        gc_collect_cycles();
        
        // ✅ OPTIMIZACIÓN 8: Contar profesores completados eficientemente
        $profesoresCompletados = $profesoresInstitucion->filter(function($profesor) use ($asignacionesCompletadas, $totalTestsActivos) {
            $testsCompletadosPorProfesor = $asignacionesCompletadas
                ->where('user_id', $profesor->id)
                ->count();
            return $testsCompletadosPorProfesor >= $totalTestsActivos;
        });
        
        $totalProfesoresCompletados = $profesoresCompletados->count();
        $totalProfesoresPendientes = $totalProfesores - $totalProfesoresCompletados;
        
        // ✅ OPTIMIZACIÓN 9: Obtener evaluaciones con filtro directo
        $evaluaciones = EvaluacionPorArea::byInstitution($institution->id)
            ->whereHas('facultad', function($q) use ($facultadIds) {
                $q->whereIn('id', $facultadIds);
            })->get();
        
        // ✅ OPTIMIZACIÓN 10: Calcular estadísticas básicas
        $promedioInstitucion = $evaluaciones->avg('score') ?? 0;
        $puntuacionMaxima = $evaluaciones->max('score') ?? 0;
        $puntuacionMinima = $evaluaciones->min('score') ?? 0;
        $fechaAplicacion = now()->format('d/m/Y');
        
        // ✅ OPTIMIZACIÓN 11: Cache de Consultas SQL con TTL de 1 hora
        $puntajesMaximos = [];
        foreach ($testsActivos as $test) {
            $cacheKey = "test_max_score_{$test->id}";
            $puntajesMaximos[$test->id] = Cache::remember($cacheKey, 3600, function() use ($test) {
                return DB::table('questions as q')
                    ->join('options as o', 'q.id', '=', 'o.question_id')
                    ->where('q.test_id', $test->id)
                    ->groupBy('q.id')
                    ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                    ->get()
                    ->sum('max_score_per_question');
            });
        }
        
        // ✅ OPTIMIZACIÓN 12: Calcular promedios por test de forma optimizada
        $totalPuntajeObtenidoInstitucion = 0;
        $totalPuntajeMaximoInstitucion = 0;
        $promediosPorTest = [];
        
        foreach ($testsActivos as $test) {
            $puntajeObtenidoTest = 0;
            $profesoresConTest = 0;
            $puntajeMaximoTest = $puntajesMaximos[$test->id];
            
            // ✅ OPTIMIZACIÓN 13: Usar asignaciones agrupadas en lugar de consultas individuales
            if (isset($asignacionesAgrupadas[$test->id])) {
                foreach ($asignacionesAgrupadas[$test->id] as $userId => $assignments) {
                    $profesoresConTest++;
                    $assignment = $assignments->first();
                    
                                            if ($assignment && $assignment->status === 'completed') {
                            // ✅ OPTIMIZACIÓN 14: Cache de puntajes por asignación
                            $puntajeCacheKey = "assignment_score_{$assignment->id}";
                            $puntaje = Cache::remember($puntajeCacheKey, 1800, function() use ($assignment) {
                                return DB::table('test_responses as tr')
                                    ->where('test_assignment_id', $assignment->id)
                                    ->sum('tr.score');
                            });
                            $puntajeObtenidoTest += $puntaje;
                        }
                }
            }
            
            $puntajeMaximoTotalTest = $puntajeMaximoTest * $profesoresConTest;
            $totalPuntajeObtenidoInstitucion += $puntajeObtenidoTest;
            $totalPuntajeMaximoInstitucion += $puntajeMaximoTotalTest;
            
            $promedioTest = $puntajeMaximoTotalTest > 0 ? round(($puntajeObtenidoTest / $puntajeMaximoTotalTest) * 100, 2) : 0;
            $promediosPorTest[] = [
                'test_name' => $test->name,
                'promedio' => $promedioTest
            ];
        }
        
        // Calcular promedio general de la institución
        $promedioInstitucion = $totalPuntajeMaximoInstitucion > 0 ? round(($totalPuntajeObtenidoInstitucion / $totalPuntajeMaximoInstitucion) * 100, 2) : 0;
        
        // ✅ OPTIMIZACIÓN 15: Calcular resultados por facultad usando datos ya cargados
        $resultadosPorFacultad = $facultadesInstitucion->map(function($facultad) use ($evaluaciones, $asignacionesCompletadas, $totalTestsActivos, $testsActivos, $asignacionesAgrupadas, $puntajesMaximos, $profesoresInstitucion) {
            // ✅ OPTIMIZACIÓN 16: Usar profesores ya filtrados por facultad
            $profesoresFacultad = $profesoresInstitucion->where('facultad_id', $facultad->id);
            $totalProfesoresFacultad = $profesoresFacultad->count();
            
            // ✅ OPTIMIZACIÓN 17: Contar profesores completados eficientemente
            $profesoresCompletadosFacultad = $profesoresFacultad->filter(function($profesor) use ($asignacionesCompletadas, $totalTestsActivos) {
                $testsCompletadosPorProfesor = $asignacionesCompletadas
                    ->where('user_id', $profesor->id)
                    ->count();
                return $testsCompletadosPorProfesor >= $totalTestsActivos;
            });
            
            $totalProfesoresCompletadosFacultad = $profesoresCompletadosFacultad->count();
            $totalProfesoresPendientesFacultad = $totalProfesoresFacultad - $totalProfesoresCompletadosFacultad;
            
            // ✅ OPTIMIZACIÓN 18: Calcular promedio usando asignaciones agrupadas y puntajes cacheados
            $totalPuntajeObtenidoFacultad = 0;
            $totalPuntajeMaximoFacultad = 0;
            
            foreach ($testsActivos as $test) {
                $profesoresConTest = 0;
                $puntajeMaximoTest = $puntajesMaximos[$test->id];
                
                foreach ($profesoresFacultad as $profesor) {
                    // ✅ OPTIMIZACIÓN 19: Usar asignaciones agrupadas en lugar de consultas
                    if (isset($asignacionesAgrupadas[$test->id][$profesor->id])) {
                        $profesoresConTest++;
                        $assignment = $asignacionesAgrupadas[$test->id][$profesor->id]->first();
                        
                        if ($assignment && $assignment->status === 'completed') {
                            $puntaje = DB::table('test_responses as tr')
                                ->where('test_assignment_id', $assignment->id)
                                ->sum('tr.score');
                            $totalPuntajeObtenidoFacultad += $puntaje;
                        }
                    }
                }
                
                $totalPuntajeMaximoFacultad += $puntajeMaximoTest * $profesoresConTest;
            }
            
            $promedioFacultad = $totalPuntajeMaximoFacultad > 0 ? round(($totalPuntajeObtenidoFacultad / $totalPuntajeMaximoFacultad) * 100, 2) : 0;
            
            return [
                'facultad' => $facultad,
                'nombre_facultad' => $facultad->nombre,
                'total_profesores' => $totalProfesoresFacultad,
                'profesores_completados' => $totalProfesoresCompletadosFacultad,
                'profesores_pendientes' => $totalProfesoresPendientesFacultad,
                'promedio_general' => round($promedioFacultad, 2),
                'ha_completado_todos' => $totalProfesoresCompletadosFacultad > 0,
                'tests_completados' => $totalProfesoresCompletadosFacultad,
                'total_tests' => $totalProfesoresFacultad
            ];
        })->sortByDesc('promedio_general')->values();
        
        // ✅ OPTIMIZACIÓN 20: Calcular resultados por programa usando datos ya cargados
        $resultadosPorPrograma = $programasInstitucion->map(function($programa) use ($evaluaciones, $asignacionesCompletadas, $totalTestsActivos, $testsActivos, $asignacionesAgrupadas, $puntajesMaximos, $profesoresInstitucion) {
            // ✅ OPTIMIZACIÓN 21: Usar profesores ya filtrados por programa
            $profesoresPrograma = $profesoresInstitucion->where('programa_id', $programa->id);
            $totalProfesoresPrograma = $profesoresPrograma->count();
            
            // ✅ OPTIMIZACIÓN 22: Contar profesores completados eficientemente
            $profesoresCompletadosPrograma = $profesoresPrograma->filter(function($profesor) use ($asignacionesCompletadas, $totalTestsActivos) {
                $testsCompletadosPorProfesor = $asignacionesCompletadas
                    ->where('user_id', $profesor->id)
                    ->count();
                return $testsCompletadosPorProfesor >= $totalTestsActivos;
            });
            
            $totalProfesoresCompletadosPrograma = $profesoresCompletadosPrograma->count();
            $totalProfesoresPendientesPrograma = $totalProfesoresPrograma - $totalProfesoresCompletadosPrograma;
            
            // ✅ OPTIMIZACIÓN 23: Calcular promedio usando asignaciones agrupadas y puntajes cacheados
            $totalPuntajeObtenidoPrograma = 0;
            $totalPuntajeMaximoPrograma = 0;
            
            foreach ($testsActivos as $test) {
                $profesoresConTest = 0;
                $puntajeMaximoTest = $puntajesMaximos[$test->id];
                
                foreach ($profesoresPrograma as $profesor) {
                    // ✅ OPTIMIZACIÓN 24: Usar asignaciones agrupadas en lugar de consultas
                    if (isset($asignacionesAgrupadas[$test->id][$profesor->id])) {
                        $profesoresConTest++;
                        $assignment = $asignacionesAgrupadas[$test->id][$profesor->id]->first();
                        
                        if ($assignment && $assignment->status === 'completed') {
                            $puntaje = DB::table('test_responses as tr')
                                ->where('test_assignment_id', $assignment->id)
                                ->sum('tr.score');
                            $totalPuntajeObtenidoPrograma += $puntaje;
                        }
                    }
                }
                
                $totalPuntajeMaximoPrograma += $puntajeMaximoTest * $profesoresConTest;
            }
            
            $promedioPrograma = $totalPuntajeMaximoPrograma > 0 ? round(($totalPuntajeObtenidoPrograma / $totalPuntajeMaximoPrograma) * 100, 2) : 0;
            
            return [
                'programa' => $programa,
                'nombre_programa' => $programa->nombre,
                'facultad_nombre' => $programa->facultad->nombre ?? 'N/A',
                'nivel_academico' => $programa->nivel_academico ?? 'No especificado',
                'total_profesores' => $totalProfesoresPrograma,
                'profesores_completados' => $totalProfesoresCompletadosPrograma,
                'profesores_pendientes' => $totalProfesoresPendientesPrograma,
                'promedio_general' => round($promedioPrograma, 2),
                'ha_completado_todos' => $totalProfesoresCompletadosPrograma > 0,
                'tests_completados' => $totalProfesoresCompletadosPrograma,
                'total_tests' => $totalProfesoresPrograma
            ];
        })->sortByDesc('promedio_general')->values();
        
        return [
            'entidad' => $institution,
            'institution' => [
                'id' => $institution->id,
                'name' => $institution->name
            ],
            'total_facultades' => $totalFacultades,
            'total_programas' => $totalProgramas,
            'total_profesores' => $totalProfesores,
            'total_profesores_completados' => $totalProfesoresCompletados,
            'total_profesores_pendientes' => $totalProfesoresPendientes,
            'promedio_institucion' => round($promedioInstitucion, 2),
            'promedios_por_test' => $promediosPorTest,
            'puntuacion_maxima' => $puntuacionMaxima,
            'puntuacion_minima' => $puntuacionMinima,
            'fecha_aplicacion' => $fechaAplicacion,
            'resultados_por_facultad' => $resultadosPorFacultad,
            'resultados_por_programa' => $resultadosPorPrograma,
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'parametros' => $parameters,
        ];
        
        } catch (\Exception $e) {
            \Log::error('Error en getUniversidadPreviewData:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new \Exception('Error al obtener datos de la universidad: ' . $e->getMessage());
        }
    }

    private function getProfesorPreviewData(User $profesor, array $parameters = [])
    {
        // Obtener información básica del profesor
        $profesor->load(['institution', 'facultad', 'programa']);
        
        // Obtener todos los tests asignados al profesor
        $testsAsignados = TestAssignment::where('user_id', $profesor->id)
            ->with(['test'])
            ->get();
        
        $totalTests = $testsAsignados->count();
        $testsCompletados = $testsAsignados->where('status', 'completed')->count();
        
        // Obtener evaluaciones completadas
        $evaluacionesCompletadas = TestAssignment::where('user_id', $profesor->id)
            ->where('status', 'completed')
            ->count();
        
        $evaluacionesPendientes = $totalTests - $evaluacionesCompletadas;
        
        // Obtener estadísticas de puntuación
        $stats = DB::table('test_assignments as ta')
            ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
            ->join('questions as q', 'tr.question_id', '=', 'q.id')
            ->where('ta.user_id', $profesor->id)
            ->where('ta.status', 'completed')
            ->whereNotIn('q.area_id', [8, 9]) // Excluir Información Socio-demográfica y Educación Abierta
            ->select(
                DB::raw('COUNT(DISTINCT ta.id) as total_evaluaciones'),
                DB::raw('SUM(tr.score) as total_score'),
                DB::raw('MAX(tr.score) as max_score'),
                DB::raw('MIN(tr.score) as min_score'),
                DB::raw('COUNT(tr.id) as total_respuestas')
            )->first();
        
        // Obtener todas las áreas (excluyendo las especificadas)
        $todasLasAreas = \App\Models\Area::whereNotIn('name', ['Información Socio-demográfica', 'Educación Abierta'])->get();
        
        // Calcular resultados por área
        $resultadosPorArea = $todasLasAreas->map(function ($area) use ($profesor) {
            // Obtener puntaje obtenido en esta área
            $puntajeObtenido = DB::table('test_assignments as ta')
                ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
                ->join('questions as q', 'tr.question_id', '=', 'q.id')
                ->where('ta.user_id', $profesor->id)
                ->where('ta.status', 'completed')
                ->where('q.area_id', $area->id)
                ->sum('tr.score');
            
            // Calcular puntaje máximo posible para las preguntas que realmente se respondieron en esta área
            $puntajeMaximo = DB::table('test_assignments as ta')
                ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
                ->join('questions as q', 'tr.question_id', '=', 'q.id')
                ->join('options as o', 'q.id', '=', 'o.question_id')
                ->where('ta.user_id', $profesor->id)
                ->where('ta.status', 'completed')
                ->where('q.area_id', $area->id)
                ->groupBy('q.id')
                ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                ->get()
                ->sum('max_score_per_question');
            
            $porcentaje = $puntajeMaximo > 0 ? round(($puntajeObtenido / $puntajeMaximo) * 100, 1) : 0;
            
            return [
                'area_name' => $area->name,
                'puntaje_obtenido' => round($puntajeObtenido, 2),
                'puntaje_maximo' => round($puntajeMaximo, 2),
                'porcentaje' => $porcentaje
            ];
        });
        
        // Obtener información detallada de tests asignados con resultados por área
        $testsAsignadosDetalle = $testsAsignados->map(function ($assignment) use ($todasLasAreas) {
            $puntaje = DB::table('test_responses as tr')
                ->join('test_assignments as ta', 'tr.test_assignment_id', '=', 'ta.id')
                ->where('ta.id', $assignment->id)
                ->where('ta.status', 'completed')
                ->sum('tr.score');
            
            // Calcular puntaje máximo basado en las preguntas que realmente se respondieron
            $puntajeMaximo = DB::table('test_assignments as ta')
                ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
                ->join('questions as q', 'tr.question_id', '=', 'q.id')
                ->join('options as o', 'q.id', '=', 'o.question_id')
                ->where('ta.id', $assignment->id)
                ->where('ta.status', 'completed')
                ->groupBy('q.id')
                ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                ->get()
                ->sum('max_score_per_question');
            
            // Calcular resultados por área específicamente para este test
            $resultadosPorAreaTest = $todasLasAreas->map(function ($area) use ($assignment) {
                // Obtener puntaje obtenido en esta área para este test específico
                $puntajeObtenido = DB::table('test_assignments as ta')
                    ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
                    ->join('questions as q', 'tr.question_id', '=', 'q.id')
                    ->where('ta.id', $assignment->id)
                    ->where('ta.status', 'completed')
                    ->where('q.area_id', $area->id)
                    ->sum('tr.score');
                
                // Calcular puntaje máximo posible para las preguntas que realmente se respondieron en esta área para este test
                $puntajeMaximo = DB::table('test_assignments as ta')
                    ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
                    ->join('questions as q', 'tr.question_id', '=', 'q.id')
                    ->join('options as o', 'q.id', '=', 'o.question_id')
                    ->where('ta.id', $assignment->id)
                    ->where('ta.status', 'completed')
                    ->where('q.area_id', $area->id)
                    ->groupBy('q.id')
                    ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                    ->get()
                    ->sum('max_score_per_question');
                
                $porcentaje = $puntajeMaximo > 0 ? round(($puntajeObtenido / $puntajeMaximo) * 100, 1) : 0;
                
                return [
                    'area_name' => $area->name,
                    'puntaje_obtenido' => round($puntajeObtenido, 2),
                    'puntaje_maximo' => round($puntajeMaximo, 2),
                    'porcentaje' => $porcentaje
                ];
            })->toArray();
            
            return [
                'id' => $assignment->id,
                'nombre' => $assignment->test->name ?? 'Test sin nombre',
                'completado' => $assignment->status === 'completed',
                'fecha_asignacion' => $assignment->created_at ? $assignment->created_at->format('d/m/Y') : 'N/A',
                'fecha_completado' => $assignment->completed_at ? $assignment->completed_at->format('d/m/Y') : 'Pendiente',
                'puntaje' => round($puntaje, 2),
                'puntaje_maximo' => round($puntajeMaximo, 2),
                'resultados_por_area' => $resultadosPorAreaTest
            ];
        });
        
        // Calcular promedio general basado en TODOS los tests asignados (completados y pendientes)
        $testsCompletadosCollection = $testsAsignadosDetalle->filter(function ($test) {
            return $test['completado'];
        });
        
        $promedioGeneral = 0;
        $promediosPorTest = [];
        
        // Calcular promedio general considerando TODOS los tests asignados (completados y pendientes)
        $totalPuntajeObtenido = 0;
        $totalPuntajeMaximo = 0;
        
        // Sumar puntajes de tests completados
        foreach ($testsAsignadosDetalle as $test) {
            $totalPuntajeObtenido += $test['puntaje'];
            $totalPuntajeMaximo += $test['puntaje_maximo'];
        }
        
        // Sumar puntajes máximos de tests pendientes
        foreach ($testsAsignados as $assignment) {
            if ($assignment->status !== 'completed') {
                $puntajeMaximoPendiente = DB::table('test_assignments as ta')
                    ->join('questions as q', 'ta.test_id', '=', 'q.test_id')
                    ->join('options as o', 'q.id', '=', 'o.question_id')
                    ->where('ta.id', $assignment->id)
                    ->groupBy('q.id')
                    ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                    ->get()
                    ->sum('max_score_per_question');
                
                $totalPuntajeMaximo += $puntajeMaximoPendiente;
            }
        }
        
        $promedioGeneral = $totalPuntajeMaximo > 0 ? round(($totalPuntajeObtenido / $totalPuntajeMaximo) * 100, 2) : 0;
        
        // Calcular promedio individual de cada test completado
        if ($testsCompletadosCollection->count() > 0) {
            foreach ($testsCompletadosCollection as $index => $test) {
                $porcentajeTest = $test['puntaje_maximo'] > 0 ? round(($test['puntaje'] / $test['puntaje_maximo']) * 100, 2) : 0;
                $promediosPorTest[] = [
                    'nombre' => $test['nombre'],
                    'promedio' => $porcentajeTest
                ];
            }
        }
        
        return [
            'profesor' => [
                'id' => $profesor->id,
                'nombre_completo' => $profesor->name . ' ' . $profesor->apellido1 . ' ' . ($profesor->apellido2 ?? ''),
                'email' => $profesor->email,
                'created_at' => $profesor->created_at ? $profesor->created_at->format('d/m/Y') : 'N/A'
            ],
            'institution' => $profesor->institution ? [
                'id' => $profesor->institution->id,
                'name' => $profesor->institution->name
            ] : null,
            'facultad' => $profesor->facultad ? [
                'id' => $profesor->facultad->id,
                'nombre' => $profesor->facultad->nombre
            ] : null,
            'programa' => $profesor->programa ? [
                'id' => $profesor->programa->id,
                'nombre' => $profesor->programa->nombre
            ] : null,
            'total_evaluaciones' => $stats->total_evaluaciones ?? 0,
            'evaluaciones_realizadas' => $evaluacionesCompletadas,
            'evaluaciones_pendientes' => $evaluacionesPendientes,
            'tests_completados' => $testsCompletadosCollection->count(),
            'total_tests' => $totalTests,
            'promedio_general' => round($promedioGeneral, 2),
            'promedios_por_test' => $promediosPorTest,
            'puntuacion_maxima' => $stats->max_score ?? 0,
            'puntuacion_minima' => $stats->min_score ?? 0,
            'resultados_por_area' => $resultadosPorArea->toArray(),
            'tests_asignados' => $testsAsignadosDetalle->toArray(),
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
        // Obtener todas las áreas disponibles del sistema PRIMERO (excluyendo Información Socio-demográfica y Educación Abierta)
        $todasLasAreas = \App\Models\Area::whereNotIn('name', ['Información Socio-demográfica', 'Educación Abierta'])->get();
        
        // Obtener todos los profesores del programa
        $profesoresPrograma = User::whereHas('roles', function($q) {
            $q->where('name', 'Docente');
        })->where('programa_id', $programa->id)->get();
        
        $totalProfesores = $profesoresPrograma->count();
        
        // Obtener todos los tests activos
        $testsActivos = Test::where('is_active', true)->get();
        $totalTestsActivos = $testsActivos->count();
        
        // Obtener asignaciones completadas por profesor en este programa
        $asignacionesCompletadas = TestAssignment::whereHas('user', function($q) use ($programa) {
            $q->where('programa_id', $programa->id);
        })->where('status', 'completed')->get();
        
        // Contar profesores que han completado todos los tests
        $profesoresCompletados = $profesoresPrograma->filter(function($profesor) use ($testsActivos, $asignacionesCompletadas, $totalTestsActivos) {
            $testsCompletadosPorProfesor = $asignacionesCompletadas
                ->where('user_id', $profesor->id)
                ->count();
            return $testsCompletadosPorProfesor >= $totalTestsActivos;
        });
        
        $totalProfesoresCompletados = $profesoresCompletados->count();
        $totalProfesoresPendientes = $totalProfesores - $totalProfesoresCompletados;
        
        // Obtener evaluaciones por área para el programa
        $query = EvaluacionPorArea::byPrograma($programa->id);
        
        $evaluaciones = $query->get();
        
        // Calcular estadísticas generales del programa
        $promedioPrograma = $evaluaciones->avg('score') ?? 0;
        $puntuacionMaxima = $evaluaciones->max('score') ?? 0;
        $puntuacionMinima = $evaluaciones->min('score') ?? 0;
        
        // Obtener fecha de aplicación (fecha actual)
        $fechaAplicacion = now()->format('d/m/Y H:i:s');
        
        // Calcular resultados por área para cada profesor - TODOS los profesores
        $resultadosPorProfesor = collect();
        
        foreach ($profesoresPrograma as $profesor) {
            $evaluacionesProfesor = $evaluaciones->where('user_id', $profesor->id);
            
            // Verificar si el profesor ha completado todos los tests
            $testsCompletadosPorProfesor = $asignacionesCompletadas
                ->where('user_id', $profesor->id)
                ->count();
            $haCompletadoTodos = $testsCompletadosPorProfesor >= $totalTestsActivos;
            
            // Generar resultados para todas las áreas disponibles
            $resultadosPorArea = $todasLasAreas->map(function ($area) use ($evaluacionesProfesor, $evaluaciones) {
                $areaEvaluaciones = $evaluacionesProfesor->where('area_id', $area->id);
                
                // Calcular puntaje máximo posible para esta área
                $puntajeMaximo = \App\Models\Question::where('area_id', $area->id)
                    ->whereHas('test', function($q) {
                        $q->where('is_active', true);
                    })
                    ->get()
                    ->sum(function($q) {
                        return $q->options->max('score') ?? 0;
                    });
                
                if ($areaEvaluaciones->count() > 0) {
                    $puntajeObtenido = $areaEvaluaciones->sum('score');
                    
                    return [
                        'area_name' => $area->name,
                        'area_id' => $area->id,
                        'puntaje_obtenido' => $puntajeObtenido,
                        'puntaje_maximo' => $puntajeMaximo > 0 ? $puntajeMaximo : 100,
                        'porcentaje' => $puntajeMaximo > 0 ? round(($puntajeObtenido / $puntajeMaximo) * 100, 2) : 0
                    ];
                } else {
                    return [
                        'area_name' => $area->name,
                        'area_id' => $area->id,
                        'puntaje_obtenido' => 0,
                        'puntaje_maximo' => $puntajeMaximo > 0 ? $puntajeMaximo : 100,
                        'porcentaje' => 0
                    ];
                }
            });
            
            $resultadosPorProfesor->push([
                'profesor' => [
                    'id' => $profesor->id,
                    'nombre_completo' => trim($profesor->name . ' ' . $profesor->apellido1 . ' ' . $profesor->apellido2),
                    'email' => $profesor->email
                ],
                'ha_completado_todos' => $haCompletadoTodos,
                'tests_completados' => $testsCompletadosPorProfesor,
                'total_tests' => $totalTestsActivos,
                'resultados_por_area' => $resultadosPorArea,
                'promedio_general' => $evaluacionesProfesor->avg('score') ?? 0
            ]);
        }
        
        // Ordenar profesores por promedio general de mayor a menor
        $resultadosPorProfesor = $resultadosPorProfesor->sortByDesc('promedio_general');
        
        // Calcular estadísticas por área del programa
        $areaStats = $todasLasAreas->map(function ($area) use ($evaluaciones) {
            $areaEvaluaciones = $evaluaciones->where('area_id', $area->id);
            return [
                'area_name' => $area->name,
                'area_id' => $area->id,
                'total_evaluaciones' => $areaEvaluaciones->count(),
                'promedio_score' => round($areaEvaluaciones->avg('score'), 2),
                'max_score' => $areaEvaluaciones->max('score'),
                'min_score' => $areaEvaluaciones->min('score'),
                'niveles' => $this->calculateNivelesFromVista($areaEvaluaciones, $area->id),
            ];
        });

        return [
            'programa' => $programa,
            'facultad' => $programa->facultad,
            'total_profesores' => $totalProfesores,
            'total_profesores_completados' => $totalProfesoresCompletados,
            'total_profesores_pendientes' => $totalProfesoresPendientes,
            'promedio_programa' => round($promedioPrograma, 2),
            'puntuacion_maxima' => $puntuacionMaxima,
            'puntuacion_minima' => $puntuacionMinima,
            'fecha_aplicacion' => $fechaAplicacion,
            'resultados_por_profesor' => $resultadosPorProfesor,
            'area_stats' => $areaStats,
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

        // Obtener información detallada de tests asignados
        $testsAsignados = TestAssignment::where('user_id', $profesor->id)
            ->with(['test'])
            ->get();
        
        $testsAsignadosDetalle = $testsAsignados->map(function ($assignment) {
            $puntaje = DB::table('test_responses as tr')
                ->join('test_assignments as ta', 'tr.test_assignment_id', '=', 'ta.id')
                ->where('ta.id', $assignment->id)
                ->where('ta.status', 'completed')
                ->sum('tr.score');
            
            // Calcular puntaje máximo basado en las preguntas que realmente se respondieron
            $puntajeMaximo = DB::table('test_assignments as ta')
                ->join('test_responses as tr', 'ta.id', '=', 'tr.test_assignment_id')
                ->join('questions as q', 'tr.question_id', '=', 'q.id')
                ->join('options as o', 'q.id', '=', 'o.question_id')
                ->where('ta.id', $assignment->id)
                ->where('ta.status', 'completed')
                ->groupBy('q.id')
                ->select(DB::raw('MAX(o.score) as max_score_per_question'))
                ->get()
                ->sum('max_score_per_question');
            
            return [
                'nombre' => $assignment->test->name ?? 'Test sin nombre',
                'completado' => $assignment->status === 'completed',
                'fecha_asignacion' => $assignment->created_at ? $assignment->created_at->format('d/m/Y') : 'N/A',
                'fecha_completado' => $assignment->completed_at ? $assignment->completed_at->format('d/m/Y') : 'Pendiente',
                'puntaje' => round($puntaje, 2),
                'puntaje_maximo' => round($puntajeMaximo, 2)
            ];
        });

        return [
            'profesor' => $profesor,
            'stats' => $stats,
            'areas' => $areas,
            'historial' => $historial,
            'comparacion' => $comparacion,
            'tests_asignados' => $testsAsignadosDetalle,
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
    
    /**
     * ✅ OPTIMIZACIÓN: Determinar si usar procesamiento asíncrono
     */
    private function shouldUseAsyncProcessing($entity)
    {
        // Usar procesamiento asíncrono si:
        // 1. Es una institución con más de 100 profesores
        // 2. Es una facultad con más de 50 profesores
        // 3. Es un programa con más de 30 profesores
        
        if ($entity instanceof Institution) {
            $profesorCount = User::where('institution_id', $entity->id)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'Docente');
                })->count();
            return $profesorCount > 100;
        }
        
        if ($entity instanceof Facultad) {
            $profesorCount = User::where('facultad_id', $entity->id)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'Docente');
                })->count();
            return $profesorCount > 50;
        }
        
        if ($entity instanceof Programa) {
            $profesorCount = User::where('programa_id', $entity->id)
                ->whereHas('roles', function($q) {
                    $q->where('name', 'Docente');
                })->count();
            return $profesorCount > 30;
        }
        
        return false;
    }
}