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
        $record = TestAssignment::with(['responses.option', 'test.questions.area'])->findOrFail($id);

        // 1) Repetir la misma lógica que ya tienes en RealizarTestResource para obtener todos los datos:
        //    - Puntaje global
        $totalScore = $record->responses->sum(fn($r) => $r->option->score ?? 0);
        $maxPossibleScore = $record->responses->sum(fn($r) => $r->question->options->max('score') ?? 0);
        
        $percentage = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;
        
        $nivelGlobal = TestCompetencyLevel::getLevelForScore($record->test_id, $totalScore);

        $completedAssignments = \App\Models\TestAssignment::with(['user.programa.facultad', 'responses.option'])
            ->where('test_id', $record->test_id)
            ->where('status', 'completed')
            ->get();
            
        $user = $record->user;
        
        // Función reutilizable para el cálculo de percentil (versión simple)
        $calculatePercentileSimple = function ($assignments, $currentScore) {
            if ($assignments->isEmpty()) {
                return 0;
            }
            $scores = $assignments->map(fn($a) => $a->responses->sum(fn($r) => $r->option->score ?? 0));
            $usersBelow = $scores->filter(fn($s) => $s < $currentScore)->count();
            return round(($usersBelow / $assignments->count()) * 100);
        };
        
        // Percentil Global
        $percentileRankGlobal = $calculatePercentileSimple($completedAssignments, $totalScore);

        // Percentil por facultad
        $facultadAssignments = $completedAssignments->filter(fn($a) => $a->user->programa?->facultad_id === $user->programa?->facultad_id);
        $percentileRankFacultad = $calculatePercentileSimple($facultadAssignments, $totalScore);

        // Percentil por programa
        $programaAssignments = $completedAssignments->filter(fn($a) => $a->user->programa_id === $user->programa_id);
        $percentileRankPrograma = $calculatePercentileSimple($programaAssignments, $totalScore);

        $percentileInstitution = $percentileRankFacultad;
        $percentileProgram = $percentileRankPrograma;

        //    - Resultados por área
        $preguntasAgrupadas = $record->test->questions()
            ->with([
                'area',
                'options',
                'responses' => function ($query) use ($record) {
                    $query->where('test_assignment_id', $record->id);
                }
            ])
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

         //calculo de porcentaje obtenido global
         $puntajeTotal = $record->responses->sum(function ($response) {
            return $response->option->score ?? 0;
        });
        $puntajePosible = $record->test->questions->sum(function ($question) {
            return $question->options->max('score') ?? 0;
        });
        $porcentajeObtenidoGlobal = round(($puntajeTotal / $puntajePosible) * 100);

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
        ]);

        // 3) Forzar la descarga del PDF
        $fileName = 'evaluacion_' . $user->document_number . '.pdf';
        return $pdf->download($fileName);
    }
}