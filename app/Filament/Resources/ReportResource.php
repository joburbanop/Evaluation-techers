<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use App\Models\Facultad;
use App\Models\Programa;
use App\Models\Institution;
use App\Models\User;
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
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Actions;
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
    protected static ?string $modelPolicy = \App\Policies\ReportPolicy::class;

    public static function canViewAny(): bool
    {
        return Auth::user() && Auth::user()->hasAnyRole(['Administrador', 'Coordinador']);
    }

    public static function canCreate(): bool
    {
        return Auth::user() && Auth::user()->hasAnyRole(['Administrador', 'Coordinador']);
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return Auth::user() && Auth::user()->hasRole('Administrador');
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        
        // Los administradores pueden eliminar cualquier reporte
        if ($user->hasRole('Administrador')) {
            return true;
        }
        
        // Los coordinadores solo pueden eliminar reportes que ellos generaron
        if ($user->hasRole('Coordinador')) {
            return $record->generated_by === $user->id;
        }
        
        return false;
    }

    public static function canView(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return Auth::user() && Auth::user()->hasAnyRole(['Administrador', 'Coordinador']);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user() && Auth::user()->hasAnyRole(['Administrador', 'Coordinador']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Configurar Reporte')
                    ->description('Seleccione los parámetros para generar el reporte')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('tipo_reporte')
                                    ->label('Tipo de Reporte')
                                    ->placeholder('Seleccione un tipo de reporte')
                                    ->options([
                                        'universidad' => 'Reporte por Universidad',
                                        'facultad' => 'Reporte por Facultad',
                                        'programa' => 'Reporte por Programa',
                                        'profesor' => 'Reporte por Profesor',
                                        'profesores_completados' => 'Reporte de Participación en Evaluación de Competencias',
                                    ])
                                    ->required()
                                    ->reactive()
                                                ->afterStateUpdated(function ($state) {
                // El tipo de reporte se actualizó
            })
                                    ->columnSpan(1),

                                Select::make('universidad_id')
                                    ->label('Universidad')
                                    ->options(function() {
                                        $user = Auth::user();
                                        
                                        // Si es administrador, puede ver todas las universidades
                                        if ($user->hasRole('Administrador')) {
                                            return Institution::where('academic_character', 'Universidad')
                                                ->get()
                                                ->mapWithKeys(function($institution) {
                                                    return [$institution->id => $institution->name];
                                                });
                                        }
                                        
                                        // Si es coordinador, solo puede ver su institución
                                        if ($user->hasRole('Coordinador') && $user->institution_id) {
                                            $institution = Institution::find($user->institution_id);
                                            if ($institution) {
                                                return [$institution->id => $institution->name];
                                            }
                                        }
                                        
                                        return [];
                                    })
                                    ->searchable()
                                    ->visible(fn ($get) => $get('tipo_reporte') === 'universidad')
                                                ->afterStateUpdated(function ($state) {
                // Universidad seleccionada
            })
                                    ->columnSpan(1),

                                Select::make('facultad_id')
                                    ->label('Facultad')
                                    ->options(function() {
                                        $user = Auth::user();
                                        
                                        // Si es administrador, puede ver todas las facultades
                                        if ($user->hasRole('Administrador')) {
                                            return Facultad::with('institution')
                                                ->where('nombre', 'like', 'Facultad de%')
                                                ->get()
                                                ->mapWithKeys(function($facultad) {
                                                    return [$facultad->id => "{$facultad->nombre} - {$facultad->institution->name}"];
                                                });
                                        }
                                        
                                        // Si es coordinador, solo puede ver su facultad
                                        if ($user->hasRole('Coordinador') && $user->facultad_id) {
                                            $facultad = Facultad::with('institution')->find($user->facultad_id);
                                            if ($facultad) {
                                                return [$facultad->id => "{$facultad->nombre} - {$facultad->institution->name}"];
                                            }
                                        }
                                        
                                        return [];
                                    })
                                    ->searchable()
                                    ->visible(fn ($get) => $get('tipo_reporte') === 'facultad')
                                                ->afterStateUpdated(function ($state) {
                // Facultad seleccionada
            })
                                    ->columnSpan(1),

                                Select::make('programa_id')
                                    ->label('Programa')
                                    ->options(function() {
                                        $user = Auth::user();
                                        
                                        // Si es administrador, puede ver todos los programas
                                        if ($user->hasRole('Administrador')) {
                                            return Programa::with(['facultad.institution'])
                                                ->get()
                                                ->mapWithKeys(function($programa) {
                                                    return [$programa->id => "{$programa->nombre} - {$programa->facultad->nombre}"];
                                                });
                                        }
                                        
                                        // Si es coordinador, puede ver todos los programas de su facultad
                                        if ($user->hasRole('Coordinador') && $user->facultad_id) {
                                            return Programa::with(['facultad.institution'])
                                                ->where('facultad_id', $user->facultad_id)
                                                ->get()
                                                ->mapWithKeys(function($programa) {
                                                    return [$programa->id => "{$programa->nombre} - {$programa->facultad->nombre}"];
                                                });
                                        }
                                        
                                        return [];
                                    })
                                    ->searchable()
                                    ->visible(fn ($get) => $get('tipo_reporte') === 'programa')
                                    ->columnSpan(1),

                                Select::make('profesor_id')
                                    ->label('Profesor')
                                    ->options(function() {
                                        $user = Auth::user();
                                        
                                        // Si es administrador, puede ver todos los docentes
                                        if ($user->hasRole('Administrador')) {
                                            return User::whereHas('roles', function($q) {
                                                    $q->where('name', 'Docente');
                                                })
                                                ->with(['institution', 'facultad', 'programa'])
                                                ->get()
                                                ->mapWithKeys(function($user) {
                                                    $institution = $user->institution ? $user->institution->name : 'Sin institución';
                                                    return [$user->id => "{$user->name} {$user->apellido1} - {$institution}"];
                                                });
                                        }
                                        
                                        // Si es coordinador, puede ver docentes de su institución y facultad
                                        if ($user->hasRole('Coordinador')) {
                                            $query = User::whereHas('roles', function($q) {
                                                $q->where('name', 'Docente');
                                            })->with(['institution', 'facultad', 'programa']);
                                            
                                            // Filtrar por institución si tiene
                                            if ($user->institution_id) {
                                                $query->where('institution_id', $user->institution_id);
                                            }
                                            
                                            // Filtrar por facultad si tiene (todos los docentes de su facultad)
                                            if ($user->facultad_id) {
                                                $query->where('facultad_id', $user->facultad_id);
                                            }
                                            
                                            return $query->get()->mapWithKeys(function($user) {
                                                $institution = $user->institution ? $user->institution->name : 'Sin institución';
                                                return [$user->id => "{$user->name} {$user->apellido1} - {$institution}"];
                                            });
                                        }
                                        
                                        return [];
                                    })
                                    ->searchable()
                                    ->visible(fn ($get) => $get('tipo_reporte') === 'profesor')
                                    ->columnSpan(1),

                                Select::make('filtro_profesores')
                                    ->label('Filtrar por')
                                    ->options([
                                        'todos' => 'Todos los profesores',
                                        'completados' => 'Solo completados',
                                        'pendientes' => 'Solo pendientes',
                                    ])
                                    ->default('todos')
                                    ->visible(fn ($get) => $get('tipo_reporte') === 'profesores_completados')
                                    ->columnSpan(1),

                                DatePicker::make('date_from')
                                    ->label('Fecha Desde')
                                    ->maxDate(now())
                                    ->columnSpan(1)
                                    ->live()
                                    ->helperText('Seleccione la fecha inicial del período')
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        // Limpiar fecha hasta cuando se cambia fecha desde
                                        if ($state) {
                                            $set('date_to', null);
                                        }
                                    }),

                                DatePicker::make('date_to')
                                    ->label('Fecha Hasta')
                                    ->maxDate(now())
                                    ->minDate(function ($get) {
                                        $dateFrom = $get('date_from');
                                        return $dateFrom ? $dateFrom : null;
                                    })
                                    ->disabled(function ($get) {
                                        return !$get('date_from');
                                    })
                                    ->helperText('Seleccione la fecha final del período (debe ser posterior a la fecha inicial)')
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(false),
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
                        'danger' => 'universidad',
                        'primary' => 'facultad',
                        'success' => 'programa',
                        'warning' => 'profesor',
                        'info' => 'profesores_completados',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'universidad' => 'Universidad',
                        'facultad' => 'Facultad',
                        'programa' => 'Programa',
                        'profesor' => 'Profesor',
                        'profesores_completados' => 'Participación en Evaluación',
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
                        'universidad' => 'Universidad',
                        'facultad' => 'Facultad',
                        'programa' => 'Programa',
                        'profesor' => 'Profesor',
                        'profesores_completados' => 'Participación en Evaluación',
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
                Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading('Vista Previa del Reporte')
                    ->visible(fn (Report $record) => Auth::user()->hasAnyRole(['Administrador', 'Coordinador']))
                    ->modalContent(function (Report $record) {
                        try {
                            $reportService = app(ReportService::class);
                            $parameters = $record->parameters ?? [];
                            
                            // Agregar el ID de la entidad a los parámetros si no existe
                            if ($record->entity_id) {
                                switch ($record->type) {
                                    case 'universidad':
                                        if (!isset($parameters['institution_id'])) {
                                            $parameters['institution_id'] = $record->entity_id;
                                        }
                                        break;
                                    case 'facultad':
                                        if (!isset($parameters['facultad_id'])) {
                                            $parameters['facultad_id'] = $record->entity_id;
                                        }
                                        break;
                                    case 'programa':
                                        if (!isset($parameters['programa_id'])) {
                                            $parameters['programa_id'] = $record->entity_id;
                                        }
                                        break;
                                    case 'profesor':
                                        if (!isset($parameters['profesor_id'])) {
                                            $parameters['profesor_id'] = $record->entity_id;
                                        }
                                        break;
                                }
                            }
                            
                            // Agregar información de debug
                            $debugInfo = [
                                'record_type' => $record->type,
                                'record_entity_id' => $record->entity_id,
                                'parameters_original' => $record->parameters ?? [],
                                'parameters_updated' => $parameters,
                                'record_data' => $record->toArray()
                            ];
                            
                            // Obtener datos de previsualización basados en el tipo de reporte
                            $previewData = $reportService->getPreviewData($record->type, $parameters);
                            
                            // Obtener el nombre de la entidad
                            $entityName = null;
                            if ($record->entity_id) {
                                switch ($record->type) {
                                    case 'universidad':
                                        $entity = Institution::find($record->entity_id);
                                        $entityName = $entity ? $entity->name : null;
                                        break;
                                    case 'facultad':
                                        $entity = Facultad::find($record->entity_id);
                                        $entityName = $entity ? $entity->nombre : null;
                                        break;
                                    case 'programa':
                                        $entity = Programa::find($record->entity_id);
                                        $entityName = $entity ? $entity->nombre : null;
                                        break;
                                    case 'profesor':
                                        $entity = User::find($record->entity_id);
                                        $entityName = $entity ? $entity->name : null;
                                        break;
                                    case 'profesores_completados':
                                        $entityName = 'Reporte de Participación en Evaluación de Competencias';
                                        break;
                                }
                            }
                            
                            // Solo agregar debug info si no hay datos de previsualización
                            if (!$previewData || empty($previewData)) {
                                $debugInfo['preview_data'] = $previewData;
                                $previewData['debug'] = json_encode($debugInfo, JSON_PRETTY_PRINT);
                            }
                            
                            return view('filament.modals.report-preview', [
                                'previewData' => $previewData,
                                'tipo_reporte' => $record->type,
                                'entityName' => $entityName,
                                'data' => $parameters,
                                'error' => null,
                                'isViewingExistingReport' => true
                            ]);
                        } catch (\Exception $e) {
                            return view('filament.modals.report-preview', [
                                'previewData' => null,
                                'tipo_reporte' => $record->type,
                                'entityName' => null,
                                'data' => [],
                                'error' => 'Error al cargar la vista previa: ' . $e->getMessage(),
                                'isViewingExistingReport' => true
                            ]);
                        }
                    })
                    ->modalWidth('7xl')
                    ->visible(fn (Report $record) => Auth::user()->hasAnyRole(['Administrador', 'Coordinador']) && $record),

                Action::make('download')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (Report $record) => route('reports.download', $record->id))
                    ->openUrlInNewTab()
                    ->visible(fn (Report $record) => Auth::user()->hasAnyRole(['Administrador', 'Coordinador']) && $record && $record->status === 'completed'),

                Action::make('regenerate')
                    ->label('Regenerar')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (Report $record) => Auth::user()->hasRole('Administrador'))
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
                    ->visible(fn (Report $record) => Auth::user()->hasRole('Administrador') && $record && in_array($record->status, ['completed', 'failed'])),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Report $record) => Auth::user()->hasRole('Administrador') || (Auth::user()->hasRole('Coordinador') && $record->generated_by === Auth::user()->id)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->hasAnyRole(['Administrador', 'Coordinador'])),
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        
        // Los administradores pueden ver todos los reportes
        if (Auth::user()->hasRole('Administrador')) {
            return $query;
        }
        
        // Los coordinadores solo pueden ver reportes de su institución/facultad/programa
        if (Auth::user()->hasRole('Coordinador')) {
            $user = Auth::user();
            
            return $query->where(function ($q) use ($user) {
                // Reportes de su institución
                if ($user->institution_id) {
                    $q->orWhere(function ($subQ) use ($user) {
                        $subQ->where('entity_type', Institution::class)
                             ->where('entity_id', $user->institution_id);
                    });
                }
                
                // Reportes de su facultad
                if ($user->facultad_id) {
                    $q->orWhere(function ($subQ) use ($user) {
                        $subQ->where('entity_type', Facultad::class)
                             ->where('entity_id', $user->facultad_id);
                    });
                }
                
                // Reportes de todos los programas de su facultad
                if ($user->facultad_id) {
                    $programaIds = Programa::where('facultad_id', $user->facultad_id)->pluck('id');
                    if ($programaIds->count() > 0) {
                        $q->orWhere(function ($subQ) use ($programaIds) {
                            $subQ->where('entity_type', Programa::class)
                                 ->whereIn('entity_id', $programaIds);
                        });
                    }
                }
                
                // Reportes que él mismo generó
                $q->orWhere('generated_by', $user->id);
            });
        }
        
        // Otros roles no pueden ver ningún reporte
        return $query->whereRaw('1 = 0');
    }


} 