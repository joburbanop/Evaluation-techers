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
        
        // Cálculo del porcentaje obtenido
        $percentage = 0;
        if ($maxPossibleScore > 0) {
            $percentage = round(($totalScore / $maxPossibleScore) * 100);
        }

       
        
        $nivelGlobal = TestCompetencyLevel::getLevelForScore($record->test_id, $totalScore);

        
        $completedAssignments = \App\Models\TestAssignment::with(['responses.option'])
            ->where('test_id', $record->test_id)
            ->where('status', 'completed')
            ->get();

        $levels = \App\Models\TestCompetencyLevel::where('test_id', $record->test_id)
            ->orderBy('min_score')
            ->get();

        $getLevel = function($score, $levels) {
            foreach ($levels as $level) {
                if ($score >= $level->min_score && $score <= $level->max_score) {
                    return $level->code;
                }
            }
            return null;
        };

        $userLevels = [];
        foreach ($completedAssignments as $assignment) {
            $score = $assignment->responses->sum(fn($r) => $r->option->score ?? 0);
            $maxScore = $assignment->responses->sum(fn($r) => $r->question->options->max('score') ?? 0);
            $percentage = $maxScore > 0 ? ($score / $maxScore) * 100 : 0;
            $level = $getLevel($score, $levels);
            if ($level) {
                $userLevels[] = $level;
            }
        }

        $totalUsers = count($userLevels);
        $levelCounts = array_count_values($userLevels);
        $orderedLevels = $levels->pluck('code')->toArray();
        $levelPercentages = [];
        foreach ($orderedLevels as $code) {
            $levelPercentages[$code] = isset($levelCounts[$code]) ? ($levelCounts[$code] / $totalUsers) * 100 : 0;
        }

        $userPercentage = $maxPossibleScore > 0 ? ($totalScore / $maxPossibleScore) * 100 : 0;
        $userLevel = $getLevel($totalScore, $levels);
        $userLevelIndex = array_search($userLevel, $orderedLevels);
        $usersBelow = 0;
        if ($userLevelIndex !== false) {
            for ($i = 0; $i < $userLevelIndex; $i++) {
                $levelCode = $orderedLevels[$i];
                $usersBelow += $levelCounts[$levelCode] ?? 0;
            }
        }
        $percentileRankGlobal = $totalUsers > 0 ? round(($usersBelow / $totalUsers) * 100) : 0;

        //    - Percentil por institución
        $userInstitutionId = auth()->user()->institution_id;
        $institutionScores = \App\Models\TestAssignment::with(['responses.option','user'])
            ->where('test_id', $record->test_id)
            ->where('status', 'completed')
            ->whereHas('user', fn($q) => $q->where('institution_id', $userInstitutionId))
            ->get()
            ->map(fn($a) => $a->responses->sum(fn($r) => $r->option->score ?? 0))
            ->sort()
            ->values();
        $percentileInstitution = 0;
        if ($institutionScores->count()) {
            $belowInst = $institutionScores->filter(fn($s) => $s < $totalScore)->count();
            $equalInst = $institutionScores->filter(fn($s) => $s === $totalScore)->count();
            $percentileInstitution = round((($belowInst + 0.5 * $equalInst) / $institutionScores->count()) * 100);
        }

        //    - Percentil por programa
        $userProgramId = auth()->user()->programa_id;
        $programScores = \App\Models\TestAssignment::with(['responses.option','user'])
            ->where('test_id', $record->test_id)
            ->where('status', 'completed')
            ->whereHas('user', fn($q) => $q->where('programa_id', $userProgramId))
            ->get()
            ->map(fn($a) => $a->responses->sum(fn($r) => $r->option->score ?? 0))
            ->sort()
            ->values();
        $percentileProgram = 0;
        if ($programScores->count()) {
            $belowProg = $programScores->filter(fn($s) => $s < $totalScore)->count();
            $equalProg = $programScores->filter(fn($s) => $s === $totalScore)->count();
            $percentileProgram = round((($belowProg + 0.5 * $equalProg) / $programScores->count()) * 100);
        }

        // Percentil por facultad
        $userFacultad = auth()->user()->programa?->facultad;
        $percentileRankFacultad = 0;
        if ($userFacultad) {
            $facultadAssignments = $completedAssignments->filter(function ($assignment) use ($userFacultad) {
                return $assignment->user->programa?->facultad_id === $userFacultad->id;
            });

            if ($facultadAssignments->count() > 0) {
                $scoresFacultad = $facultadAssignments->map(fn($a) => $a->responses->sum(fn($r) => $r->option->score ?? 0));
                $usersBelowFacultad = $scoresFacultad->filter(fn($s) => $s < $totalScore)->count();
                $percentileRankFacultad = round(($usersBelowFacultad / $facultadAssignments->count()) * 100);
            }
        }

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
            'percentileRankPrograma' => $percentileProgram,
            'evaluatedName' => auth()->user()->full_name,
            'identification' => auth()->user()->document_number ?? 'Sin identificación',
            'institution' => auth()->user()->institution?->name ?? 'Sin institución',
            'program' => auth()->user()->programa?->nombre ?? 'Sin programa',
            'facultad' => $userFacultad?->nombre ?? 'Sin facultad',
            'icon' => 'heroicon-o-academic-cap',
            'areaResults' => $areaResults,
            'assignmentId' => $record->id,
            'percentage' => $porcentajeObtenidoGlobal,
        ]);

        // 3) Forzar la descarga del PDF
        $fileName = 'evaluacion_' . auth()->user()->document_number . '.pdf';
        return $pdf->download($fileName);
    }
}