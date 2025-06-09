<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF; // Asegúrate de tener instalado barryvdh/laravel-dompdf
use App\Models\TestAssignment;

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
        $nivelGlobal = \App\Models\CompetencyLevel::getLevelByScore($totalScore);

        //    - Percentil global
        $allScores = \App\Models\TestAssignment::with(['responses.option'])
            ->where('test_id', $record->test_id)
            ->where('status', 'completed')
            ->get()
            ->map(fn($a) => $a->responses->sum(fn($r) => $r->option->score ?? 0))
            ->sort()
            ->values();
        $percentileRankGlobal = 0;
        if ($allScores->count()) {
            $below = $allScores->filter(fn($s) => $s < $totalScore)->count();
            $equal = $allScores->filter(fn($s) => $s === $totalScore)->count();
            $percentileRankGlobal = round((($below + 0.5 * $equal) / $allScores->count()) * 100);
        }

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

        //    - Resultados por área
        $preguntasAgrupadas = $record->test->questions()
            ->with([
                'area',
                'options',
                'responses' => fn($q) => $q->where('test_assignment_id', $record->id)
            ])
            ->get()
            ->filter(fn($q) => $q->area !== null)
            ->groupBy(fn($q) => $q->area->id);
        $areaResults = collect();
        foreach ($preguntasAgrupadas as $areaId => $preguntas) {
            $area = $preguntas->first()->area;
            $puntajeAreaObtenido = $preguntas->sum(fn($preg) => $preg->responses->sum(fn($r) => $r->option->score ?? 0));
            $puntajeAreaMax = $preguntas->sum(fn($preg) => $preg->options->max('score') ?? 0);
            $nivelArea = \App\Models\CompetencyLevel::getLevelByScore($puntajeAreaObtenido);
            $areaResults->push([
                'area_name' => $area->name,
                'obtained_score' => $puntajeAreaObtenido,
                'max_possible' => $puntajeAreaMax,
                'percentage' => $puntajeAreaMax > 0 ? round(($puntajeAreaObtenido / $puntajeAreaMax) * 100) : 0,
                'level_code' => $nivelArea?->code ?? 'Sin código',
                'level_description' => $nivelArea?->description ?? 'Sin descripción',
            ]);
        }

        // 2) Generar el PDF usando la misma vista 'components.score-display'
        $pdf = PDF::loadView('components.score-display', [
            'maxScore' => $maxPossibleScore,
            'score' => (string) $totalScore,
            'percentage' => $percentage,
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
            'averageScore' => $percentileRankGlobal,
            'percentileInstitution' => $percentileInstitution,
            'percentileProgram' => $percentileProgram,
            'evaluatedName' => auth()->user()->name,
            'identification' => auth()->user()->document_number ?? 'Sin identificación',
            'institution' => auth()->user()->institution?->name ?? 'Sin institución',
            'program' => auth()->user()->programa?->nombre ?? 'Sin programa',
            'icon' => 'heroicon-o-academic-cap',
            'areaResults' => $areaResults,
            'assignmentId' => $record->id,
        ]);

        // 3) Forzar la descarga del PDF
        $fileName = 'evaluacion_' . auth()->user()->document_number . '.pdf';
        return $pdf->download($fileName);
    }
}