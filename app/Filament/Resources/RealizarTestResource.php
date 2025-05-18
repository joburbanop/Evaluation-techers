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
                Tables\Columns\ImageColumn::make('test.image')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->test->name).'&color=FFFFFF&background=4f46e5'),
                
                Tables\Columns\TextColumn::make('test.name')
                    ->label('Test')
                    ->description(fn ($record) => $record->test->description)
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('test.questions_count')
                    ->label('Preguntas')
                    ->counts('test', 'questions')
                    ->badge()
                    ->color('primary'),
                
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
                    ->label('Estado')
                    ->default('pending'),
            ])

->actions([
    Tables\Actions\Action::make('responder')
        ->label(fn (TestAssignment $record) => $record->status === 'completed' ? 'Revisar' : 'Comenzar/Continuar Test')
        ->icon(fn (TestAssignment $record) => $record->status === 'completed' ? 'heroicon-o-eye' : 'heroicon-o-play')
        ->color(fn (TestAssignment $record) => $record->status === 'completed' ? 'success' : 'primary')
        ->button()
        ->size('sm')
        ->modalHeading(fn (TestAssignment $record) => 'Test: ' . $record->test->name)
        ->modalDescription(fn (TestAssignment $record) => $record->test->description)
        ->modalWidth('5xl')
        ->form(function (TestAssignment $record) {
            // Si el test está completado, mostramos los resultados
            if ($record->status === 'completed') {
                $questions = $record->test->questions()
                    ->with(['options', 'responses' => function($q) use ($record) {
                        $q->where('test_assignment_id', $record->id);
                    }])
                    ->get();
                
                $fields = [
                    Forms\Components\Placeholder::make('completed_info')
                        ->label('Resultados del Test')
                        ->content('Este test ya ha sido completado. A continuación puedes revisar tus respuestas:')
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6']),
                ];
                
                foreach ($questions as $index => $question) {
                    $selectedOptionId = $question->responses->first()->option_id ?? null;
                    $correctOptionId = $question->options->where('is_correct', true)->first()->id ?? null;
                    
                    $fields[] = Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\ViewField::make("question_{$question->id}_header")
                                ->view('question-header', [
                                    'index' => $index + 1,
                                    'totalQuestions' => $questions->count(),
                                    'questionText' => $question->question
                                ]),
                            
                            Forms\Components\ViewField::make("answers.{$question->id}")
                                ->view('test-result', [
                                    'options' => $question->options,
                                    'selectedOptionId' => $selectedOptionId,
                                    'correctOptionId' => $correctOptionId
                                ])
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'mb-6 border-2 border-gray-200 rounded-xl p-6 shadow-sm hover:border-primary-300 transition-colors duration-200']);
                }
                
                return $fields;
            }
            
            // Código para tests pendientes o en progreso
            $questions = $record->test->questions()->with('options')->get();
            $questionsPerPage = 5;
            
            if ($questions->isEmpty()) {
                return [
                    Forms\Components\Placeholder::make('no-questions')
                        ->label('No hay preguntas disponibles')
                        ->content('Este test no tiene preguntas configuradas.')
                        ->columnSpanFull()
                        ->extraAttributes(['class' => 'bg-yellow-50 p-4 rounded-lg border border-yellow-200']),
                ];
            }
        
            $totalPages = ceil($questions->count() / $questionsPerPage);
        
            $formFields = [
                Forms\Components\Hidden::make('current_page')
                    ->default(0),
                    
                Forms\Components\Hidden::make('answers')
                    ->default([])
                    ->dehydrated(true)
                    ->afterStateHydrated(function ($component, $state) {
                        if (empty($state)) {
                            $component->state([]);
                        }
                    }),
                    
                Forms\Components\Placeholder::make('progress')
                    ->label('Progreso del Test')
                    ->content(function ($get) use ($questions, $questionsPerPage) {
                        $currentPage = $get('current_page') ?? 0;
                        $currentQuestion = ($currentPage * $questionsPerPage) + 1;
                        $lastQuestion = min(($currentPage + 1) * $questionsPerPage, $questions->count());
                        $progress = ($lastQuestion / $questions->count()) * 100;
                        
                        return view('test-progress', [
                            'currentRange' => $currentQuestion . '-' . $lastQuestion,
                            'totalQuestions' => $questions->count(),
                            'progress' => $progress
                        ]);
                    })
                    ->columnSpanFull(),
            ];
        
            for ($page = 0; $page < $totalPages; $page++) {
                $pageQuestions = $questions->slice($page * $questionsPerPage, $questionsPerPage);
                $firstQuestionNumber = ($page * $questionsPerPage) + 1;
                
                $formFields[] = Forms\Components\Group::make()
                    ->id("page-{$page}")
                    ->hidden(fn ($get) => ($get('current_page') ?? 0) != $page)
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema(function () use ($pageQuestions, $questions, $firstQuestionNumber) {
                                $fields = [];
                                
                                foreach ($pageQuestions as $index => $question) {
                                    $globalIndex = $firstQuestionNumber + $index - 1;
                                    
                                    $fields[] = Forms\Components\Card::make()
                                        ->schema([
                                            Forms\Components\ViewField::make("question_{$question->id}_header")
                                                ->view('question-header', [
                                                    'index' => $globalIndex,
                                                    'totalQuestions' => $questions->count(),
                                                    'questionText' => $question->question
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
                                                ->afterStateUpdated(function ($state, $set, $get) use ($question) {
                                                    $currentAnswers = $get('answers') ?? [];
                                                    if (!is_array($currentAnswers)) {
                                                        $currentAnswers = [];
                                                    }
                                                    $currentAnswers[$question->id] = $state;
                                                    $set('answers', $currentAnswers);
                                                    \Log::info('Respuesta actualizada:', [
                                                        'question_id' => $question->id,
                                                        'state' => $state,
                                                        'currentAnswers' => $currentAnswers
                                                    ]);
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

                            // Botón Guardar
                            Forms\Components\Actions\Action::make('guardar')
                                ->label('Guardar progreso')
                                ->color('primary')
                                ->icon('heroicon-o-bookmark')
                                ->iconPosition('before')
                                ->extraAttributes(['class' => 'px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700'])
                                ->action(function (TestAssignment $record, array $data) {
                                    \Log::info('Datos recibidos en guardar:', $data);
                                    
                                    $hasAnswers = false;
                                    if (isset($data['answers']) && is_array($data['answers'])) {
                                        foreach ($data['answers'] as $questionId => $optionId) {
                                            if (!empty($optionId)) {
                                                $hasAnswers = true;
                                                $record->responses()->updateOrCreate(
                                                    ['question_id' => $questionId],
                                                    ['option_id' => $optionId, 'user_id' => auth()->id()]
                                                );
                                            }
                                        }
                                    }

                                    if ($hasAnswers) {
                                        $record->update([
                                            'status' => 'in_progress',
                                        ]);
                                        Notification::make()
                                            ->title('Avance guardado')
                                            ->success()
                                            ->body('Tu avance ha sido guardado. Puedes continuar después.')
                                            ->send();
                                    } else {
                                        Notification::make()
                                            ->title('Sin respuestas')
                                            ->warning()
                                            ->body('No has respondido ninguna pregunta en esta página. Puedes continuar después.')
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

                            // Botón Enviar
                            Forms\Components\Actions\Action::make('enviar')
                                ->label('Enviar respuestas')
                                ->color('success')
                                ->icon('heroicon-o-check')
                                ->iconPosition('before')
                                ->extraAttributes(['class' => 'px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700'])
                                ->hidden(fn ($get) => ($get('current_page') ?? 0) < ($totalPages - 1))
                                ->action(function (TestAssignment $record, array $data) use ($questions, $questionsPerPage, $totalPages) {
                                    \Log::info('Datos recibidos en enviar:', $data);
                                    
                                    $currentPage = $data['current_page'] ?? 0;
                                    $startIndex = $currentPage * $questionsPerPage;
                                    $questionsInCurrentPage = $questions->slice($startIndex, $questionsPerPage);
                                    
                                    $hasAnswersInCurrentPage = false;
                                    $answeredQuestions = [];
                                    
                                    // Verificar si hay respuestas en la página actual
                                    foreach ($questionsInCurrentPage as $question) {
                                        $answerKey = "answers.{$question->id}";
                                        if (isset($data[$answerKey]) && !empty($data[$answerKey])) {
                                            $hasAnswersInCurrentPage = true;
                                            $answeredQuestions[] = $question->id;
                                        }
                                    }

                                    if (!$hasAnswersInCurrentPage) {
                                        $questionIds = $questionsInCurrentPage->pluck('id')->toArray();
                                        $providedAnswers = $answeredQuestions;
                                        
                                        \Log::info('Validación fallida:', [
                                            'currentPage' => $currentPage + 1,
                                            'totalPages' => $totalPages,
                                            'questionIds' => $questionIds,
                                            'providedAnswers' => $providedAnswers,
                                            'data' => $data
                                        ]);
                                        
                                        Notification::make()
                                            ->title('Respuestas requeridas')
                                            ->warning()
                                            ->body(view('filament.notifications.test-validation', [
                                                'currentPage' => $currentPage + 1,
                                                'totalPages' => $totalPages,
                                                'questionIds' => $questionIds,
                                                'providedAnswers' => $providedAnswers,
                                                'answers' => $data
                                            ]))
                                            ->send();
                                        return;
                                    }

                                    try {
                                        // Guardar todas las respuestas
                                        foreach ($questionsInCurrentPage as $question) {
                                            $answerKey = "answers.{$question->id}";
                                            if (isset($data[$answerKey]) && !empty($data[$answerKey])) {
                                                $record->responses()->updateOrCreate(
                                                    ['question_id' => $question->id],
                                                    ['option_id' => $data[$answerKey], 'user_id' => auth()->id()]
                                                );
                                            }
                                        }

                                        $record->update([
                                            'status' => 'completed',
                                            'completed_at' => now(),
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
            ->with(['test.questions.options'])
            ->orderByRaw("CASE 
                WHEN status = 'pending' THEN 1 
                WHEN status = 'in_progress' THEN 2 
                WHEN status = 'completed' THEN 3 
                ELSE 4 
            END")
            ->orderBy('created_at', 'asc');
    }
}