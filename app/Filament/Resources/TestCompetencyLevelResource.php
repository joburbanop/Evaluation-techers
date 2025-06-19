<?php

namespace App\Filament\Resources;

use App\Models\Test;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\TestCompetencyLevelResource\Pages;

class TestCompetencyLevelResource extends Resource
{
    protected static ?string $model = Test::class;
    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $navigationGroup = 'Evaluaciones';
    protected static ?string $modelLabel = 'Test';
    protected static ?string $pluralModelLabel = 'Tests con Niveles Globales';

    // 👉 Esta función permite cargar relaciones de conteo automáticamente
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withCount([
                'competencyLevels',
                'areaCompetencyLevels',
            ])
            ->with('questions'); // Agregamos esto si necesitas mostrar número de preguntas
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ✔ Nombre del test
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre del Test')
                    ->searchable()
                    ->sortable(),

                // ✔ Descripción del test
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50) // Mostrar resumen corto
                    ->wrap(), // Para que no corte feo

             

                // (Opcional) número de preguntas
                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Preguntas')
                    ->getStateUsing(fn ($record) => $record->questions->count())
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make('verDetalles')
                ->label('Ver Detalles')
                ->icon('heroicon-o-eye'),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestCompetencyLevels::route('/'),
            'view' => Pages\ViewTestCompetencyLevels::route('/{record}/view'),
        ];
    }
    public static function canAccess(): bool
{
    return auth()->check() && auth()->user()->hasRole('Administradorsd');
}
}