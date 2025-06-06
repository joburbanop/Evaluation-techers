<?php

namespace App\Filament\Resources;
use App\Policies\RealizarTestPolicy;
use App\Filament\Resources\RealizarTestResource\Pages;
use App\Models\TestAssignment;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Forms\Components\ViewField;
use Filament\Tables\Filters\SelectFilter;

class RealizarTestResource extends Resource
{
    protected static ?string $model = TestAssignment::class;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Realizar Test';
    protected static ?string $navigationGroup = 'Evaluaciones';
    protected static ?string $modelLabel = 'Test Asignado';

    // Deshabilitar la creación de registros
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('test.questions')
                    ->label('Preguntas')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->test) {
                            return '<span style="color: red;">Sin test</span>';
                        }
                        $count = $record->test->questions ? $record->test->questions->count() : 0;
                        return '<span style="display: inline-flex; align-items: center; font-weight: bold; color: #4f46e5;">'
                            .'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                            .$count.' preguntas</span>';
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('avance_calculado')
                    ->label('Avance')
                    ->getStateUsing(function ($record) {
                        $total = $record->test?->questions?->count() ?? 0;
                        $respondidas = $record->responses?->count() ?? 0;
                        // Si el estado es completado, siempre 100%
                        $porcentaje = $record->status === 'completed'
                            ? 100
                            : ($total > 0 ? round(($respondidas / $total) * 100) : 0);

                        return '<span style="display: inline-flex; align-items: center; font-weight: bold; color: #059669;">'
                            .'<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-1">'
                            .'<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" /></svg>'
                            .$porcentaje.'%</span>';
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Asignado')
                    ->dateTime('d M Y, H:i')
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
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pendientes',
                        'in_progress' => 'En progreso',
                        'completed' => 'Completados',
                        'expired' => 'Expirados',
                    ])
                    ->label('Estado'),
            ])

