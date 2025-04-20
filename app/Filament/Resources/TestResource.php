<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestResource\Pages;
use App\Models\Test;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Stack;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Evaluaciones';
    protected static ?string $navigationLabel = 'Crear Evaluaciones';
    protected static ?string $modelLabel = 'Test';
    protected static ?string $pluralModelLabel = 'Tests';
    

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Información Básica')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Section::make('Detalles del Test')
                                ->description('Complete la información básica del test de evaluación')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nombre del Test')
                                        ->required()
                                        ->maxLength(255)
                                        ->validationMessages([
                                            'required' => '', 
                                        ])
                                        ->columnSpanFull(),
                                        
                                    Forms\Components\Select::make('category')
                                        ->label('Categoría')
                                        ->options([
                                            'competencia_pedagogica' => 'Competencia Pedagógica',
                                            'competencia_comunicativa' => 'Competencia Comunicativa',
                                            'competencia_gestion' => 'Competencia de Gestión',
                                            'competencia_tecnologica' => 'Competencia Tecnológica',
                                            'competencia_investigativa' => 'Competencia Investigativa',
                                        ])
                                        ->required()
                                        ->validationMessages([
                                            'required' => '', 
                                        ])
                                        ->native(false),
                                        
                                    Forms\Components\Textarea::make('description')
                                        ->label('Descripción')
                                        ->required()
                                        ->rows(4)
                                        ->validationMessages([
                                            'required' => '', 
                                        ])
                                        ->columnSpanFull(),
                                ])
                                ->columns(1),
                        ])
                        ->afterValidation(function ($state, $set) {
                            $errors = [];
                        
                            if (empty($state['name'])) {
                                $errors[] = '• Nombre del Test';
                            }
                            
                            if (empty($state['category'])) {
                                $errors[] = '• Categoría';
                            }
                            
                            if (empty($state['description'])) {
                                $errors[] = '• Descripción';
                            }
                            
                            if (!empty($errors)) {
                                Notification::make()
                                    ->title('Campos obligatorios faltantes')
                                    ->body("Debe completar los siguientes campos:\n\n" . implode("\n", $errors))
                                    ->danger()
                                    ->persistent()
                                    ->send();
                            }
                        }),
                        
                    Wizard\Step::make('Preguntas')
                        ->icon('heroicon-o-question-mark-circle')
                        ->schema([
                            Forms\Components\Section::make('Gestión de Preguntas')
                                ->description('Agregue las preguntas con sus opciones de respuesta')
                                ->schema([
                                    Forms\Components\Repeater::make('questions')
                                        ->relationship('questions')
                                        ->label('')
                                        ->schema([
                                            Forms\Components\Textarea::make('question')
                                                ->label('Texto de la Pregunta')
                                                ->required()
                                                ->maxLength(500)
                                                ->rows(2)
                                                ->columnSpanFull(),
                                                
                                            Forms\Components\Repeater::make('options')
                                                ->relationship('options')
                                                ->label('Opciones de Respuesta')
                                                ->addActionLabel('Agregar Opción')
                                                ->defaultItems(4)
                                                ->minItems(1)
                                                ->schema([
                                                    Forms\Components\TextInput::make('option')
                                                        ->label('Texto de la Opción')
                                                        ->required()
                                                        ->maxLength(255),
                                                        
                                                    Forms\Components\Toggle::make('is_correct')
                                                        ->label('Respuesta Correcta')
                                                        ->inline(false),
                                                ])
                                                ->grid(2)
                                                ->itemLabel(fn (array $state): ?string => $state['option'] ?? null),
                                        ])
                                        ->defaultItems(1)
                                        ->minItems(1)
                                        ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
                                        ->collapsible()
                                        ->cloneable()
                                        ->columnSpanFull(),
                                ])
                                ->columns(1),
                                                ]),
                        
                    Wizard\Step::make('Revisión')
                        ->icon('heroicon-o-check-circle')
                        ->schema([
                            Forms\Components\Section::make('Resumen Final')
                                ->description('Revise toda la información antes de guardar')
                                ->schema([
                                    Forms\Components\Placeholder::make('summary')
                                        ->label('Detalles del Test')
                                        ->content(function ($get) {
                                            $name = $get('name') ?? 'Sin nombre';
                                            $category = $get('category') ?? 'Sin categoría';
                                            $description = $get('description') ?? 'Sin descripción';
                                            $questionsCount = count($get('questions') ?? []);
                                            
                                            return new HtmlString("
                                                <div class='space-y-4'>
                                                    <div class='flex items-start'>
                                                        <span class='font-medium w-48'>Nombre del Test:</span>
                                                        <span class='flex-1 text-gray-800'>$name</span>
                                                    </div>
                                                    <div class='flex items-start'>
                                                        <span class='font-medium w-48'>Categoría:</span>
                                                        <span class='flex-1 text-gray-800'>" . ucfirst(str_replace('_', ' ', $category)) . "</span>
                                                    </div>
                                                    <div class='flex items-start'>
                                                        <span class='font-medium w-48'>Descripción:</span>
                                                        <span class='flex-1 text-gray-800'>$description</span>
                                                    </div>
                                                    <div class='flex items-start'>
                                                        <span class='font-medium w-48'>Total de Preguntas:</span>
                                                        <span class='flex-1 text-gray-800'>$questionsCount/20</span>
                                                    </div>
                                                </div>
                                            ");
                                        })
                                        ->columnSpanFull(),
                                ])
                                ->columns(1),
                        ]),
                ])
                ->persistStepInQueryString()
                ->startOnStep(1)
                ->submitAction(new HtmlString('
                    <button type="submit" class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-page-button-action bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700 focus:text-primary-100 focus:ring-white border-transparent text-white shadow focus:ring-white px-4">
                        Guardar Test
                    </button>
                '))
                ->extraAttributes(['class' => 'max-w-full px-0'])
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'max-w-full px-0']);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    Split::make([
                        TextColumn::make('name')
                            ->searchable()
                            ->sortable()
                            ->weight('bold')
                            ->description(fn (Test $record): string => $record->description),
                            
                        BadgeColumn::make('category')
                            ->sortable()
                            ->label('Categoría')
                            ->formatStateUsing(function (string $state) {
                                $categories = [
                                    'competencia_pedagogica' => 'Pedagógica',
                                    'competencia_comunicativa' => 'Comunicativa',
                                    'competencia_gestion' => 'Gestión',
                                    'competencia_tecnologica' => 'Tecnológica',
                                    'competencia_investigativa' => 'Investigativa',
                                ];
                                return $categories[$state] ?? $state;
                            })
                            ->icon('heroicon-o-tag')
                    ]),
                    
                    Stack::make([
                        TextColumn::make('questions_count')
                            ->label('Preguntas')
                            ->counts('questions')
                            ->icon('heroicon-o-question-mark-circle'),
                            
                        TextColumn::make('created_at')
                            ->label('Creado')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-calendar'),
                    ])->space(1),
                ])
                ->space(3)
                ->extraAttributes(['class' => 'p-6 shadow-md rounded-lg']),
            ])
            ->contentGrid([
                'md' => 1,
                'xl' => 2,
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoría')
                    ->options([
                        'competencia_pedagogica' => 'Competencia Pedagógica',
                        'competencia_comunicativa' => 'Competencia Comunicativa',
                        'competencia_gestion' => 'Competencia de Gestión',
                        'competencia_tecnologica' => 'Competencia Tecnológica',
                        'competencia_investigativa' => 'Competencia Investigativa',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo Test')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTests::route('/'),
            'create' => Pages\CreateTest::route('/create'),
            'edit' => Pages\EditTest::route('/{record}/edit'),
        ];
    }

    
}