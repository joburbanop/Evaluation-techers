<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestAssignmentResource\Pages;
use App\Models\TestAssignment;
use App\Models\User;
use App\Models\Test;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\IconColumn;

class TestAssignmentResource extends Resource
{
    protected static ?string $model = TestAssignment::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Evaluaciones';
    protected static ?string $navigationLabel = 'Asignación de Evaluaciones';
    protected static ?string $modelLabel = 'Asignación de Evaluación';
    protected static ?string $pluralModelLabel = 'Asignaciones de Evaluaciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información de Asignación')
                    ->description('Asigne evaluaciones a los docentes')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Docente')
                                    ->options(function() {
                                        return User::role('Docente')
                                            ->whereNotNull('name')
                                            ->where('is_active', true)
                                            ->pluck('name', 'id')
                                            ->filter()
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->columnSpan(1),
                                
                                Forms\Components\Select::make('test_id')
                                    ->label('Evaluación')
                                    ->options(function() {
                                        return Test::where('is_active', true)
                                            ->whereNotNull('name')
                                            ->pluck('name', 'id')
                                            ->filter()
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->columnSpan(1),
                            ]),
                            
                        Forms\Components\Textarea::make('instructions')
                            ->label('Instrucciones adicionales')
                            ->columnSpanFull()
                            ->maxLength(500),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Docente')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ?? 'N/A'),

                TextColumn::make('test.name')
                    ->label('Evaluación')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ?? 'N/A'),
                    
                BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'expired',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending' => 'Pendiente',
                        'completed' => 'Completado',
                        'expired' => 'Expirado',
                        default => 'Desconocido'
                    }),
                    
                IconColumn::make('is_completed')
                    ->label('Completado')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('test_id')
                    ->label('Evaluación')
                    ->options(function() {
                        return Test::where('is_active', true)
                            ->whereNotNull('name')
                            ->pluck('name', 'id')
                            ->filter()
                            ->toArray();
                    })
                    ->searchable(),
                    
                SelectFilter::make('user_id')
                    ->label('Docente')
                    ->options(function() {
                        return User::role('Docente')
                            ->whereNotNull('name')
                            ->where('is_active', true)
                            ->pluck('name', 'id')
                            ->filter()
                            ->toArray();
                    })
                    ->searchable(),
                    
                Tables\Filters\Filter::make('pending')
                    ->label('Pendientes')
                    ->query(fn ($query) => $query->where('status', 'pending')),
                    
                Tables\Filters\Filter::make('completed')
                    ->label('Completadas')
                    ->query(fn ($query) => $query->where('status', 'completed')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No hay evaluaciones asignadas')
            ->emptyStateDescription('Asigne una nueva evaluación haciendo clic en el botón de abajo')
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Asignar nueva evaluación')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestAssignments::route('/'),
            'create' => Pages\CreateTestAssignment::route('/create'),
            'edit' => Pages\EditTestAssignment::route('/{record}/edit'),
        ];
    }
}
