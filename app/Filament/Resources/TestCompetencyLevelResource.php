<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestCompetencyLevelResource\Pages;
use App\Models\TestCompetencyLevel;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class TestCompetencyLevelResource extends Resource
{
    protected static ?string $model = TestCompetencyLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationGroup = 'Evaluaciones';
    protected static ?string $modelLabel = 'Nivel de Competencia Global';
    protected static ?string $pluralModelLabel = 'Niveles de Competencia Global';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('test_id')
                    ->label('Test')
                    ->relationship('test', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->label('Nombre del Nivel')
                    ->required(),

                Forms\Components\TextInput::make('code')
                    ->label('Código')
                    ->required(),

                Forms\Components\TextInput::make('min_score')
                    ->label('Puntaje Mínimo')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('max_score')
                    ->label('Puntaje Máximo')
                    ->numeric()
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(500),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('test.name')
                    ->label('Test')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nivel')
                    ->searchable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Código'),

                Tables\Columns\TextColumn::make('min_score')
                    ->label('Mín'),

                Tables\Columns\TextColumn::make('max_score')
                    ->label('Máx'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestCompetencyLevels::route('/'),
            'create' => Pages\CreateTestCompetencyLevel::route('/create'),
            'edit' => Pages\EditTestCompetencyLevel::route('/{record}/edit'),
        ];
    }
}