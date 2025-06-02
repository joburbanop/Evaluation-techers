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
use Illuminate\Support\HtmlString;

class TestResultResource extends Resource
{
    protected static ?string $model = TestAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Resultados de Tests';

    protected static ?string $modelLabel = 'Resultado de Test';

    protected static ?string $pluralModelLabel = 'Resultados de Tests';

    protected static ?string $navigationGroup = 'Evaluaciones';

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
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('competency_level')
                                    ->label('Nivel de Competencia')
                                    ->state(function (TestAssignment $record) {
                                        $totalScore = $record->responses->sum(function ($response) {
                                            return $response->option->score ?? 0;
                                        });
                                        $maxPossibleScore = $record->responses->sum(function ($response) {
                                            return $response->question->options->max('score');
                                        });
                                        $percentage = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;
                                        
                                        $level = match(true) {
                                            $percentage >= 90 => ['Experto', 'success'],
                                            $percentage >= 75 => ['Avanzado', 'primary'],
                                            $percentage >= 60 => ['Intermedio', 'warning'],
                                            $percentage >= 40 => ['Básico', 'danger'],
                                            default => ['Novato', 'gray']
                                        };
                                        
                                        return new HtmlString("
                                            <div class='flex flex-col items-center p-4 bg-white rounded-lg shadow'>
                                                <div class='text-2xl font-bold text-{$level[1]}-600'>{$level[0]}</div>
                                                <div class='text-sm text-gray-600'>{$percentage}%</div>
                                            </div>
                                        ");
                                    })
                                    ->columnSpan(1),
                                TextEntry::make('total_score')
                                    ->label('Puntuación Total')
                                    ->state(function (TestAssignment $record) {
                                        $totalScore = $record->responses->sum(function ($response) {
                                            return $response->option->score ?? 0;
                                        });
                                        
                                        $maxPossibleScore = $record->responses->sum(function ($response) {
                                            return $response->question->options->max('score');
                                        });
                                        
                                        $percentage = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;
                                        
                                        return new HtmlString("
                                            <div class='flex flex-col items-center p-4 bg-white rounded-lg shadow'>
                                                <div class='text-2xl font-bold text-primary-600'>{$totalScore}/{$maxPossibleScore}</div>
                                                <div class='text-sm text-gray-600'>puntos obtenidos</div>
                                                <div class='text-sm text-gray-600 mt-1'>{$percentage}% del total</div>
                                            </div>
                                        ");
                                    })
                                    ->columnSpan(1),
                                TextEntry::make('correct_answers')
                                    ->label('Respuestas Correctas')
                                    ->state(function (TestAssignment $record) {
                                        $correct = $record->responses->where('is_correct', true)->count();
                                        $total = $record->responses->count();
                                        $percentage = $total > 0 ? ($correct / $total) * 100 : 0;
                                        
                                        return new HtmlString("
                                            <div class='flex flex-col items-center p-4 bg-white rounded-lg shadow'>
                                                <div class='text-2xl font-bold text-success-600'>{$correct}/{$total}</div>
                                                <div class='text-sm text-gray-600'>{$percentage}%</div>
                                            </div>
                                        ");
                                    })
                                    ->columnSpan(1),
                            ]),
                    ]),

                Section::make('Gráfico de Competencias')
                    ->schema([
                        TextEntry::make('radar_chart')
                            ->label('Distribución de Competencias')
                            ->state(function (TestAssignment $record) {
                                $areas = \App\Models\Area::all();
                                $data = [];
                                
                                foreach ($areas as $area) {
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
                                    
                                    $data[] = [
                                        'area' => $area->name,
                                        'score' => $percentage
                                    ];
                                }
                                
                                return new HtmlString("
    <div class='p-4 bg-white rounded-lg shadow flex justify-center items-center'>
        <canvas id='radarChart' width='500' height='500' data-chart='" . htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') . "' style='background: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb;'></canvas>
    </div>
    <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('radarChart');
            const data = JSON.parse(ctx.dataset.chart);
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: data.map(item => item.area),
                    datasets: [{
                        label: 'Nivel de Competencia',
                        data: data.map(item => item.score),
                        backgroundColor: 'rgba(99, 102, 241, 0.2)', // indigo with opacity
                        borderColor: 'rgba(99, 102, 241, 1)', // indigo
                        borderWidth: 3,
                        pointBackgroundColor: 'rgba(245, 158, 11, 1)', // amber
                        pointBorderColor: '#ffffff',
                        pointRadius: 6,
                        pointHoverRadius: 9,
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                color: '#374151', // gray-700
                                font: {
                                    size: 16,
                                    weight: 'bold',
                                    family: 'Inter, sans-serif'
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1f2937', // gray-800
                            titleColor: '#f9fafb', // gray-50
                            bodyColor: '#f3f4f6', // gray-100
                            borderColor: '#4b5563', // gray-600
                            borderWidth: 1,
                            padding: 12,
                            bodyFont: {
                                size: 14,
                                weight: '500',
                                family: 'Inter, sans-serif'
                            },
                            titleFont: {
                                size: 16,
                                weight: 'bold',
                                family: 'Inter, sans-serif'
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.raw + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        r: {
                            angleLines: {
                                color: '#111827', // gris muy oscuro
                                lineWidth: 2.5
                            },
                            grid: {
                                color: '#111827', // gris muy oscuro
                                lineWidth: 2.5,
                                circular: true
                            },
                            pointLabels: {
                                color: '#4b5563', // gray-600
                                font: {
                                    size: 14,
                                    weight: '600',
                                    family: 'Inter, sans-serif'
                                },
                                backdropColor: 'rgba(255, 255, 255, 0.8)'
                            },
                            ticks: {
                                display: true,
                                color: '#6b7280', // gray-500
                                font: {
                                    size: 12,
                                    family: 'Inter, sans-serif'
                                },
                                stepSize: 20,
                                backdropColor: 'rgba(255, 255, 255, 0)',
                                z: 10
                            },
                            suggestedMin: 0,
                            suggestedMax: 100
                        }
                    },
                    elements: {
                        line: {
                            tension: 0.1 // smoother lines
                        }
                    }
                }
            });
        });
    </script>
");
                            })
                            ->columnSpanFull(),
                    ]),

                Section::make('Puntuación por Área')
                    ->schema([
                        Grid::make(3)
                            ->schema(function (TestAssignment $record) {
                                $areas = \App\Models\Area::all();
                                return $areas->map(function ($area) use ($record) {
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
                                    $correctAnswers = $areaResponses->where('is_correct', true)->count();
                                    $totalQuestions = $areaResponses->count();

                                    $level = match(true) {
                                        $percentage >= 90 => ['Experto', 'success'],
                                        $percentage >= 75 => ['Avanzado', 'primary'],
                                        $percentage >= 60 => ['Intermedio', 'warning'],
                                        $percentage >= 40 => ['Básico', 'danger'],
                                        default => ['Novato', 'gray']
                                    };

                                    return TextEntry::make("area_{$area->id}")
                                        ->label($area->name)
                                        ->state(function () use ($totalScore, $maxPossibleScore, $percentage, $level, $correctAnswers, $totalQuestions) {
                                            return new HtmlString("
                                                <div class='flex flex-col items-center p-4 bg-white rounded-lg shadow'>
                                                    <div class='text-2xl font-bold text-{$level[1]}-600'>{$percentage}%</div>
                                                    <div class='text-sm text-gray-600'>{$totalScore}/{$maxPossibleScore} puntos</div>
                                                    <div class='mt-2 text-sm font-medium text-{$level[1]}-500'>{$level[0]}</div>
                                                    <div class='mt-2 text-sm text-gray-500'>
                                                        {$correctAnswers}/{$totalQuestions} correctas
                                                    </div>
                                                </div>
                                            ");
                                        });
                                })->toArray();
                            }),
                    ]),

                Section::make('Respuestas Detalladas')
                    ->schema([
                        RepeatableEntry::make('responses')
                            ->schema([
                                TextEntry::make('question.question')
                                    ->label('Pregunta')
                                    ->weight(FontWeight::Bold)
                                    ->columnSpan(2),
                                TextEntry::make('option.text')
                                    ->label('Tu respuesta')
                                    ->color(fn ($record) => $record->is_correct ? 'success' : 'danger')
                                    ->icon(fn ($record) => $record->is_correct ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),
                                TextEntry::make('option.score')
                                    ->label('Puntuación')
                                    ->formatStateUsing(fn ($state) => "{$state} puntos")
                                    ->color(fn ($state) => match(true) {
                                        $state >= 3 => 'success',
                                        $state >= 2 => 'warning',
                                        default => 'danger'
                                    }),
                                TextEntry::make('feedback')
                                    ->label('Retroalimentación')
                                    ->visible(fn ($record) => filled($record->feedback))
                                    ->columnSpan(2),
                                TextEntry::make('justification')
                                    ->label('Justificación')
                                    ->visible(fn ($record) => filled($record->justification))
                                    ->columnSpan(2),
                            ])
                            ->columns(3),
                    ]),

                Section::make('Recomendaciones')
                    ->schema([
                        TextEntry::make('recommendations')
                            ->label('Recomendaciones')
                            ->state(function (TestAssignment $record) {
                                $totalScore = $record->responses->sum(function ($response) {
                                    return $response->option->score ?? 0;
                                });
                                
                                $maxPossibleScore = $record->responses->sum(function ($response) {
                                    return $response->question->options->max('score');
                                });
                                
                                $percentage = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;
                                
                                $competencyLevel = match(true) {
                                    $percentage >= 90 => 'Experto',
                                    $percentage >= 75 => 'Avanzado',
                                    $percentage >= 60 => 'Intermedio',
                                    $percentage >= 40 => 'Básico',
                                    default => 'Novato'
                                };

                                $areaScores = \App\Models\Area::with(['questions' => function ($query) use ($record) {
                                    $query->whereHas('responses', function ($q) use ($record) {
                                        $q->where('test_assignment_id', $record->id);
                                    });
                                }])->get()->map(function ($area) use ($record) {
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
                                    
                                    $score = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;

                                    return (object)[
                                        'name' => $area->name,
                                        'score' => $score
                                    ];
                                });

                                $recommendations = [];
                                
                                // Recomendaciones generales
                                switch ($competencyLevel) {
                                    case 'Novato':
                                        $recommendations[] = "• Enfócate en desarrollar habilidades básicas de competencia digital.";
                                        break;
                                    case 'Básico':
                                        $recommendations[] = "• Continúa fortaleciendo tus habilidades digitales fundamentales.";
                                        break;
                                    case 'Intermedio':
                                        $recommendations[] = "• Trabaja en la integración de tecnologías digitales en tu práctica docente.";
                                        break;
                                    case 'Avanzado':
                                        $recommendations[] = "• Profundiza en el uso avanzado de herramientas digitales.";
                                        break;
                                    case 'Experto':
                                        $recommendations[] = "• Lidera iniciativas de innovación digital en tu institución.";
                                        break;
                                }

                                // Recomendaciones específicas por área
                                foreach ($areaScores as $area) {
                                    if ($area->score < 60) {
                                        $recommendation = "• En el área de {$area->name}, considera: ";
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
                                            case 'TECNOLOGIAS DIGITALES':
                                                $recommendation .= "mejorar tus habilidades en el uso de herramientas digitales.";
                                                break;
                                            case 'ENSEÑANZA Y APRENDIZAJE':
                                                $recommendation .= "incorporar más tecnologías digitales en tus métodos de enseñanza.";
                                                break;
                                            default:
                                                $recommendation .= "mejorar tus habilidades en esta área.";
                                        }
                                        $recommendations[] = $recommendation;
                                    }
                                }

                                return implode("\n", $recommendations);
                            })
                            ->listWithLineBreaks()
                            ->bulleted(),
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