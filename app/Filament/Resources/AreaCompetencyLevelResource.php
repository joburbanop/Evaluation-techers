<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaCompetencyLevelResource\Pages;
use App\Models\AreaCompetencyLevel;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class AreaCompetencyLevelResource extends Resource
{
    protected static ?string $model = AreaCompetencyLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Evaluaciones';
    protected static ?string $modelLabel = 'Nivel por Área';
    protected static ?string $pluralModelLabel = 'Niveles por Área';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('area_id')
                    ->relationship('area', 'name')
                    ->searchable()
                    ->required()
                    ->label('Área'),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nombre del Nivel'),

                Forms\Components\TextInput::make('code')
                    ->required()
                    ->label('Código'),

                Forms\Components\TextInput::make('min_score')
                    ->numeric()
                    ->required()
                    ->label('Puntaje Mínimo'),

                Forms\Components\TextInput::make('max_score')
                    ->numeric()
                    ->required()
                    ->label('Puntaje Máximo'),

                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->label('Descripción'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('area.name')
                    ->label('Área')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Código'),

                Tables\Columns\TextColumn::make('min_score')
                    ->label('Mínimo'),

                Tables\Columns\TextColumn::make('max_score')
                    ->label('Máximo'),

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
  public static function canAccess(): bool
{
    return auth()->check() && auth()->user()->hasRole('Administradorsd');
}
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAreaCompetencyLevels::route('/'),
            'create' => Pages\CreateAreaCompetencyLevel::route('/create'),
            'edit' => Pages\EditAreaCompetencyLevel::route('/{record}/edit'),
        ];
    }

    
}