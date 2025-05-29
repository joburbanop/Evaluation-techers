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
    protected static ?string $navigationLabel = 'Gestion Evaluaciones';
    protected static ?string $modelLabel = 'Test';
    protected static ?string $pluralModelLabel = 'Tests';
    protected static ?string $modelPolicy = \App\Policies\TestPolicy::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Preguntas')
                        ->icon('heroicon-o-question-mark-circle')
                        ->schema([
                            Forms\Components\Section::make('Gestión de Preguntas')
                                ->description('Agregue las preguntas con sus opciones de respuesta')
                                ->schema([
                                    Forms\Components\Placeholder::make('instrucciones')
                                        ->content('Cada test puede contener múltiples preguntas. Para cada pregunta, agregue opciones de respuesta y marque la respuesta correcta.')
                                        ->extraAttributes(['class' => 'text-sm text-primary-600 mb-4 p-3 bg-primary-50 rounded-lg border border-primary-100'])
                                        ->columnSpanFull(),

                                    Forms\Components\Repeater::make('questions')
                                        ->relationship('questions')
                                        ->label('')
                                        ->schema([
                                            Forms\Components\Section::make()
                                                ->extraAttributes(['class' => 'bg-white rounded-xl shadow-sm border-2 border-gray-200 hover:border-primary-300 transition-colors duration-200'])
                                                ->schema([
                                                    Forms\Components\TextInput::make('factor_digcomedu')
                                                        ->label('Factor DigComEdu')
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->columnSpanFull()
                                                        ->extraAttributes(['class' => 'text-lg font-medium border-2 border-primary-200 focus:border-primary-500 rounded-lg shadow-sm'])
                                                        ->prefixIcon('heroicon-o-academic-cap')
                                                        ->prefixIconColor('primary'),

                                                    Forms\Components\Select::make('area')
                                                        ->label('Área')
                                                        ->required()
                                                        ->options(fn () => \App\Models\Category::pluck('name', 'name'))
                                                        ->searchable()
                                                        ->preload()
                                                        ->createOptionForm([
                                                            Forms\Components\TextInput::make('name')
                                                                ->label('Nombre del Área')
                                                                ->required()
                                                                ->maxLength(255)
                                                                ->unique('categories', 'name'),
                                                            Forms\Components\Textarea::make('description')
                                                                ->label('Descripción')
                                                                ->maxLength(65535)
                                                                ->columnSpanFull(),
                                                            Forms\Components\Toggle::make('is_active')
                                                                ->label('Activo')
                                                                ->default(true)
                                                                ->required(),
                                                        ])
                                                        ->createOptionUsing(function (array $data) {
                                                            return \App\Models\Category::create($data)->name;
                                                        })
                                                        ->createOptionAction(
                                                            fn (Forms\Components\Actions\Action $action) => $action
                                                                ->modalHeading('Crear Nueva Área')
                                                                ->modalSubmitActionLabel('Crear Área')
                                                                ->modalWidth('md')
                                                        )
                                                        ->columnSpanFull()
                                                        ->extraAttributes(['class' => 'text-lg font-medium border-2 border-primary-200 focus:border-primary-500 rounded-lg shadow-sm'])
                                                        ->prefixIcon('heroicon-o-bookmark')
                                                        ->prefixIconColor('primary'),

                                                    Forms\Components\Textarea::make('pregunta')
                                                        ->label('Pregunta')
                                                        ->helperText('Escriba la pregunta de manera clara y concisa')
                                                        ->required()
                                                        ->maxLength(500)
                                                        ->rows(4)
                                                        ->extraAttributes(['class' => 'text-lg font-medium border-2 border-primary-200 focus:border-primary-500 rounded-lg shadow-sm p-4 bg-white'])
                                                        ->columnSpanFull()
                                                        ->placeholder('Escriba aquí su pregunta...'),

                                                    Forms\Components\Section::make('Opciones de Respuesta')
                                                        ->description('Agregue las opciones y seleccione la respuesta correcta')
                                                        ->collapsible(false)
                                                        ->extraAttributes(['class' => 'bg-gray-50 rounded-lg mt-4 p-6 border-2 border-primary-100 shadow-sm'])
                                                        ->schema([
                                                            Forms\Components\Repeater::make('options')
                                                                ->relationship('options')
                                                                ->label('')
                                                                ->addActionLabel('Agregar Opción')
                                                                ->defaultItems(4)
                                                                ->minItems(1)
                                                                ->schema([
                                                                    Forms\Components\Grid::make(3)
                                                                        ->schema([
                                                                            Forms\Components\TextInput::make('option')
                                                                                ->label('Opción de Respuesta')
                                                                                ->required()
                                                                                ->maxLength(255)
                                                                                ->columnSpan(2)
                                                                                ->extraAttributes(['class' => 'text-base font-medium border-2 border-gray-200 focus:border-primary-500 rounded-lg shadow-sm'])
                                                                                ->prefixIcon('heroicon-o-chat-bubble-left-right'),

                                                                            Forms\Components\Toggle::make('is_correct')
                                                                                ->label('Correcta')
                                                                                ->onIcon('heroicon-o-check-circle')
                                                                                ->offIcon('heroicon-o-x-circle')
                                                                                ->onColor('success')
                                                                                ->offColor('danger')
                                                                                ->columnSpan(1)
                                                                                ->inline(true)
                                                                                ->extraAttributes(['class' => 'text-base']),
                                                                        ]),

                                                                        Forms\Components\TextInput::make('score')
                                                                            ->label('Valor de la Pregunta')
                                                                            ->numeric()
                                                                            ->minValue(0)
                                                                            ->maxValue(100)
                                                                            ->default(1)
                                                                            ->required()
                                                                            ->columnSpanFull()
                                                                            ->extraAttributes(['class' => 'text-base font-medium border-2 border-gray-200 focus:border-primary-500 rounded-lg shadow-sm mt-3'])
                                                                            ->prefixIcon('heroicon-o-star')
                                                                            ->prefixIconColor('primary')
                                                                            ->suffix('puntos')
                                                                            ->rules([
                                                                                'required',
                                                                                'numeric',
                                                                                'min:0',
                                                                                'max:100'
                                                                            ])
                                                                            ->validationMessages([
                                                                                'min' => 'El valor mínimo debe ser 0 puntos',
                                                                                'max' => 'El valor máximo debe ser 100 puntos',
                                                                                'numeric' => 'El valor debe ser un número',
                                                                                'required' => 'Este campo es obligatorio'
                                                                            ])
                                                                ])
                                                                ->itemLabel(fn (array $state): ?string =>
                                                                    (!empty($state['option']) ?
                                                                    ($state['is_correct'] ? '✓ ' : '') . mb_substr((string)$state['option'], 0, 40) . (mb_strlen((string)$state['option']) > 40 ? '...' : '')
                                                                    : 'Nueva opción'))
                                                                ->extraAttributes(['class' => 'space-y-4']),
                                                        ])
                                                        ->columnSpanFull(),
                                                ]),
                                        ])
                                        ->defaultItems(1)
                                        ->minItems(1)
                                        ->reorderable(true)
                                        ->reorderableWithDragAndDrop(true)
                                        ->deleteAction(
                                            fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                                        )
                                        ->itemLabel(fn (array $state): ?string =>
                                            (!empty($state['pregunta']) ?
                                            'Pregunta: ' . mb_substr(strip_tags((string)$state['pregunta']), 0, 50) . (mb_strlen(strip_tags((string)$state['pregunta'])) > 50 ? '...' : '')
                                            : 'Nueva pregunta'))
                                        ->collapsed(false)
                                        ->collapsible()
                                        ->cloneable()
                                        ->extraAttributes(['class' => 'space-y-6'])
                                        ->columnSpanFull(),
                                ])
                                ->columns(1)
                                ->extraAttributes(['class' => 'border-2 border-primary-100 rounded-xl p-6 bg-white shadow-sm']),
                        ]),

                    Wizard\Step::make('Revisión')
                        ->icon('heroicon-o-check-circle')
                        ->schema([
                            Forms\Components\Section::make('Resumen Final')
                                ->description('Revise toda la información antes de guardar')
                                ->extraAttributes(['class' => 'bg-white rounded-xl shadow-md border-2 border-primary-200'])
                                ->schema([
                                    Forms\Components\Placeholder::make('review_header')
                                        ->content(function ($get) {
                                            $category = $get('category_id');
                                            $categoryModel = \App\Models\Category::find($category);
                                            $categoryName = $categoryModel ? $categoryModel->name : 'Sin categoría';

                                            $name = $get('name') ?? 'Sin nombre';
                                            $questions = $get('questions') ?? [];
                                            $questionsCount = is_countable($questions) ? count($questions) : 0;

                                            return new HtmlString("
                                                <div class='text-center mb-6'>
                                                    <h2 class='text-xl font-bold text-primary-600 mb-1'>$name</h2>
                                                    <span class='inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800 border border-primary-200'>
                                                        <svg class='w-4 h-4 mr-1.5' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                                                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'></path>
                                                        </svg>
                                                        $categoryName

                                                    </span>
                                                </div>

                                                <div class='flex justify-between items-center px-4 py-3 bg-gray-50 rounded-lg mb-5 border border-gray-200'>
                                                    <div class='flex items-center'>
                                                        <svg class='w-5 h-5 mr-2 text-primary-500' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                                                            <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path>
                                                        </svg>
                                                        <span class='text-lg font-medium'>Total de Preguntas</span>
                                                    </div>
                                                    <span class='text-xl font-bold " . ($questionsCount == 0 ? 'text-red-600' : ($questionsCount < 5 ? 'text-amber-600' : 'text-green-600')) . "'>
                                                        " . (int)$questionsCount . "
                                                    </span>
                                                </div>
                                            ");
                                        })
                                        ->columnSpanFull(),

                                    Forms\Components\Section::make('Descripción')
                                        ->collapsed(false)
                                        ->collapsible()
                                        ->extraAttributes(['class' => 'bg-white rounded-lg border border-gray-200 p-4'])
                                        ->schema([
                                            Forms\Components\Placeholder::make('description_content')
                                                ->content(function ($get) {
                                                    $description = $get('description') ?? 'Sin descripción';
                                                    return new HtmlString("
                                                        <div class='prose max-w-full'>
                                                            <p class='text-gray-700'>$description</p>
                                                        </div>
                                                    ");
                                                }),
                                        ])
                                        ->columnSpanFull(),

                                    Forms\Components\Section::make('Preguntas y Respuestas')
                                        ->collapsed(false)
                                        ->collapsible()
                                        ->extraAttributes(['class' => 'bg-white rounded-lg border border-gray-200 p-4'])
                                        ->schema([
                                            Forms\Components\Placeholder::make('questions_preview')
                                                ->content(function ($get) {
                                                    $questions = $get('questions') ?? [];

                                                    if (empty($questions)) {
                                                        return new HtmlString("
                                                            <div class='text-center py-4'>
                                                                <p class='text-gray-500'>No hay preguntas agregadas todavía.</p>
                                                            </div>
                                                        ");
                                                    }

                                                    $output = "<div class='space-y-4'>";

                                                    foreach ($questions as $index => $question) {
                                                        $questionNumber = (int)$index + 1;
                                                        $questionText = $question['pregunta'] ?? 'Sin texto';

                                                        $output .= "
                                                            <div class='bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-primary-300 transition-colors duration-200'>
                                                                <div class='flex items-start mb-3'>
                                                                    <span class='flex-shrink-0 flex items-center justify-center h-6 w-6 rounded-full bg-primary-100 text-primary-800 font-bold text-sm mr-3 border border-primary-200'>$questionNumber</span>
                                                                    <p class='font-medium text-gray-900'>$questionText</p>
                                                                </div>
                                                        ";

                                                        $options = isset($question['options']) && is_array($question['options']) ? array_values($question['options']) : [];
                                                        if (!empty($options)) {
                                                            $output .= "<div class='ml-9 space-y-2'>";

                                                            foreach ($options as $optionIndex => $option) {
                                                                $optionText = $option['option'] ?? 'Sin texto';
                                                                $isCorrect = !empty($option['is_correct']);
                                                                $optionLetter = chr(65 + (int)$optionIndex);

                                                                $badgeClass = $isCorrect
                                                                    ? 'bg-green-100 text-green-800 border-green-200'
                                                                    : 'bg-gray-100 text-gray-800 border-gray-200';

                                                                $scoreValue = $option['score'] ?? 1;

                                                                $output .= "
                                                                    <div class='flex items-center group'>
                                                                        <span class='flex-shrink-0 flex items-center justify-center h-5 w-5 rounded-full bg-gray-200 text-gray-700 text-xs mr-2 border border-gray-300'>$optionLetter</span>
                                                                        <span class='flex-1'>$optionText</span>
                                                                        " . ($isCorrect ? "<span class='ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium $badgeClass border'>Correcta</span>" : "") . "
                                                                        <span class='ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-800 border border-blue-100'>
                                                                            <svg class='w-3 h-3 mr-1' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                                                                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'></path>
                                                                            </svg>
                                                                            $scoreValue pts
                                                                        </span>
                                                                    </div>
                                                                ";
                                                            }

                                                            $output .= "</div>";
                                                        }

                                                        $output .= "</div>";
                                                    }

                                                    $output .= "</div>";

                                                    return new HtmlString($output);
                                                }),
                                        ])
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
                            ->size('lg')
                            ->color('primary')
                            ->description(fn (Test $record): string =>
                                $record->description ? 
                                (mb_strlen($record->description) > 120 ?
                                mb_substr($record->description, 0, 120) . '...' :
                                $record->description) : 'Sin descripción')
                            ->extraAttributes(['class' => 'max-w-md']),

                        BadgeColumn::make('category.name')
                            ->label('Categoría')
                            ->color('primary')
                    ]),

                    Split::make([
                        TextColumn::make('questions_count')
                            ->label('Preguntas')
                            ->counts('questions')
                            ->color('success')
                            ->icon('heroicon-o-question-mark-circle')
                            ->extraAttributes(['class' => 'font-medium']),

                        TextColumn::make('created_at')
                            ->label('Creado')
                            ->dateTime('d/m/Y H:i')
                            ->color('gray')
                            ->icon('heroicon-o-calendar')
                            ->extraAttributes(['class' => 'text-right']),
                    ])->extraAttributes(['class' => 'gap-4']),
                ])
                ->space(3)
                ->extraAttributes(['class' => 'p-6 shadow-sm border-2 border-gray-200 bg-white rounded-xl hover:shadow-md transition duration-200']),
            ])
            ->contentGrid([
                'md' => 1,
                'xl' => 2,
            ])
            ->recordClasses(fn () => 'transition-all duration-300')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoría')
                    ->options([
                        'competencia_pedagogica' => 'Competencia Pedagógica',
                        'competencia_comunicativa' => 'Competencia Comunicativa',
                        'competencia_gestion' => 'Competencia de Gestión',
                        'competencia_tecnologica' => 'Competencia Tecnológica',
                        'competencia_investigativa' => 'Competencia Investigativa',
                    ])
                    ->indicator('Categoría'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->color('primary'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->color('danger'),
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
