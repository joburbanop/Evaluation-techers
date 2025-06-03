<?php

namespace App\Filament\Widgets;

use App\Models\TestAssignment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;

class EvaluacionesRecientes extends BaseWidget
{
    protected static ?string $heading = 'Evaluaciones';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TestAssignment::query()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Evaluado')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('test.name')
                    ->label('Test')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'expired' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Pendiente',
                        'in_progress' => 'En progreso',
                        'completed' => 'Completado',
                        'expired' => 'Expirado',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('score_display')
                    ->label('Nivel de Competencia')
                    ->html()
                    ->formatStateUsing(function ($record) {
                        $scoreData = $record->responses->first()?->calculateTotalScore() ?? [
                            'total_score' => 0,
                            'max_possible_score' => 0,
                            'level' => 'A1',
                            'level_info' => [
                                'name' => 'Novato',
                                'description' => 'Nivel inicial de competencia digital',
                                'color' => 'danger'
                            ],
                            'percentage' => 0
                        ];

                        $correctAnswers = $record->responses->where('is_correct', true)->count();
                        $totalQuestions = $record->responses->count();

                        return view('filament.components.score-display', [
                            'level' => $scoreData['level'],
                            'levelInfo' => $scoreData['level_info'],
                            'totalScore' => $scoreData['total_score'],
                            'maxScore' => $scoreData['max_possible_score'],
                            'percentage' => $scoreData['percentage'],
                            'correctAnswers' => $correctAnswers,
                            'totalQuestions' => $totalQuestions
                        ])->render();
                    }),
            ])
            ->actions([
                Action::make('ver_detalles')
                    ->label('Ver Detalles')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (TestAssignment $record) => "Resultados de {$record->user->name}")
                    ->modalContent(function (TestAssignment $record) {
                        $responses = $record->responses->loadMissing('question.options', 'option');
                        $areas = \App\Models\Area::all();
                        
                        $areaScores = $areas->map(function ($area) use ($record) {
                            $areaResponses = $record->responses()
                                ->whereHas('question', function ($query) use ($area) {
                                    $query->where('area_id', $area->id);
                                })->get();

                            $totalScore = $areaResponses->sum(function ($response) {
                                return $response->option->score ?? 0;
                            });

                            $maxPossibleScore = $areaResponses->sum(function ($response) {
                                return $response->question->options->max('score');
                            });

                            $percentage = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;
                            $level = \App\Models\CompetencyLevel::getLevelByScore($percentage);

                            return [
                                'area' => $area->name,
                                'score' => $percentage,
                                'level' => $level,
                                'totalScore' => $totalScore,
                                'maxScore' => $maxPossibleScore
                            ];
                        });

                        return view('filament.widgets.evaluacion-detalles', [
                            'record' => $record,
                            'responses' => $responses,
                            'areaScores' => $areaScores
                        ]);
                    })
                    ->modalWidth('7xl')
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
} 