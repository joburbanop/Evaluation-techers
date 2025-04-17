<?php

namespace App\Filament\Resources;

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
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
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
                
                Tables\Columns\TextColumn::make('due_at')
                    ->label('Fecha límite')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->due_at < now() ? 'danger' : 'success'),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'expired' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Pendiente',
                        'completed' => 'Completado',
                        'expired' => 'Expirado',
                        default => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pendientes',
                        'completed' => 'Completados',
                        'expired' => 'Expirados',
                    ])
                    ->label('Estado')
                    ->default('pending'),
                
                Tables\Filters\Filter::make('expired')
                    ->label('Expirados')
                    ->query(fn (Builder $query): Builder => $query->where('due_at', '<', now())->where('status', 'pending'))
                    ->default(false),
            ])
            ->actions([
                Tables\Actions\Action::make('responder')
                    ->label(fn (TestAssignment $record) => $record->status === 'completed' ? 'Revisar' : 'Comenzar Test')
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
                            // Cargar las preguntas con sus opciones
                            $questions = $record->test->questions()->with('options')->get();
                            
                            // Obtener todas las respuestas del usuario para este test
                            $responses = $record->responses()->with('option')->get()->keyBy('question_id');
                            
                            $fields = [
                                Forms\Components\Placeholder::make('completed_info')
                                    ->label('Resultados del Test')
                                    ->content('Este test ya ha sido completado. A continuación puedes revisar tus respuestas:')
                                    ->columnSpanFull(),
                            ];
                            
                            foreach ($questions as $index => $question) {
                                $selectedOptionId = $responses->get($question->id)?->option_id ?? null;
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
                                    ->extraAttributes(['class' => 'mb-6 border border-gray-200 rounded-xl p-6 shadow-sm']);
                            }
                            
                            return $fields;
                        }
                        
                        // Código para tests pendientes
                        $questions = $record->test->questions()->with('options')->get();
                        $questionsPerPage = 5;
                        
                        if ($questions->isEmpty()) {
                            return [
                                Forms\Components\Placeholder::make('no-questions')
                                    ->label('No hay preguntas disponibles')
                                    ->content('Este test no tiene preguntas configuradas.')
                                    ->columnSpanFull(),
                            ];
                        }
                    
                        $totalPages = ceil($questions->count() / $questionsPerPage);
                    
                        $formFields = [
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
                                                            ->options($question->options->pluck('option', 'id')->toArray())
                                                            ->required()
                                                            ->label('Selecciona una respuesta:')
                                                            ->inline()
                                                            ->inlineLabel(false)
                                                            ->columnSpanFull()
                                                            ->extraAttributes(['class' => 'space-y-2']),
                                                    ])
                                                    ->columnSpanFull()
                                                    ->extraAttributes(['class' => 'mb-6 border border-gray-200 rounded-xl p-6 shadow-sm']);
                                            }
                                            
                                            return $fields;
                                        })
                                        ->columns(1)
                                        ->columnSpanFull(),
                                    
                                    Forms\Components\Actions::make([
                                        Forms\Components\Actions\Action::make('previous_page')
                                            ->label('Anterior')
                                            ->color('gray')
                                            ->icon('heroicon-o-arrow-left')
                                            ->hidden($page === 0)
                                            ->action(function ($set) use ($page) {
                                                $set('current_page', $page - 1);
                                            })
                                            ->extraAttributes(['class' => 'px-6 py-3 rounded-lg']),
                                        
                                        Forms\Components\Actions\Action::make('next_page')
                                            ->label($page === $totalPages - 1 ? 'Finalizar Test' : 'Siguiente')
                                            ->color('primary')
                                            ->icon($page === $totalPages - 1 ? 'heroicon-o-check' : 'heroicon-o-arrow-right')
                                            ->hidden($totalPages <= 1)
                                            ->submit($page === $totalPages - 1)
                                            ->action(function ($set) use ($page) {
                                                $set('current_page', $page + 1);
                                            })
                                            ->extraAttributes(['class' => 'px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white']),
                                    ])
                                    ->alignEnd()
                                    ->columnSpanFull()
                                    ->extraAttributes(['class' => 'mt-6 space-x-3']),
                                ])
                                ->columnSpanFull();
                        }
                        
                        return $formFields;
                    })
                    ->action(function (TestAssignment $record, array $data) {
                        // Solo procesar acción si el test está pendiente
                        if ($record->status === 'pending') {
                            try {
                                if (empty($data['answers'])) {
                                    throw new \Exception('No se han proporcionado respuestas.');
                                }
            
                                foreach ($data['answers'] as $questionId => $optionId) {
                                    $record->responses()->updateOrCreate(
                                        ['question_id' => $questionId],
                                        ['option_id' => $optionId, 'user_id' => auth()->id()]
                                    );
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
                                Notification::make()
                                    ->title('Error')
                                    ->danger()
                                    ->body('Error al guardar respuestas: ' . $e->getMessage())
                                    ->send();
                            }
                        }
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->with(['test.questions.options'])
            ->orderByRaw("CASE 
                WHEN status = 'pending' AND due_at > NOW() THEN 1 
                WHEN status = 'pending' AND due_at <= NOW() THEN 2 
                WHEN status = 'completed' THEN 3 
                ELSE 4 
            END")
            ->orderBy('due_at', 'asc');
    }
}