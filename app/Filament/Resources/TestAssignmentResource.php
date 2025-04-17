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
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;

class TestAssignmentResource extends Resource
{
    protected static ?string $model = TestAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Evaluaciones';
    protected static ?string $navigationLabel = 'Asignación de Evaluaciones';
    protected static ?string $modelLabel = 'Asignación de Evaluación';
    protected static ?string $pluralModelLabel = 'Asignaciones de Evaluaciones';
    protected static function generateTimeOptions(): array
    {
        $options = [];
        
        // Comienza a las 12:00 AM y termina a las 11:45 PM, con intervalos de 15 minutos
        $start = Carbon::createFromTime(0, 0);  // 12:00 AM
        $end = Carbon::createFromTime(23, 45);  // 11:45 PM
        
        while ($start <= $end) {
            $timeValue = $start->format('H:i');  // Formato 24 horas para el valor
            $timeLabel = $start->format('g:i A'); // Formato 12 horas para la etiqueta (AM/PM)
            $options[$timeValue] = $timeLabel;
            $start->addMinutes(15);  // Avanzar 15 minutos
        }
    
        return $options;
    }
    

    protected static function getDurationLabel($startTime, $endTime): string
    {
        if (!$startTime || !$endTime) return '';
    
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        
      
        if ($end <= $start) return '';
        
        $diff = $start->diff($end);
        
        
        if ($diff->h >= 1) {
            $hours = $diff->h;
            $minutes = $diff->i;
    
            
            if ($minutes > 0) {
                return "($hours h $minutes min)";
            }
            return "($hours h)";
        }
    
        
        return "($diff->i min)";
    }
    

