<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestResultResource\Pages;
use App\Models\TestAssignment;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;

class TestResultResource extends Resource
{
    protected static ?string $model = TestAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Resultados de Tests';

    protected static ?string $modelLabel = 'Resultado de Test';

    protected static ?string $pluralModelLabel = 'Resultados de Tests';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Docente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('test.name')
                    ->label('Test')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Inicio')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Fecha de Finalización')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responses_sum_score')
                    ->label('Puntuación Total')
                    ->sum('responses', 'score')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('test')
                    ->relationship('test', 'name'),
                Tables\Filters\Filter::make('completed')
                    ->query(fn ($query) => $query->whereNotNull('completed_at')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Resumen General')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('competency_level')
                                    ->label('Nivel de Competencia')
                                    ->state(function (TestAssignment $record) {
                                        return $record->responses->first()?->getCompetencyLevel()?->name ?? 'No disponible';
                                    })
                                    ->color('primary')
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('total_score')
                                    ->label('Puntuación Total')
                                    ->state(function (TestAssignment $record) {
                                        $totalScore = $record->responses->sum('score');
                                        $maxScore = $record->responses->count() * 4;
                                        return "{$totalScore}/{$maxScore}";
                                    })
                                    ->color('success')
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('correct_answers')
                                    ->label('Respuestas Correctas')
                                    ->state(function (TestAssignment $record) {
                                        $correct = $record->responses->where('is_correct', true)->count();
                                        $total = $record->responses->count();
                                        return "{$correct}/{$total}";
                                    })
                                    ->color('success')
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('time_taken')
                                    ->label('Tiempo Total')
                                    ->state(function (TestAssignment $record) {
                                        return $record->completed_at?->diffInMinutes($record->created_at) . ' min';
                                    })
                                    ->color('warning')
                                    ->weight(FontWeight::Bold),
                            ]),
                    ]),

                Section::make('Puntuación por Área')
                    ->schema([
                        TextEntry::make('area_scores')
                            ->label('Distribución de Competencias')
                            ->state(function (TestAssignment $record) {
                                $areas = \App\Models\Area::all();
                                $scores = [];
                                
                                foreach ($areas as $area) {
                                    $areaResponses = $record->responses()
                                        ->whereHas('question', function ($query) use ($area) {
                                            $query->where('area_id', $area->id);
                                        })->get();

                                    $totalScore = $areaResponses->sum('score');
                                    $maxScore = $areaResponses->count() * 4;
                                    $score = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;

                                    $scores[] = "{$area->name}: " . round($score, 1) . "%";
                                }

                                return implode("\n", $scores);
                            })
                            ->listWithLineBreaks(),
                    ]),

                Section::make('Respuestas Detalladas')
                    ->schema([
                        RepeatableEntry::make('responses')
                            ->schema([
                                TextEntry::make('question.question')
                                    ->label('Pregunta')
                                    ->weight(FontWeight::Bold),
                                TextEntry::make('option.text')
                                    ->label('Tu respuesta')
                                    ->color(fn ($record) => $record->is_correct ? 'success' : 'danger'),
                                TextEntry::make('score')
                                    ->label('Puntuación')
                                    ->formatStateUsing(fn ($state) => "{$state}/4"),
                                TextEntry::make('feedback')
                                    ->label('Retroalimentación')
                                    ->visible(fn ($record) => filled($record->feedback)),
                                TextEntry::make('justification')
                                    ->label('Justificación')
                                    ->visible(fn ($record) => filled($record->justification)),
                            ])
                            ->columns(2),
                    ]),

                Section::make('Recomendaciones')
                    ->schema([
                        TextEntry::make('recommendations')
                            ->label('Recomendaciones')
                            ->state(function (TestAssignment $record) {
                                $competencyLevel = $record->responses->first()?->getCompetencyLevel();
                                $areaScores = \App\Models\Area::with(['questions' => function ($query) use ($record) {
                                    $query->whereHas('responses', function ($q) use ($record) {
                                        $q->where('test_assignment_id', $record->id);
                                    });
                                }])->get()->map(function ($area) use ($record) {
                                    $areaResponses = $record->responses()
                                        ->whereHas('question', function ($query) use ($area) {
                                            $query->where('area_id', $area->id);
                                        })->get();

                                    $totalScore = $areaResponses->sum('score');
                                    $maxScore = $areaResponses->count() * 4;
                                    $score = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;

                                    return (object)[
                                        'name' => $area->name,
                                        'score' => round($score, 1)
                                    ];
                                });

                                $recommendations = [];
                                
                                // Recomendaciones generales
                                if ($competencyLevel) {
                                    switch ($competencyLevel->code) {
                                        case 'A1':
                                            $recommendations[] = "Enfócate en desarrollar habilidades básicas de competencia digital.";
                                            break;
                                        case 'A2':
                                            $recommendations[] = "Continúa fortaleciendo tus habilidades digitales fundamentales.";
                                            break;
                                        case 'B1':
                                            $recommendations[] = "Trabaja en la integración de tecnologías digitales en tu práctica docente.";
                                            break;
                                        case 'B2':
                                            $recommendations[] = "Profundiza en el uso avanzado de herramientas digitales.";
                                            break;
                                        case 'C1':
                                            $recommendations[] = "Lidera iniciativas de innovación digital en tu institución.";
                                            break;
                                        case 'C2':
                                            $recommendations[] = "Comparte tu experiencia y conocimientos con otros docentes.";
                                            break;
                                    }
                                }

                                // Recomendaciones específicas por área
                                foreach ($areaScores as $area) {
                                    if ($area->score < 60) {
                                        $recommendation = "En el área de {$area->name}, considera: ";
                                        switch ($area->name) {
                                            case 'Compromiso profesional':
                                                $recommendation .= "participar en más actividades de desarrollo profesional digital.";
                                                break;
                                            case 'Práctica pedagógica':
                                                $recommendation .= "explorar nuevas estrategias pedagógicas digitales.";
                                                break;
                                            case 'Empoderamiento de los estudiantes':
                                                $recommendation .= "fomentar más la autonomía digital de tus estudiantes.";
                                                break;
                                            default:
                                                $recommendation .= "mejorar tus habilidades en esta área.";
                                        }
                                        $recommendations[] = $recommendation;
                                    }
                                }

                                return implode("\n", $recommendations);
                            })
                            ->listWithLineBreaks(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestResults::route('/'),
            'view' => Pages\ViewTestResult::route('/{record}'),
        ];
    }
} 