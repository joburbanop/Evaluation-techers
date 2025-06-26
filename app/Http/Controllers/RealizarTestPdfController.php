<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\TestAssignment;
use App\Models\TestCompetencyLevel;
use App\Models\TestAreaCompetencyLevel;
use App\Models\AreaCompetencyLevel; 


class RealizarTestPdfController extends Controller
{
    public function generate($id)
    {
        $record = TestAssignment::with(['responses.option.question.area', 'test', 'test.questions.area'])->findOrFail($id);

        // 1) Repetir la misma lógica que ya tienes en RealizarTestResource para obtener todos los datos:
        //    - Puntaje global (excluyendo preguntas sociodemográficas)
        $nonSociodemographicResponses = $record->responses->filter(function($response) {
            return $response->question->area_id !== 8;
        });
        
        $totalScore = $nonSociodemographicResponses->sum(fn($r) => $r->option->score ?? 0);
        $maxPossibleScore = $nonSociodemographicResponses->sum(fn($r) => $r->question->options->max('score') ?? 0);
        
        $percentage = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;
        
        $nivelGlobal = TestCompetencyLevel::getLevelForScore($record->test_id, $totalScore);

        $completedAssignments = \App\Models\TestAssignment::with(['user.programa.facultad', 'responses.option.question.area'])
            ->where('test_id', $record->test_id)
            ->where('status', 'completed')
            ->get();
            
        $user = $record->user;
        
        // Función reutilizable para el cálculo de resultados (versión simple) - excluyendo sociodemográficas
        $calculatePercentileSimple = function ($assignments, $currentScore) {
            if ($assignments->isEmpty()) {
                return 0;
            }
            $scores = $assignments->map(function($a) {
                $nonSociodemographicResponses = $a->responses->filter(function($response) {
                    return $response->question->area_id !== 8;
                });
                return $nonSociodemographicResponses->sum(fn($r) => $r->option->score ?? 0);
            });
            $usersBelow = $scores->filter(fn($s) => $s < $currentScore)->count();
            return round(($usersBelow / $assignments->count()) * 100);
        };
        
        // Resultados Globales
        $percentileRankGlobal = $calculatePercentileSimple($completedAssignments, $totalScore);

        // Resultados por facultad
        $facultadAssignments = $completedAssignments->filter(fn($a) => $a->user->programa?->facultad_id === $user->programa?->facultad_id);
        $percentileRankFacultad = $calculatePercentileSimple($facultadAssignments, $totalScore);

        // Resultados por programa
        $programaAssignments = $completedAssignments->filter(fn($a) => $a->user->programa_id === $user->programa_id);
        $percentileRankPrograma = $calculatePercentileSimple($programaAssignments, $totalScore);

        $percentileInstitution = $percentileRankFacultad;
        $percentileProgram = $percentileRankPrograma;

        //    - Resultados por área (excluyendo área sociodemográfica)
        $preguntasAgrupadas = $record->test->questions()
            ->with([
                'area',
                'options',
                'responses' => function ($query) use ($record) {
                    $query->where('test_assignment_id', $record->id);
                }
            ])
            ->where('area_id', '!=', 8) // Excluir área sociodemográfica
            ->get()
            ->filter(fn ($q) => $q->area !== null)
            ->groupBy(fn ($q) => $q->area->id);

        $areaResults = collect();

        foreach ($preguntasAgrupadas as $areaId => $preguntas) {
            $area = $preguntas->first()->area;

            $puntajeObtenido = $preguntas->sum(fn ($pregunta) =>
                $pregunta->responses->sum(fn ($r) => $r->option->score ?? 0)
            );

            $puntajeMaximo = $preguntas->sum(fn ($pregunta) =>
                $pregunta->options->max('score') ?? 0
            );
           $nivel = \App\Models\TestAreaCompetencyLevel::getLevelByScore($record->test_id, $area->id, $puntajeObtenido);

            $areaResults->push([
                'area_name' => $area->name,
                'obtained_score' => $puntajeObtenido,
                'max_possible' => $puntajeMaximo,
                'percentage' => $puntajeMaximo > 0 ? round(($puntajeObtenido / $puntajeMaximo) * 100) : 0,
                'level_code' => $nivel?->code ?? 'NA',
                'level_description' => $nivel?->description ?? 'NA',
            ]);
        }

         //calculo de porcentaje obtenido global (excluyendo sociodemográficas)
         $puntajeTotal = $nonSociodemographicResponses->sum(function ($response) {
            return $response->option->score ?? 0;
        });
        $puntajePosible = $record->test->questions()
            ->where('area_id', '!=', 8) // Excluir área sociodemográfica
            ->get()
            ->sum(function ($question) {
                return $question->options->max('score') ?? 0;
            });
        $porcentajeObtenidoGlobal = round(($puntajeTotal / $puntajePosible) * 100);

        // Debug: Verificar que el test se cargue correctamente
        \Log::info('Test cargado:', [
            'test_id' => $record->test_id,
            'test_name' => $record->test->name ?? 'Nombre no encontrado',
            'test_object' => $record->test ? 'Test cargado' : 'Test no cargado'
        ]);

        // 2) Generar el PDF usando la misma vista 'components.score-display'
        $pdf = PDF::loadView('components.score-display', [
            'maxScore' => $maxPossibleScore,
            'score' => (string) $totalScore,
            'percentage' => (int) $percentage,
            'levelName' => $nivelGlobal?->name ?? 'Sin nivel',
            'levelDescription' => $nivelGlobal?->description ?? 'Sin descripción',
            'levelCode' => $nivelGlobal?->code ?? 'Sin código',
            'publicationDate' => \Illuminate\Support\Carbon::parse($record->updated_at)
                                  ->locale('es')
                                  ->translatedFormat('d \\D\\E F \\D\\E Y, H:i'),
            'applicationDate' => \Illuminate\Support\Carbon::parse($record->created_at)
                                  ->locale('es')
                                  ->translatedFormat('d \\D\\E F \\D\\E Y, H:i'),
            'percentileInfo' => true,
            'percentileRankGlobal' => $percentileRankGlobal,
            'percentileInstitution' => $percentileInstitution,
            'percentileProgram' => $percentileProgram,
            'percentileRankFacultad' => $percentileRankFacultad,
            'percentileRankPrograma' => $percentileRankPrograma,
            'evaluatedName' => $user->full_name,
            'identification' => $user->document_number ?? 'Sin identificación',
            'institution' => $user->institution?->name ?? 'Sin institución',
            'program' => $user->programa?->nombre ?? 'Sin programa',
            'facultad' => $user->programa?->facultad?->nombre ?? 'Sin facultad',
            'icon' => 'heroicon-o-academic-cap',
            'areaResults' => $areaResults,
            'assignmentId' => $record->id,
            'percentage' => $porcentajeObtenidoGlobal,
            'testName' => 'Informe de Evaluación: ' . ($record->test->name ?? 'Test sin nombre'),
        ]);

        // 3) Forzar la descarga del PDF
        $fileName = 'evaluacion_' . $user->document_number . '.pdf';
        return $pdf->download($fileName);
    }
}