    public static function form(Form $form): Form
    {
        $timeOptions = self::generateTimeOptions();

        return $form
            ->schema([
                Section::make('Información de Asignación')
                    ->description('Asigne evaluaciones a los docentes')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Docente')
                                    ->options(User::role('Docente')->get()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(1),
                                
                                Forms\Components\Select::make('test_id')
                                    ->label('Evaluación')
                                    ->options(Test::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                            
                            Grid::make(2)
                            ->schema([
                                // Fecha de Asignación
                                Grid::make(1)
                                    ->columnSpan(1)
                                    ->schema([
                                        Forms\Components\DatePicker::make('assigned_date')
                                            ->label('Fecha')
                                            ->displayFormat('D, d M Y') // Formato similar a Google Calendar
                                            ->native(false)
                                            ->default(now())
                                            ->minDate(now())
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                                $time = $get('assigned_time') ?? '09:00';
                                                $set('assigned_at', self::combineDateTime($state, $time));
                                                
                                                // Actualizar duración
                                                $set('duration_label', self::getDurationLabel(
                                                    $get('assigned_at'),
                                                    $get('due_at')
                                                ));
                                            }),
                                            
                                        Forms\Components\Select::make('assigned_time')
                                            ->label('Hora de inicio')
                                            ->options($timeOptions)
                                            ->searchable()
                                            ->default('09:00')
                                            ->required()
                                            ->native(false)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                                $date = $get('assigned_date') ?? now()->format('Y-m-d');
                                                $set('assigned_at', self::combineDateTime($date, $state));
                                                
                                                // Actualizar duración
                                                $set('duration_label', self::getDurationLabel(
                                                    $get('assigned_at'),
                                                    $get('due_at')
                                                ));
                                            })
                                         
                                    ]),

                                // Fecha Límite
                                Grid::make(1)
                                    ->columnSpan(1)
                                    ->schema([
                                        Forms\Components\DatePicker::make('due_date')
                                            ->label('Fecha final')
                                            ->displayFormat('D, d M Y')
                                            ->native(false)
                                            ->required()
                                            ->minDate(fn (Forms\Get $get) => $get('assigned_date') ?? now())
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                                $time = $get('due_time') ?? '17:00';
                                                $set('due_at', self::combineDateTime($state, $time));
                                                
                                                // Actualizar duración
                                                $set('duration_label', self::getDurationLabel(
                                                    $get('assigned_at'),
                                                    $get('due_at')
                                                ));
                                            }),
                                            
                                        Forms\Components\Select::make('due_time')
                                            ->label('Hora final')
                                            ->options($timeOptions)
                                            ->searchable()
                                            ->default('17:00')
                                            ->required()
                                            ->native(false)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                                $date = $get('due_date') ?? now()->format('Y-m-d');
                                                $set('due_at', self::combineDateTime($date, $state));
                                                
                                                // Actualizar duración
                                                $set('duration_label', self::getDurationLabel(
                                                    $get('assigned_at'),
                                                    $get('due_at')
                                                ));
                                            }),
                                            
                                      Forms\Components\TextInput::make('duration_label')
                                        ->label('Duración')
                                        ->disabled()
                                        ->dehydrated(false)
                                        ->columnSpanFull()
                                        ->extraAttributes(['class' => 'font-bold text-primary-600']),
                                ]),
                                   
                                // Campos ocultos para almacenar el valor completo
                                Forms\Components\Hidden::make('assigned_at')
                                    ->afterStateHydrated(function ($component, $state) {
                                        if ($state) {
                                            $dateTime = Carbon::parse($state);
                                            $container = $component->getContainer();

                                            // Verificar si los componentes existen antes de establecer sus valores
                                            $assignedDateComponent = $container->getComponent('assigned_date');
                                            $assignedTimeComponent = $container->getComponent('assigned_time');

                                            // Solo acceder a los componentes si existen
                                            if ($assignedDateComponent) {
                                                $assignedDateComponent->state($dateTime->format('Y-m-d'));
                                            }

                                            if ($assignedTimeComponent) {
                                                $assignedTimeComponent->state($dateTime->format('H:i'));
                                            }
                                        }
                                    }),

    
                                    
                                    Forms\Components\Hidden::make('due_at')
                                    ->afterStateHydrated(function ($component, $state) {
                                        if ($state) {
                                            $dateTime = Carbon::parse($state);
                                            $container = $component->getContainer();
                                
                                            // Verificar si los componentes existen antes de establecer sus valores
                                            $dueDateComponent = $container->getComponent('due_date');
                                            $dueTimeComponent = $container->getComponent('due_time');
                                
                                            // Solo acceder a los componentes si existen
                                            if ($dueDateComponent) {
                                                $dueDateComponent->state($dateTime->format('Y-m-d'));
                                            }
                                
                                            if ($dueTimeComponent) {
                                                $dueTimeComponent->state($dateTime->format('H:i'));
                                            }
                                        }
                                    }),
                                
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
    protected static function combineDateTime(string $date, string $time): string
    {
        // Limpiar los posibles espacios en blanco al principio o final de la hora
        $time = trim($time);
    
        // Formatos posibles para la hora (24 horas y 12 horas)
        $formatsToTry = ['H:i', 'h:i A', 'h:i a'];
    
        $timeObj = null;
    
        // Intentamos con varios formatos
        foreach ($formatsToTry as $format) {
            try {
                // Si el formato es correcto, se genera el objeto de hora
                $timeObj = Carbon::createFromFormat($format, $time);
                break;
            } catch (\Exception $e) {
                continue;  // Continuamos con el siguiente formato si falla
            }
        }
    
        // Si no hemos podido parsear la hora, usamos la hora actual
        if (!$timeObj) {
            \Log::error("Error al parsear la hora: " . $time);
            $timeObj = now();
        }
    
        try {
            // Creamos un objeto de fecha con la fecha proporcionada
            $dateObj = Carbon::parse($date);
            // Combinamos la fecha con la hora
            return $dateObj->setTime($timeObj->hour, $timeObj->minute)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // Si hay un error, devolvemos la hora actual
            \Log::error("Error al combinar la fecha y hora: " . $e->getMessage());
            return now()->format('Y-m-d H:i:s');  // Devolvemos la hora actual si algo falla
        }
    }
    
    
    
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                ->label('Docente')
                ->searchable()
                ->sortable()
                ->description(fn (TestAssignment $record) => $record->user->name),

                TextColumn::make('test.name')
                    ->label('Evaluación')
                    ->searchable()
                    ->sortable()
                    ->description(fn (TestAssignment $record) => $record->test->name),

                    
                TextColumn::make('assigned_at')
                    ->label('Asignado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                TextColumn::make('due_at')
                    ->label('Vence')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(fn (TestAssignment $record): string => 
                        $record->due_at < now() ? 'danger' : 'success'),
                    
                BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'overdue',
                    ])
                    ->getStateUsing(function (TestAssignment $record): string {
                        if ($record->completed_at) return 'completed';
                        if ($record->due_at < now()) return 'overdue';
                        return 'pending';
                    }),
                    
                IconColumn::make('is_completed')
                    ->label('Completado')
                    ->boolean()
                    ->getStateUsing(fn (TestAssignment $record): bool => !is_null($record->completed_at)),
            ])
            ->filters([
                SelectFilter::make('test_id')
                    ->label('Evaluación')
                    ->options(Test::all()->pluck('name', 'id'))
                    ->searchable(),
                    
                SelectFilter::make('user_id')
                    ->label('Docente')
                    ->options(User::role('Docente')->get()->pluck('name', 'id'))
                    ->searchable(),
                    
                Tables\Filters\Filter::make('overdue')
                    ->label('Vencidas')
                    ->query(fn ($query) => $query->where('due_at', '<', now())->whereNull('completed_at')),
                    
                Tables\Filters\Filter::make('pending')
                    ->label('Pendientes')
                    ->query(fn ($query) => $query->where('due_at', '>=', now())->whereNull('completed_at')),
                    
                Tables\Filters\Filter::make('completed')
                    ->label('Completadas')
                    ->query(fn ($query) => $query->whereNotNull('completed_at')),
            ])
            ->actions([
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('due_at', 'asc')
            ->emptyStateHeading('No hay evaluaciones asignadas')
            ->emptyStateDescription('Asigne una nueva evaluación haciendo clic en el botón de abajo')
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Asignar nueva evaluación')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->assigned_at && Carbon::parse($model->assigned_at) < now()) {
                throw new \Exception('La fecha de asignación no puede ser en el pasado');
            }
            
            if ($model->due_at && $model->assigned_at && 
                Carbon::parse($model->due_at) <= Carbon::parse($model->assigned_at)) {
                throw new \Exception('La fecha límite debe ser posterior a la fecha de asignación');
            }
        });
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
