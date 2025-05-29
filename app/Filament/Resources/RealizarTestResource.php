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
                                                ->dehydrated(true)
                                                ->default(function (TestAssignment $record) use ($question) {
                                                    return $record->responses()
                                                        ->where('question_id', $question->id)
                                                        ->value('option_id');
                                                })
                                                ->afterStateUpdated(function ($state, $set, $get) use ($question) {
                                                    \Log::info('Radio actualizado:', [
                                                        'question_id' => $question->id,
                                                        'state' => $state,
                                                        'all_data' => $get()
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
                          

                            // Botón Guardar
                            Forms\Components\Actions\Action::make('guardar')
                                ->label('Guardar progreso')
                                ->color('primary')
                                ->icon('heroicon-o-bookmark')
                                ->iconPosition('before')
                                ->extraAttributes(['class' => 'px-4 py-2 border border-transparent rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700'])
                                ->action(function (TestAssignment $record, array $data, $get) use ($pageQuestions) {
                                    \Log::info('Datos recibidos en guardar:', [
                                        'data' => $data,
                                        'all_data' => $get(),
                                        'pageQuestions' => $pageQuestions->pluck('id')->toArray()
                                    ]);
                                    
                                    $hasAnswers = false;
                                    $answers = [];
                                    $allData = $get();
                                    
                                    foreach ($pageQuestions as $question) {
                                        $answer = $allData['answers'][$question->id] ?? null;
                                        
                                        \Log::info('Verificando respuesta:', [
                                            'question_id' => $question->id,
                                            'value' => $answer,
                                        ]);
                                        
                                        if (!empty($answer)) {
                                            $hasAnswers = true;
                                            $answers[$question->id] = $answer;
                                            $record->responses()->updateOrCreate(
                                                ['question_id' => $question->id],
                                                ['option_id' => $answer, 'user_id' => auth()->id()]
                                            );
                                        }
                                    }
                                    
                                    \Log::info('Resultado del guardado:', [
                                        'hasAnswers' => $hasAnswers,
                                        'answers' => $answers
                                    ]);
                                    
                                    if ($hasAnswers) {
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
                                ->action(function (TestAssignment $record, array $data, $get) use ($pageQuestions) {
                                    \Log::info('Datos recibidos en enviar:', [
                                        'data' => $data,
                                        'all_data' => $get(),
                                        'pageQuestions' => $pageQuestions->pluck('id')->toArray()
                                    ]);
                                    
                                    $hasAnswers = false;
                                    $answers = [];
                                    $allData = $get();
                                    
                                    foreach ($pageQuestions as $question) {
                                        $answer = $allData['answers'][$question->id] ?? null;
                                        
                                        \Log::info('Verificando respuesta para enviar:', [
                                            'question_id' => $question->id,
                                            'value' => $answer,
                                        ]);
                                        
                                        if (!empty($answer)) {
                                            $hasAnswers = true;
                                            $answers[$question->id] = $answer;
                                            $record->responses()->updateOrCreate(
                                                ['question_id' => $question->id],
                                                ['option_id' => $answer, 'user_id' => auth()->id()]
                                            );
                                        }
                                    }

                                    \Log::info('Resultado del envío:', [
                                        'hasAnswers' => $hasAnswers,
                                        'answers' => $answers
                                    ]);

                                    if (!$hasAnswers) {
                                        Notification::make()
                                            ->title('Respuestas requeridas')
                                            ->warning()
                                            ->body('Por favor, asegúrate de responder al menos una pregunta en esta página antes de enviar.')
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