<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestResource\Pages;
use App\Models\Test;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class TestResource extends Resource
{
    protected static ?string $model = Test::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Campos de test
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required(),

                Forms\Components\Select::make('category')
                    ->label('Categoría')
                    ->options([
                        'competencia_pedagogica' => 'Competencia Pedagógica',
                        'competencia_comunicativa' => 'Competencia Comunicativa',
                        'competencia_gestion' => 'Competencia de Gestión',
                        'competencia_tecnologica' => 'Competencia Tecnológica',
                        'competencia_investigativa' => 'Competencia Investigativa',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->required(),

                // Repeater principal para las preguntas
                Forms\Components\Repeater::make('questions')
                    ->relationship('questions') // Esto apunta a Test::questions()
                    ->label('Preguntas')
                    ->schema([
                        // Campo de la pregunta
                        Forms\Components\TextInput::make('question')
                            ->label('Pregunta')
                            ->required(),

                        // Repeater anidado para las opciones
                        Forms\Components\Repeater::make('options')
                            ->relationship('options') // Esto apunta a Question::options()
                            ->label('Opciones de Respuesta')
                            ->schema([
                                Forms\Components\TextInput::make('option')
                                    ->label('Opción')
                                    ->required(),

                                Forms\Components\Toggle::make('is_correct')
                                    ->label('Respuesta Correcta')
                                    ->default(false),
                            ])
                            ->minItems(4)
                            ->maxItems(4)
                            ->required(),
                    ])
                    ->minItems(20)
                    ->maxItems(20)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Columnas que quieras mostrar en el listado
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('category'),
                Tables\Columns\TextColumn::make('description'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTests::route('/'),
            'create' => Pages\CreateTest::route('/create'),
            'edit'   => Pages\EditTest::route('/{record}/edit'),
        ];
    }
}