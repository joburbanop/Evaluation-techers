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
use App\Models\TestAreaCompetencyLevel;
use Filament\Forms\Components\Select;
use App\Models\User;
use App\Models\Departamento;
use App\Models\Ciudad;
use App\Models\Facultad;
use App\Models\Programa;

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
                Tables\Columns\TextColumn::make('test.name')
                    ->label('Test')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40)
                    ->tooltip(fn (string $state): string => $state),
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
                        // Contar las respuestas únicas por pregunta para evitar duplicados
                        $respondidas = $record->responses()->distinct('question_id')->count();
                        
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
        ->label(fn (TestAssignment $record) => $record?->status === 'completed' ? 'Ver Resultados' : 'Comenzar/Continuar Test')
        ->icon(fn (TestAssignment $record) => $record?->status === 'completed' ? 'heroicon-o-eye' : 'heroicon-o-play')
        ->color(fn (TestAssignment $record) => $record?->status === 'completed' ? 'success' : 'primary')
        ->button()
        ->size('md')
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
                $fields = [
                    Forms\Components\Section::make()
                        ->heading(new \Illuminate\Support\HtmlString(
                            '<h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 pb-4">Resumen General</h2>'
                        ))
                        ->description(function () use ($record) {
                            return new \Illuminate\Support\HtmlString(
                                '<p class="text-base text-gray-500 dark:text-gray-400">' . 
                                e($record->test->description) . 
                                '</p>'
                            );
                        })
                        ->schema([
                             Forms\Components\Placeholder::make('total_score_display')
                                ->label('') // Sin etiqueta
                                ->content(function () use ($record) {
                                    // Excluir preguntas sociodemográficas (area_id = 8)
                                    $nonSociodemographicResponses = $record->responses->filter(function($response) {
                                        return $response->question->area_id !== 8;
                                    });
                                    
                                    $totalScore = $nonSociodemographicResponses->sum(fn($r) => $r->option->score ?? 0);
                                    $maxPossibleScore = $nonSociodemographicResponses->sum(fn($r) => $r->question->options->max('score') ?? 0);
                                    $percentage = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;
                                    $nivelGlobal = \App\Models\TestCompetencyLevel::getLevelForScore($record->test_id, $totalScore);

                                    $completedAssignments = \App\Models\TestAssignment::where('test_id', $record->test_id)->where('status', 'completed')->get();
                                    $levels = \App\Models\TestCompetencyLevel::where('test_id', $record->test_id)->orderBy('min_score')->get();
                                    $totalUsers = $completedAssignments->count();
                                    
                                    // Calcular resultados excluyendo preguntas sociodemográficas
                                    $scores = $completedAssignments->map(function($a) {
                                        $nonSociodemographicResponses = $a->responses->filter(function($response) {
                                            return $response->question->area_id !== 8;
                                        });
                                        return $nonSociodemographicResponses->sum(fn($r) => $r->option->score ?? 0);
                                    });
                                    $usersBelow = $scores->filter(fn($s) => $s < $totalScore)->count();
                                    $percentileRankGlobal = $totalUsers > 0 ? round(($usersBelow / $totalUsers) * 100) : 0;

                                    // Cálculo de resultados por facultad
                                    $userFacultad = auth()->user()->programa?->facultad ?? null;
                                    $percentileRankFacultad = 0;
                                    if ($userFacultad) {
                                        $facultadAssignments = $completedAssignments->filter(function($assignment) use ($userFacultad) {
                                            return $assignment->user->programa?->facultad?->id === $userFacultad->id;
                                        });
                                        $totalUsersFacultad = $facultadAssignments->count();
                                        if ($totalUsersFacultad > 0) {
                                            $scoresFacultad = $facultadAssignments->map(function($a) {
                                                $nonSociodemographicResponses = $a->responses->filter(function($response) {
                                                    return $response->question->area_id !== 8;
                                                });
                                                return $nonSociodemographicResponses->sum(fn($r) => $r->option->score ?? 0);
                                            });
                                            $usersBelowFacultad = $scoresFacultad->filter(fn($s) => $s < $totalScore)->count();
                                            $percentileRankFacultad = round(($usersBelowFacultad / $totalUsersFacultad) * 100);
                                        }
                                    }

                                    // Cálculo de resultados por programa
                                    $userPrograma = auth()->user()->programa ?? null;
                                    $percentileRankPrograma = 0;
                                    if ($userPrograma) {
                                        $programaAssignments = $completedAssignments->filter(function($assignment) use ($userPrograma) {
                                            return $assignment->user->programa?->id === $userPrograma->id;
                                        });
                                        $totalUsersPrograma = $programaAssignments->count();
                                        if ($totalUsersPrograma > 0) {
                                            $scoresPrograma = $programaAssignments->map(function($a) {
                                                $nonSociodemographicResponses = $a->responses->filter(function($response) {
                                                    return $response->question->area_id !== 8;
                                                });
                                                return $nonSociodemographicResponses->sum(fn($r) => $r->option->score ?? 0);
                                            });
                                            $usersBelowPrograma = $scoresPrograma->filter(fn($s) => $s < $totalScore)->count();
                                            $percentileRankPrograma = round(($usersBelowPrograma / $totalUsersPrograma) * 100);
                                        }
                                    }

                                    // Excluir área sociodemográfica (area_id = 8) de los resultados por área
                                    $preguntasAgrupadas = $record->test->questions()
                                        ->with('area')
                                        ->where('area_id', '!=', 8) // Excluir área sociodemográfica
                                        ->get()
                                        ->filter(fn($q) => $q->area)
                                        ->groupBy('area.id');
                                    
                                    $areaResults = collect();
                                    foreach ($preguntasAgrupadas as $areaId => $preguntas) {
                                        $area = $preguntas->first()->area;
                                        $puntajeMaximo = $preguntas->sum(fn($p) => $p->options->max('score') ?? 0);
                                        $puntajeObtenido = $record->responses->whereIn('question_id', $preguntas->pluck('id'))->sum(fn($r) => $r->option->score ?? 0);
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

                                    return view('components.score-display', [
                                        'maxScore' => $maxPossibleScore,
                                        'score' => (string) $totalScore,
                                        'percentage' => $percentage,
                                        'levelName' => $nivelGlobal?->name ?? 'Sin nivel',
                                        'levelDescription' => $nivelGlobal?->description ?? 'Sin descripción',
                                        'levelCode' => $nivelGlobal?->code ?? 'Sin código',
                                        'publicationDate' => \Illuminate\Support\Carbon::parse($record->updated_at)->locale('es')->translatedFormat('d \\D\\E F \\D\\E Y, H:i'),
                                        'applicationDate' => \Illuminate\Support\Carbon::parse($record->created_at)->locale('es')->translatedFormat('d \\D\\E F \\D\\E Y, H:i'),
                                        'percentileInfo' => true,
                                        'percentileRankGlobal' => $percentileRankGlobal,
                                        'percentileRankFacultad' => $percentileRankFacultad,
                                        'percentileRankPrograma' => $percentileRankPrograma,
                                        'evaluatedName' => auth()->user()->full_name,
                                        'identification' => auth()->user()->document_number ?? 'Sin identificación',
                                        'institution' => auth()->user()->institution?->name ?? 'Sin institución',
                                        'program' => auth()->user()->programa?->nombre ?? 'Sin programa',
                                        'facultad' => auth()->user()->programa?->facultad?->nombre ?? 'Sin facultad',
                                        'icon' => 'heroicon-o-academic-cap',
                                        'areaResults' => $areaResults,
                                        'assignmentId' => $record->id,
                                        'testName' => $record->test->name,
                                    ]);
                                })->columnSpanFull(),
                        ]),
                ];

                return $fields;
            }

            // Código para tests pendientes o en progreso
            $allQuestions = $record->test->questions()
                ->with(['options', 'factor', 'area'])
                ->orderByRaw('CASE WHEN area_id = 8 AND factor_id = 8 THEN 0 ELSE 1 END ASC, `order` ASC')
                ->get();

            if ($allQuestions->isEmpty()) {
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
                    ->default(function (TestAssignment $record) use ($allQuestions) {
                        $questionsPerPage = 5;
                        $totalPages = ceil($allQuestions->count() / $questionsPerPage);
                        
                        // Contar el número total de preguntas respondidas
                        $answeredQuestionsCount = $record->responses()
                            ->distinct()
                            ->count('question_id');

                        // Calcular la página basándose en el número de preguntas respondidas
                        // Si respondiste 8 preguntas, estás en página 2 (para completar preguntas 9-10)
                        // Si respondiste 10 preguntas, estás en página 3 (para continuar con preguntas 11-15)
                        if ($answeredQuestionsCount % $questionsPerPage === 0) {
                            // Es múltiplo de 5, ir a la siguiente página
                            $calculatedPage = ($answeredQuestionsCount / $questionsPerPage);
                        } else {
                            // No es múltiplo de 5, ir a la página donde están las preguntas pendientes
                            $calculatedPage = floor($answeredQuestionsCount / $questionsPerPage);
                        }
                        
                        // Asegurar que no exceda el número total de páginas y no sea negativo
                        $pageToReturn = max(0, min($calculatedPage, $totalPages - 1));
                        
                        // Logs para diagnóstico
                        \Log::info('Cálculo de página de inicio:', [
                            'test_assignment_id' => $record->id,
                            'total_questions' => $allQuestions->count(),
                            'answered_questions_count' => $answeredQuestionsCount,
                            'questions_per_page' => $questionsPerPage,
                            'is_multiple_of_5' => ($answeredQuestionsCount % $questionsPerPage === 0),
                            'calculated_page' => $calculatedPage,
                            'total_pages' => $totalPages,
                            'page_to_return' => $pageToReturn
                        ]);
                        
                        // Si no hay respuestas, empezar desde la primera página
                        if ($answeredQuestionsCount === 0) {
                            return 0;
                        }
                        
                        return $pageToReturn;
                    }),

                Forms\Components\Placeholder::make('progress')
                    ->label('Progreso del Test')
                    ->content(function ($get) use ($allQuestions) {
                        $totalQuestions = $allQuestions->flatten()->count();
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

            $questionsPerPage = 5;
            $totalPages = ceil($allQuestions->count() / $questionsPerPage);

            for ($page = 0; $page < $totalPages; $page++) {
                $pageQuestions = $allQuestions->slice($page * $questionsPerPage, $questionsPerPage);
                
                $hasMoocQuestion = $pageQuestions->contains(fn($q) => str_contains($q->question, 'MOOCs*'));

                $pageSchema = [
                    Forms\Components\Group::make()
                        ->schema(function () use ($pageQuestions, $allQuestions) {
                            $fields = [];
                            foreach ($pageQuestions as $question) {
                                $fields[] = Forms\Components\Fieldset::make()
                                    ->label(new \Illuminate\Support\HtmlString('<span class="text-xl font-bold text-gray-800 dark:text-gray-200">' . 'Pregunta ' . $question->order . ': ' . $question->question . '</span>'))
                                    ->id('question-'.$question->id)
                                    ->schema([
                                        // Radio o CheckboxList según el tipo de pregunta
                                        $question->is_multiple 
                                            ? Forms\Components\CheckboxList::make("answers.{$question->id}")
                                                ->options(function() use ($question) {
                                                    $options = [];
                                                    foreach ($question->options as $option) {
                                                        $options[$option->id] = new \Illuminate\Support\HtmlString(
                                                            '<div class="relative group flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm hover:shadow-lg cursor-pointer transition-all duration-200 hover:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-300">
                                                                    <span class="text-base font-semibold text-gray-800 dark:text-gray-100 group-hover:text-indigo-700 dark:group-hover:text-indigo-300 transition">' . e($option->option) . '</span>
                                                                </div>'
                                                            );
                                                    }
                                                    return $options;
                                                })
                                                ->label('Selecciona todas las opciones que apliquen:')
                                                ->columnSpanFull()
                                                ->extraAttributes(['class' => 'space-y-3'])
                                                ->live()
                                                ->dehydrated(true)
                                                ->default(function (TestAssignment $record) use ($question) {
                                                    if ($question->is_multiple) {
                                                        // Para preguntas de selección múltiple, obtener todas las opciones seleccionadas
                                                        $responses = $record->responses()
                                                            ->where('question_id', $question->id)
                                                            ->with('responseOptions')
                                                            ->get();
                                                        
                                                        $selectedOptions = [];
                                                        foreach ($responses as $response) {
                                                            // Agregar la opción principal
                                                            $selectedOptions[] = $response->option_id;
                                                            
                                                            // Agregar las opciones adicionales si existen
                                                            if ($response->responseOptions) {
                                                                foreach ($response->responseOptions as $responseOption) {
                                                                    $selectedOptions[] = $responseOption->id;
                                                                }
                                                            }
                                                        }
                                                        
                                                        return array_unique($selectedOptions);
                                                    } else {
                                                        // Para preguntas de selección única
                                                        return $record->responses()
                                                            ->where('question_id', $question->id)
                                                            ->value('option_id');
                                                    }
                                                })
                                            : Forms\Components\Radio::make("answers.{$question->id}")
                                                ->options(function() use ($question) {
                                                    $options = [];
                                                    foreach ($question->options as $option) {
                                                        $options[$option->id] = new \Illuminate\Support\HtmlString(
                                                            '<div class="relative group flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm hover:shadow-lg cursor-pointer transition-all duration-200 hover:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-300">
                                                                <span class="text-base font-semibold text-gray-800 dark:text-gray-100 group-hover:text-indigo-700 dark:group-hover:text-indigo-300 transition">' . e($option->option) . '</span>
                                                                <span class="absolute right-3 top-1/2 -translate-y-1/2 hidden group-[.filament-radio-option-checked]:block">
                                                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </span>
                                                            </div>'
                                                        );
                                                    }
                                                    return $options;
                                                })
                                                ->label('Selecciona una respuesta:')
                                                ->columnSpanFull()
                                                ->extraAttributes(['class' => 'space-y-3'])
                                                ->live()
                                                ->dehydrated(true)
                                                ->default(function (TestAssignment $record) use ($question) {
                                                    // Para preguntas de selección única
                                                    return $record->responses()
                                                        ->where('question_id', $question->id)
                                                        ->value('option_id');
                                                }),
                                    ])
                                    ->columnSpanFull()
                                    ->extraAttributes(['class' => 'mb-6 p-6']);
                            }
                            return $fields;
                        })
                        ->columns(1)
                        ->columnSpanFull(),
                ];

                if ($hasMoocQuestion) {
                    $pageSchema[] = Forms\Components\Section::make('Definiciones')
                        ->schema([
                            Forms\Components\Placeholder::make('definiciones')
                                ->content(new \Illuminate\Support\HtmlString('
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <h4 class="font-semibold text-gray-800 mb-2">Términos utilizados en este test:</h4>
                                        <ul class="space-y-1 text-sm text-gray-700">
                                            <li><strong>MOOC*:</strong> Massive Open Online Course (Curso Masivo Abierto en Línea)</li>
                                        </ul>
                                    </div>
                                '))
                                ->columnSpanFull()
                        ])
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'mt-6']);
                }
                
                $pageSchema[] = Forms\Components\Actions::make([
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
                                        if ($question->is_multiple) {
                                            // Para preguntas de selección múltiple
                                            $existingResponse = \App\Models\TestResponse::where('test_assignment_id', $record->id)
                                                ->where('question_id', $question->id)
                                                ->where('user_id', auth()->id())
                                                ->first();

                                            if ($existingResponse) {
                                                $existingResponse->options()->delete();
                                                $existingResponse->delete();
                                            }

                                            $response = \App\Models\TestResponse::create([
                                                'test_assignment_id' => $record->id,
                                                'question_id' => $question->id,
                                                'option_id' => $answer[0],
                                                'user_id' => auth()->id()
                                            ]);

                                            foreach ($answer as $optionId) {
                                                \App\Models\TestResponseOption::create([
                                                    'test_response_id' => $response->id,
                                                    'option_id' => $optionId
                                                ]);
                                            }
                                        } else {
                                            // Para preguntas de selección única
                                            \App\Models\TestResponse::updateOrCreate(
                                                [
                                                    'test_assignment_id' => $record->id,
                                                    'question_id' => $question->id,
                                                    'user_id' => auth()->id()
                                                ],
                                                [
                                                    'option_id' => $answer
                                                ]
                                            );
                                        }
                                    } catch (\Exception $e) {
                                        \Log::error('Error al guardar respuesta:', [
                                            'error' => $e->getMessage(),
                                            'question_id' => $question->id,
                                            'answer' => $answer
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

                                    // Notificación simple
                                    Notification::make()
                                        ->title('¡Guardado exitoso!')
                                        ->success()
                                        ->body('Has guardado ' . $answeredQuestions . ' de ' . $totalQuestions . ' preguntas (' . $progress . '%)')
                                        ->send();

                                    \Log::info('Notificación enviada exitosamente');

                                    // Cerrar el modal y refrescar la página
                                    return redirect()->to(RealizarTestResource::getUrl('index'))->with('success', 'Test guardado exitosamente');

                                } catch (\Exception $e) {
                                    \Log::error('Error al actualizar estado:', [
                                        'error' => $e->getMessage(),
                                        'record_id' => $record->id
                                    ]);

                                    Notification::make()
                                        ->title('Error al guardar')
                                        ->danger()
                                        ->body('Ha ocurrido un error al guardar el progreso.')
                                        ->send();
                                }
                            } else {
                                Notification::make()
                                    ->title('Sin respuestas')
                                    ->warning()
                                    ->body('No has respondido ninguna pregunta.')
                                    ->send();
                            }
                        }),

                    // Botón Siguiente
                    Forms\Components\Actions\Action::make('siguiente')
                        ->label('Siguiente')
                        ->color(function ($get) use ($allQuestions, $questionsPerPage) {
                            $currentPage = $get('current_page') ?? 0;
                            $answers = $get('answers') ?? [];
                            
                            // Obtener las preguntas de la página actual
                            $pageQuestions = $allQuestions->slice($currentPage * $questionsPerPage, $questionsPerPage);
                            
                            // Verificar que todas las preguntas de la página actual estén respondidas
                            foreach ($pageQuestions as $question) {
                                $answer = $answers[$question->id] ?? null;
                                if (empty($answer)) {
                                    return 'gray'; // Deshabilitado (gris)
                                }
                            }
                            
                            return 'primary'; // Habilitado (azul)
                        })
                        ->icon('heroicon-o-arrow-right')
                        ->iconPosition('after')
                        ->extraAttributes(function ($get) use ($allQuestions, $questionsPerPage) {
                            $currentPage = $get('current_page') ?? 0;
                            $answers = $get('answers') ?? [];
                            
                            // Obtener las preguntas de la página actual
                            $pageQuestions = $allQuestions->slice($currentPage * $questionsPerPage, $questionsPerPage);
                            $isComplete = true;
                            
                            // Verificar que todas las preguntas de la página actual estén respondidas
                            foreach ($pageQuestions as $question) {
                                $answer = $answers[$question->id] ?? null;
                                if (empty($answer)) {
                                    $isComplete = false;
                                    break;
                                }
                            }
                            
                            if ($isComplete) {
                                return [
                                    'class' => 'px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 cursor-pointer',
                                    'data-action' => 'siguiente'
                                ];
                            } else {
                                return [
                                    'class' => 'px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-gray-400 bg-gray-100 cursor-not-allowed',
                                    'disabled' => 'disabled'
                                ];
                            }
                        })
                        ->hidden(fn ($get) => ($get('current_page') ?? 0) >= ($totalPages - 1))
                        ->action(function ($set, $get) use ($allQuestions, $questionsPerPage) {
                            $currentPage = $get('current_page') ?? 0;
                            $answers = $get('answers') ?? [];
                            
                            // Obtener las preguntas de la página actual
                            $pageQuestions = $allQuestions->slice($currentPage * $questionsPerPage, $questionsPerPage);
                            
                            // Verificar que todas las preguntas de la página actual estén respondidas
                            foreach ($pageQuestions as $question) {
                                $answer = $answers[$question->id] ?? null;
                                if (empty($answer)) {
                                    return; // No hacer nada si faltan respuestas
                                }
                            }
                            
                            // Si todas están respondidas, avanzar a la siguiente página
                            $set('current_page', $currentPage + 1);
                        })
                        ->after(fn () => 'setTimeout(() => { const modalContent = document.querySelector(\'.fi-modal-content\'); if (modalContent) { modalContent.scrollTo({ top: 0, behavior: \'smooth\' }); } }, 50)'),

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
                        })
                        ->after(fn () => 'setTimeout(() => { const modalContent = document.querySelector(\'.fi-modal-content\'); if (modalContent) { modalContent.scrollTo({ top: 0, behavior: \'smooth\' }); } }, 50)'),

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

                            foreach ($allQuestions as $idx => $question) {
                                $answer = $answers[$question->id] ?? null;
                                if (!empty($answer)) {
                                    if ($question->is_multiple) {
                                        // Para preguntas de selección múltiple
                                        // Primero eliminamos todas las respuestas existentes para esta pregunta
                                        $existingResponse = \App\Models\TestResponse::where('test_assignment_id', $record->id)
                                            ->where('question_id', $question->id)
                                            ->where('user_id', auth()->id())
                                            ->first();

                                        if ($existingResponse) {
                                            $existingResponse->options()->delete();
                                            $existingResponse->delete();
                                        }

                                        // Creamos una nueva respuesta
                                        $response = \App\Models\TestResponse::create([
                                            'test_assignment_id' => $record->id,
                                            'question_id' => $question->id,
                                            'option_id' => $answer[0], // Guardamos la primera opción como principal
                                            'user_id' => auth()->id()
                                        ]);

                                        // Guardamos todas las opciones seleccionadas
                                        foreach ($answer as $optionId) {
                                            \App\Models\TestResponseOption::create([
                                                'test_response_id' => $response->id,
                                                'option_id' => $optionId
                                            ]);
                                        }
                                    } else {
                                        // Para preguntas de selección única, actualizar o crear una sola respuesta
                                        \App\Models\TestResponse::updateOrCreate(
                                            [
                                                'test_assignment_id' => $record->id,
                                                'question_id' => $question->id,
                                                'user_id' => auth()->id()
                                            ],
                                            [
                                                'option_id' => $answer
                                            ]
                                        );
                                    }
                                } else {
                                    $faltantes[] = [
                                        'numero' => $idx + 1,
                                        'texto' => $question->question,
                                    ];
                                }
                            }
                           if (count($faltantes) > 0) {
                                $lista = '<ul class="pl-4 mt-2 space-y-1">';
                                foreach ($faltantes as $faltante) {
                                    $lista .= '<li class="list-disc text-sm text-gray-700"><b>Pregunta '.$faltante['numero'].':</b> '. $faltante['texto'] . '</li>';
                                }
                                $lista .= '</ul>';

                                Notification::make()
                                    ->title('Faltan preguntas por responder')
                                    ->warning()
                                    ->body('Debes responder todas las preguntas antes de enviar el test.<br><b>Preguntas pendientes:</b>' . $lista)
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
                ->extraAttributes(['class' => 'mt-6 flex flex-wrap gap-3 justify-end']);

                $formFields[] = Forms\Components\Group::make()
                    ->id("page-{$page}")
                    ->hidden(fn ($get) => ($get('current_page') ?? 0) != $page)
                    ->schema($pageSchema);
            }

            return $formFields;
        })
        ->fillForm(function (TestAssignment $record): array {
            $answers = [];
            $responses = $record->responses()->with(['question', 'options'])->get();

            foreach ($responses as $response) {
                if (!$response->question) {
                    continue;
                }
                $questionId = $response->question_id;

                if ($response->question->is_multiple) {
                    // Para preguntas múltiples, se recogen todas las opciones
                    $answers[$questionId] = $response->options->pluck('option_id')->toArray();
                } else {
                    // Para preguntas simples
                    $answers[$questionId] = $response->option_id;
                }
            }
            
            // Calcular la página de inicio para el log
            $questionsPerPage = 5;
            $allQuestions = $record->test->questions()->get();
            $answeredQuestionsCount = $record->responses()->distinct()->count('question_id');
            
            if ($answeredQuestionsCount % $questionsPerPage === 0) {
                // Es múltiplo de 5, ir a la siguiente página
                $calculatedPage = ($answeredQuestionsCount / $questionsPerPage);
            } else {
                // No es múltiplo de 5, ir a la página donde están las preguntas pendientes
                $calculatedPage = floor($answeredQuestionsCount / $questionsPerPage);
            }
            
            $pageToReturn = max(0, min($calculatedPage, ceil($allQuestions->count() / $questionsPerPage) - 1));
            
            \Log::info('fillForm - Valores calculados:', [
                'test_assignment_id' => $record->id,
                'answered_questions_count' => $answeredQuestionsCount,
                'is_multiple_of_5' => ($answeredQuestionsCount % $questionsPerPage === 0),
                'calculated_page' => $calculatedPage,
                'page_to_return' => $pageToReturn
            ]);
            
            return [
                'answers' => $answers,
                'current_page' => $pageToReturn,
            ];
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
        return auth()->user()?->hasRole('Docente') ?? false;
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
