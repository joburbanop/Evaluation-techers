<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use App\Models\Facultad;
use App\Models\Programa;
use App\Services\ReportService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Reportes';
    protected static ?string $navigationLabel = 'Gestión de Reportes';
    protected static ?string $modelLabel = 'Reporte';
    protected static ?string $pluralModelLabel = 'Reportes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Generar Nuevo Reporte')
                    ->description('Cree reportes profesionales por facultad o programa')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('tipo_reporte')
                                    ->label('Tipo de Reporte')
                                    ->options([
                                        'facultad' => 'Reporte por Facultad',
                                        'programa' => 'Reporte por Programa',
                                    ])
                                    ->required()
                                    ->reactive()
                                    ->columnSpan(1),

                                Select::make('facultad_id')
                                    ->label('Facultad')
                                    ->options(function() {
                                        return Facultad::with('institution')
                                            ->where('nombre', 'like', 'Facultad de%')
                                            ->get()
                                            ->mapWithKeys(function($facultad) {
                                                return [$facultad->id => "{$facultad->nombre} - {$facultad->institution->name}"];
                                            });
                                    })
                                    ->searchable()
                                    ->visible(fn ($get) => $get('tipo_reporte') === 'facultad')
                                    ->columnSpan(1),

                                Select::make('programa_id')
                                    ->label('Programa')
                                    ->options(function() {
                                        return Programa::with(['facultad.institution'])
                                            ->get()
                                            ->mapWithKeys(function($programa) {
                                                return [$programa->id => "{$programa->nombre} - {$programa->facultad->nombre}"];
                                            });
                                    })
                                    ->searchable()
                                    ->visible(fn ($get) => $get('tipo_reporte') === 'programa')
                                    ->columnSpan(1),

                                DatePicker::make('date_from')
                                    ->label('Fecha Desde')
                                    ->columnSpan(1),

                                DatePicker::make('date_to')
                                    ->label('Fecha Hasta')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre del Reporte')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                BadgeColumn::make('type')
                    ->label('Tipo')
                    ->colors([
                        'primary' => 'facultad',
                        'success' => 'programa',
                        'warning' => 'institution',
                        'info' => 'global',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'facultad' => 'Facultad',
                        'programa' => 'Programa',
                        'institution' => 'Institución',
                        'global' => 'Global',
                        default => $state,
                    }),

                TextColumn::make('entity.nombre')
                    ->label('Entidad')
                    ->searchable()
                    ->sortable()
                    ->default('Sin entidad'),

                BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'generating',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Pendiente',
                        'generating' => 'Generando',
                        'completed' => 'Completado',
                        'failed' => 'Fallido',
                        default => $state,
                    }),

                TextColumn::make('file_size_formatted')
                    ->label('Tamaño')
                    ->visible(fn ($record) => $record && $record->status === 'completed'),

                TextColumn::make('generated_at')
                    ->label('Generado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('generatedBy.name')
                    ->label('Generado por')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo de Reporte')
                    ->options([
                        'facultad' => 'Facultad',
                        'programa' => 'Programa',
                        'institution' => 'Institución',
                        'global' => 'Global',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'generating' => 'Generando',
                        'completed' => 'Completado',
                        'failed' => 'Fallido',
                    ]),
            ])
            ->actions([
                Action::make('download')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (Report $record) => route('reports.download', $record->id))
                    ->openUrlInNewTab()
                    ->visible(fn (Report $record) => $record && $record->status === 'completed'),

                Action::make('regenerate')
                    ->label('Regenerar')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->action(function (Report $record) {
                        try {
                            $reportService = app(ReportService::class);
                            
                            if ($record->type === 'facultad') {
                                $facultad = Facultad::find($record->entity_id);
                                $reportService->generateFacultadReport($facultad, $record->parameters ?? []);
                            } elseif ($record->type === 'programa') {
                                $programa = Programa::find($record->entity_id);
                                $reportService->generateProgramaReport($programa, $record->parameters ?? []);
                            }

                            Notification::make()
                                ->title('Reporte regenerado exitosamente')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error al regenerar el reporte')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (Report $record) => $record && in_array($record->status, ['completed', 'failed'])),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Report $record) => 
                        $record && ($record->generated_by === Auth::id() || Auth::user()->hasRole('Administrador'))
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->hasRole('Administrador')),
                ]),
            ])
            ->emptyStateHeading('No hay reportes generados')
            ->emptyStateDescription('Genere su primer reporte usando el botón de abajo')
            ->emptyStateIcon('heroicon-o-document-chart-bar');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
        ];
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasAnyRole(['Administrador', 'Coordinador']);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        
        try {
            // Los administradores ven todos los reportes, otros usuarios solo los suyos
            if (!Auth::user()->hasRole('Administrador')) {
                $query->where('generated_by', Auth::id());
            }
        } catch (\Exception $e) {
            // Si hay error, retornar query vacío
            return $query->whereRaw('1 = 0');
        }
        
        return $query;
    }
} 