->actions([
    Tables\Actions\Action::make('responder')
        ->label(fn (TestAssignment $record) => $record?->status === 'completed' ? 'Revisar' : 'Comenzar/Continuar Test')
        ->icon(fn (TestAssignment $record) => $record?->status === 'completed' ? 'heroicon-o-eye' : 'heroicon-o-play')
        ->color(fn (TestAssignment $record) => $record?->status === 'completed' ? 'success' : 'primary')
        ->button()
        ->size('sm')
        ->modalHeading(fn (TestAssignment $record) => 'Test: ' . ($record?->test?->name ?? 'Test'))
        ->modalDescription(fn (TestAssignment $record) => $record?->test?->description ?? '')
        ->modalWidth('5xl')
        ->modalSubmitAction(false)
        ->modalCancelAction(false)
        ->form(function (TestAssignment $record) {
            if (!$record) {
                return [
                    Forms\Components\Placeholder::make('error')
                        ->label('Error')
                        ->content('No se pudo cargar el test.')
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'bg-red-50 p-4 rounded-lg border border-red-200']),
                ];
            }

            // Si el test está completado, mostramos los resultados
            if ($record->status === 'completed') {
                $questions = $record->test->questions()
                    ->with(['options', 'responses' => function($q) use ($record) {
                        $q->where('test_assignment_id', $record->id);
                    }])
                    ->get();

                $fields = [
                    Forms\Components\Section::make('Resumen General')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\Card::make()
                                        ->schema([
                                            Forms\Components\Placeholder::make('total_score')
                                                ->label('Puntuación Total')
                                                ->content(function () use ($record) {
                                                    //
                                                    // 1) CALCULAR PUNTAJE GLOBAL
                                                    //
                                                    $totalScore = $record->responses->sum(function ($response) {
                                                        return $response->option->score ?? 0;
                                                    });

                                                    $maxPossibleScore = $record->responses->sum(function ($response) {
                                                        return $response->question->options->max('score');
                                                    });

                                                    $percentage = $maxPossibleScore > 0
                                                        ? round(($totalScore / $maxPossibleScore) * 100)
                                                        : 0;

                                                    $nivelGlobal = \App\Models\CompetencyLevel::getLevelByScore($totalScore);

                                                    //
                                                    // 2) CALCULAR PERCENTIL GLOBAL ENTRE TODOS LOS DOCENTES
                                                    //
                                                    $allScores = \App\Models\TestAssignment::with(['responses.option'])
                                                        ->where('test_id', $record->test_id)
                                                        ->where('status', 'completed')
                                                        ->get()
                                                        ->map(function ($assignment) {
                                                            return $assignment->responses->sum(function ($resp) {
                                                                return $resp->option->score ?? 0;
                                                            });
                                                        })
                                                        ->sort()
                                                        ->values();

                                                    $percentileRankGlobal = 0;
                                                    $totalAssignments = $allScores->count();
                                                    if ($totalAssignments > 0) {
                                                        $below = $allScores->filter(fn($s) => $s < $totalScore)->count();
                                                        $equal = $allScores->filter(fn($s) => $s === $totalScore)->count();
                                                        $percentileRankGlobal = round((($below + 0.5 * $equal) / $totalAssignments) * 100);
                                                    }

                                                    //
                                                    // 3) CALCULAR PERCENTIL POR INSTITUCIÓN
                                                    //
                                                    $userInstitutionId = auth()->user()->institution_id;
                                                    $institutionScores = \App\Models\TestAssignment::with(['responses.option','user'])
                                                        ->where('test_id', $record->test_id)
                                                        ->where('status', 'completed')
                                                        ->whereHas('user', function($q) use ($userInstitutionId) {
                                                            $q->where('institution_id', $userInstitutionId);
                                                        })
                                                        ->get()
                                                        ->map(function($assignment) {
                                                            return $assignment->responses->sum(fn($r) => $r->option->score ?? 0);
                                                        })
                                                        ->sort()
                                                        ->values();

                                                    $percentileInstitution = 0;
                                                    $totalInst = $institutionScores->count();
                                                    if ($totalInst > 0) {
                                                        $belowInst = $institutionScores->filter(fn($s) => $s < $totalScore)->count();
                                                        $equalInst = $institutionScores->filter(fn($s) => $s === $totalScore)->count();
                                                        $percentileInstitution = round((($belowInst + 0.5 * $equalInst) / $totalInst) * 100);
                                                    }

                                                    //
                                                    // 4) CALCULAR PERCENTIL POR PROGRAMA
                                                    //
                                                    $userProgramId = auth()->user()->programa_id;
                                                    $programScores = \App\Models\TestAssignment::with(['responses.option','user'])
                                                        ->where('test_id', $record->test_id)
                                                        ->where('status', 'completed')
                                                        ->whereHas('user', function($q) use ($userProgramId) {
                                                            $q->where('programa_id', $userProgramId);
                                                        })
                                                        ->get()
                                                        ->map(function($assignment) {
                                                            return $assignment->responses->sum(fn($r) => $r->option->score ?? 0);
                                                        })
                                                        ->sort()
                                                        ->values();

                                                    $percentileProgram = 0;
                                                    $totalProg = $programScores->count();
                                                    if ($totalProg > 0) {
                                                        $belowProg = $programScores->filter(fn($s) => $s < $totalScore)->count();
                                                        $equalProg = $programScores->filter(fn($s) => $s === $totalScore)->count();
                                                        $percentileProgram = round((($belowProg + 0.5 * $equalProg) / $totalProg) * 100);
                                                    }

                                                    //
                                                    // 5) CALCULAR RESULTADOS POR ÁREA
                                                    //
                                                    $questionsConArea = $record->test->questions()
                                                        ->with([
                                                            'options',
                                                            'responses' => function($query) use ($record) {
                                                                $query->where('test_assignment_id', $record->id);
                                                            },
                                                            'area',
                                                        ])
                                                        ->get();

                                                    $areaResults = collect();
                                                    $questionsConArea
                                                        ->groupBy(fn($pregunta) => $pregunta->area->id)
                                                        ->each(function($preguntasDeEstaArea) use (&$areaResults) {
                                                            $areaName = $preguntasDeEstaArea->first()->area->name;

                                                            $obtainedScore = $preguntasDeEstaArea->sum(function($pregunta) {
                                                                return $pregunta->responses->sum(fn($r) => $r->option->score ?? 0);
                                                            });

                                                            $maxPossibleScoreArea = $preguntasDeEstaArea->sum(function($pregunta) {
                                                                return $pregunta->options->max('score') ?? 0;
                                                            });

                                                            $nivelArea = \App\Models\CompetencyLevel::getLevelByScore($obtainedScore);

                                                            $areaResults->push([
                                                                'area_name' => $areaName,
                                                                'obtained_score' => $obtainedScore,
                                                                'max_possible' => $maxPossibleScoreArea,
                                                                'level_code' => $nivelArea?->code ?? 'Sin código',
                                                                'level_description' => $nivelArea?->description ?? 'Sin descripción',
                                                            ]);
                                                        });

                                                    //
                                                    // 6) RETORNAR LA VISTA
                                                    //
                                                    return view('components.score-display', [
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
                                                    ]);
                                                })
                                        ]),


                                ]),
                        ]),



                ];

                return $fields;
            }

            // Código para tests pendientes o en progreso
            $questions = $record->test->questions()
                ->with(['options', 'factor', 'area'])
                ->get()
                ->groupBy('factor.name');

            if ($questions->isEmpty()) {
                return [
                    Forms\Components\Placeholder::make('no-questions')
                        ->label('No hay preguntas disponibles')
                        ->content('Este test no tiene preguntas configuradas.')
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'bg-yellow-50 p-4 rounded-lg border border-yellow-200']),
                ];
            }

            $formFields = [
                Forms\Components\Hidden::make('current_page')
                    ->default(0),

                Forms\Components\Placeholder::make('progress')
                    ->label('Progreso del Test')
                    ->content(function ($get) use ($questions) {
                        $totalQuestions = $questions->flatten()->count();
                        $currentPage = $get('current_page') ?? 0;
                        $questionsPerPage = 5;
                        $currentQuestion = ($currentPage * $questionsPerPage) + 1;
                        $lastQuestion = min(($currentPage + 1) * $questionsPerPage, $totalQuestions);
                        $progress = ($lastQuestion / $totalQuestions) * 100;

                        return view('test-progress', [
                            'currentRange' => $currentQuestion . '-' . $lastQuestion,
                            'totalQuestions' => $totalQuestions,
                            'progress' => $progress
                        ]);
                    })
                    ->columnSpanFull(),
            ];

            $allQuestions = $questions->flatten();
            $questionsPerPage = 5;
            $totalPages = ceil($allQuestions->count() / $questionsPerPage);

            for ($page = 0; $page < $totalPages; $page++) {
                $pageQuestions = $allQuestions->slice($page * $questionsPerPage, $questionsPerPage);
                $firstQuestionNumber = ($page * $questionsPerPage) + 1;

                $formFields[] = Forms\Components\Group::make()
                    ->id("page-{$page}")
                    ->hidden(fn ($get) => ($get('current_page') ?? 0) != $page)
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema(function () use ($pageQuestions, $allQuestions, $firstQuestionNumber) {
                                $fields = [];

                                foreach ($pageQuestions as $index => $question) {
                                    $globalIndex = $firstQuestionNumber + $index - 1;

                                    $fields[] = Forms\Components\Card::make()
                                        ->schema([
                                            Forms\Components\ViewField::make("question_{$question->id}_header")
                                                ->view('question-header', [
                                                    'index' => $globalIndex,
                                                    'totalQuestions' => $allQuestions->count(),
                                                    'questionText' => $question->question,
                                                    'factor' => $question->factor->name,
                                                    'area' => $question->area->name
                                                ]),

                                            Forms\Components\Radio::make("answers.{$question->id}")
                                                ->options(function() use ($question) {
                                                    $options = [];
                                                    foreach ($question->options as $option) {
                                                        $options[$option->id] = new \Illuminate\Support\HtmlString(
                                                            '<div class="flex items-center space-x-3 p-2 rounded hover:bg-gray-50">' .
                                                            '<span class="flex-shrink-0 flex items-center justify-center h-5 w-5 rounded-full bg-gray-100 text-gray-800 border border-gray-300 text-xs">'
                                                            . chr(65 + $option->index) . '</span>' .
                                                            '<span>' . $option->option . '</span>' .
                                                            '</div>'
                                                        );
                                                    }
                                                    return $options;
                                                })
                                                ->label('Selecciona una respuesta:')
                                                ->inline()
                                                ->inlineLabel(false)
                                                ->columnSpanFull()
                                                ->extraAttributes(['class' => 'space-y-3'])
                                                ->live()
                                                ->dehydrated(true)
                                                ->default(function (TestAssignment $record) use ($question) {
                                                    return $record->responses()
                                                        ->where('question_id', $question->id)
                                                        ->value('option_id');
                                                }),
                                        ])
                                        ->columnSpanFull()
                                        ->extraAttributes(['class' => 'mb-6 border-2 border-gray-200 rounded-xl p-6 shadow-sm hover:border-primary-300 transition-colors duration-200']);
                                }

                                return $fields;
                            })
                            ->columns(1)
                            ->columnSpanFull(),

                        Forms\Components\Actions::make([


                            // Botón Guardar
                            Forms\Components\Actions\Action::make('guardar')
                                ->label('Guardar progreso')
                                ->color('primary')
                                ->icon('heroicon-o-bookmark')
                                ->iconPosition('before')
                                ->extraAttributes(['class' => 'px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700'])
                                ->action(function (TestAssignment $record, array $data, $get) {
                                    \Log::info('Datos recibidos en guardar:', [
                                        'data' => $data,
                                        'all_data' => $get(),
                                    ]);

                                    $answers = $get('answers') ?? [];
                                    $allQuestions = $record->test->questions()->get();
                                    $hasAnswers = false;
                                    $guardadas = [];

                                    foreach ($allQuestions as $question) {
                                        $answer = $answers[$question->id] ?? null;
                                        if (!empty($answer)) {
                                            $hasAnswers = true;
                                            $guardadas[$question->id] = $answer;
                                            try {
                                                $response = \App\Models\TestResponse::updateOrCreate(
                                                    [
                                                        'test_assignment_id' => $record->id,
                                                        'question_id' => $question->id
                                                    ],
                                                    [
                                                        'option_id' => $answer,
                                                        'user_id' => auth()->id()
                                                    ]
                                                );
                                                \Log::info('Respuesta guardada:', [
                                                    'response_id' => $response->id,
                                                    'test_assignment_id' => $record->id,
                                                    'question_id' => $question->id,
                                                    'option_id' => $answer
                                                ]);
                                            } catch (\Exception $e) {
                                                \Log::error('Error al guardar respuesta:', [
                                                    'error' => $e->getMessage(),
                                                    'question_id' => $question->id,
                                                    'option_id' => $answer
                                                ]);
                                            }
                                        }
                                    }

                                    \Log::info('Resultado del guardado:', [
                                        'hasAnswers' => $hasAnswers,
                                        'guardadas' => $guardadas
                                    ]);

                                    if ($hasAnswers) {
                                        try {
                                            $record->update([
                                                'status' => 'in_progress',
                                            ]);

                                            // Calcular el progreso
                                            $totalQuestions = $record->test->questions()->count();
                                            $answeredQuestions = $record->responses()->count();
                                            $progress = round(($answeredQuestions / $totalQuestions) * 100);

                                            Notification::make()
                                                ->title('Avance guardado')
                                                ->success()
                                                ->body(view('notifications.test-progress', [
                                                    'progress' => $progress,
                                                    'answeredQuestions' => $answeredQuestions,
                                                    'totalQuestions' => $totalQuestions,
                                                    'remainingQuestions' => $totalQuestions - $answeredQuestions
                                                ]))
                                                ->persistent()
                                                ->actions([
                                                    \Filament\Notifications\Actions\Action::make('continuar')
                                                        ->label('Continuar después')
                                                        ->button()
                                                        ->close(),
                                                ])
                                                ->send();

                                            return redirect()->to(RealizarTestResource::getUrl('index'));
                                        } catch (\Exception $e) {
                                            \Log::error('Error al actualizar estado:', [
                                                'error' => $e->getMessage(),
                                                'record_id' => $record->id
                                            ]);

                                            Notification::make()
                                                ->title('Error al guardar')
                                                ->danger()
                                                ->body('Ha ocurrido un error al guardar el progreso. Por favor, intenta nuevamente.')
                                                ->send();
                                        }
                                    } else {
                                        Notification::make()
                                            ->title('Sin respuestas')
                                            ->warning()
                                            ->body('No has respondido ninguna pregunta en este intento. Puedes continuar después.')
                                            ->send();
                                    }
                                }),


                            // Botón Siguiente
                            Forms\Components\Actions\Action::make('siguiente')
                                ->label('Siguiente')
                                ->color('primary')
                                ->icon('heroicon-o-arrow-right')
                                ->iconPosition('after')
                                ->extraAttributes(['class' => 'px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700'])
                                ->hidden(fn ($get) => ($get('current_page') ?? 0) >= ($totalPages - 1))
                                ->action(function ($set, $get) {
                                    $set('current_page', ($get('current_page') ?? 0) + 1);
                                }),
                                  // Botón Regresar
                            Forms\Components\Actions\Action::make('regresar')
                            ->label('Regresar')
                            ->color('gray')
                            ->icon('heroicon-o-arrow-left')
                            ->iconPosition('before')
                            ->extraAttributes(['class' => 'px-4 py-2 border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50'])
                            ->hidden(fn ($get) => ($get('current_page') ?? 0) === 0)
                            ->action(function ($set, $get) {
                                $set('current_page', ($get('current_page') ?? 0) - 1);
                            }),

                            // Botón Enviar
                            Forms\Components\Actions\Action::make('enviar')
                                ->label('Enviar respuestas')
                                ->color('success')
                                ->icon('heroicon-o-check')
                                ->iconPosition('before')
                                ->extraAttributes(['class' => 'px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700'])
                                ->hidden(fn ($get) => ($get('current_page') ?? 0) < ($totalPages - 1))
                                ->action(function (TestAssignment $record, array $data, $get) {
                                    \Log::info('Datos recibidos en enviar:', [
                                        'data' => $data,
                                        'all_data' => $get(),
                                    ]);

                                    $answers = $get('answers') ?? [];
                                    $allQuestions = $record->test->questions()->get();
                                    $faltantes = [];

                                    foreach ($allQuestions as $question) {
                                        $answer = $answers[$question->id] ?? null;
                                        if (!empty($answer)) {
                                            \App\Models\TestResponse::updateOrCreate(
                                                [
                                                    'test_assignment_id' => $record->id,
                                                    'question_id' => $question->id
                                                ],
                                                [
                                                    'option_id' => $answer,
                                                    'user_id' => auth()->id()
                                                ]
                                            );
                                        } else {
                                            $faltantes[] = $question->id;
                                        }
                                    }

                                    if (count($faltantes) > 0) {
                                        Notification::make()
                                            ->title('Faltan preguntas por responder')
                                            ->warning()
                                            ->body('Debes responder todas las preguntas antes de enviar el test.')
                                            ->send();
                                        return;
                                    }

                                    try {
                                        $record->update([
                                            'status' => 'completed',
                                        ]);

                                        Notification::make()
                                            ->title('Test completado')
                                            ->success()
                                            ->body('Has completado el test correctamente.')
                                            ->send();

                                        return redirect()->to(RealizarTestResource::getUrl('index'));
                                    } catch (\Exception $e) {
                                        \Log::error('Error al guardar respuestas:', [
                                            'error' => $e->getMessage(),
                                            'data' => $data
                                        ]);

                                        Notification::make()
                                            ->title('Error al guardar respuestas')
                                            ->danger()
                                            ->body('Ha ocurrido un error al guardar tus respuestas. Por favor, intenta nuevamente.')
                                            ->send();
                                    }
                                }),
                        ])
                        ->alignEnd()
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'mt-6 flex flex-wrap gap-3 justify-end']),
                    ])
                    ->columnSpanFull();
            }

            return $formFields;
        })
])

            ->bulkActions([])
            ->emptyStateHeading('No tienes tests pendientes')
            ->emptyStateDescription('Cuando te asignen nuevos tests, aparecerán aquí')
            ->emptyStateIcon('heroicon-o-check-badge')
            ->emptyStateActions([]); // Eliminamos todas las acciones del estado vacío
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRealizarTests::route('/'),
            // Eliminadas las páginas de create y edit
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('Realizar test') ?? false;
    }





    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->with(['test.questions', 'responses'])
            ->orderByRaw("CASE
                WHEN status = 'pending' THEN 1
                WHEN status = 'in_progress' THEN 2
                WHEN status = 'completed' THEN 3
                ELSE 4
            END")
            ->orderBy('created_at', 'asc');
    }
}
