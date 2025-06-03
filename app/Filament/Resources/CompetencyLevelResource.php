<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompetencyLevelResource\Pages;
use App\Models\CompetencyLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompetencyLevelResource extends Resource
{
    protected static ?string $model = CompetencyLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Niveles de Competencia';

    protected static ?string $modelLabel = 'Nivel de Competencia';

    protected static ?string $pluralModelLabel = 'Niveles de Competencia';

    protected static ?string $navigationGroup = 'Evaluaciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Nivel')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Novato, Experto, etc.')
                            ->helperText('Nombre descriptivo del nivel de competencia'),

                        Forms\Components\TextInput::make('code')
                            ->label('Código')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej: A1, B2, C1, etc.')
                            ->helperText('Código único para identificar el nivel'),

                        Forms\Components\TextInput::make('min_score')
                            ->label('Puntuación Mínima')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->helperText('Puntuación mínima para alcanzar este nivel'),

                        Forms\Components\TextInput::make('max_score')
                            ->label('Puntuación Máxima')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->helperText('Puntuación máxima para este nivel'),

                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->required()
                            ->maxLength(1000)
                            ->rows(3)
                            ->placeholder('Descripción detallada del nivel de competencia')
                            ->helperText('Describe las características y habilidades de este nivel'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('min_score')
                    ->label('Puntuación Mínima')
                    ->sortable(),

                Tables\Columns\TextColumn::make('max_score')
                    ->label('Puntuación Máxima')
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->color('primary')
                    ->after(function () {
                        CompetencyLevel::clearCache();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->color('danger')
                    ->after(function () {
                        CompetencyLevel::clearCache();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(function () {
                            CompetencyLevel::clearCache();
                        }),
                ]),
            ])
            ->defaultSort('min_score', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompetencyLevels::route('/'),
            'create' => Pages\CreateCompetencyLevel::route('/create'),
            'edit' => Pages\EditCompetencyLevel::route('/{record}/edit'),
        ];
    }
} 