# 📚 Manual de Módulos - Sistema de Evaluación de Competencias Digitales Docentes

## 📋 Índice
1. [Módulo de Usuarios](#módulo-de-usuarios)
2. [Módulo de Tests](#módulo-de-tests)
3. [Módulo de Asignaciones](#módulo-de-asignaciones)
4. [Módulo de Evaluaciones](#módulo-de-evaluaciones)
5. [Módulo de Reportes](#módulo-de-reportes)
6. [Módulo de API](#módulo-de-api)
7. [Módulo de Dashboard](#módulo-de-dashboard)
8. [Módulo de Permisos](#módulo-de-permisos)

---

## 👥 Módulo de Usuarios

### Configuración del Recurso UserResource

#### Ubicación
```
app/Filament/Resources/UserResource.php
```

#### Configuración Básica
```php
class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationLabel = 'Gestión de Usuarios';
}
```

#### Campos del Formulario
```php
public static function form(Form $form): Form
{
    return $form->schema([
        Section::make('Información Personal')
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                
                TextInput::make('document_number')
                    ->label('Número de Documento')
                    ->required(),
                
                Select::make('document_type')
                    ->label('Tipo de Documento')
                    ->options([
                        'CC' => 'Cédula de Ciudadanía',
                        'CE' => 'Cédula de Extranjería',
                        'TI' => 'Tarjeta de Identidad',
                        'PP' => 'Pasaporte'
                    ])
                    ->required(),
            ])->columns(2),
            
        Section::make('Información Académica')
            ->schema([
                Select::make('institution_id')
                    ->label('Institución')
                    ->options(Institution::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                    
                Select::make('facultad_id')
                    ->label('Facultad')
                    ->options(Facultad::pluck('nombre', 'id'))
                    ->searchable()
                    ->required(),
                    
                Select::make('programa_id')
                    ->label('Programa')
                    ->options(Programa::pluck('nombre', 'id'))
                    ->searchable()
                    ->required(),
            ])->columns(3),
            
        Section::make('Roles y Permisos')
            ->schema([
                CheckboxList::make('roles')
                    ->label('Roles')
                    ->options(Role::pluck('name', 'id'))
                    ->columns(3),
            ]),
    ]);
}
```

#### Configuración de Tabla
```php
public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')
                ->label('Nombre')
                ->searchable()
                ->sortable(),
                
            TextColumn::make('email')
                ->label('Email')
                ->searchable()
                ->sortable(),
                
            BadgeColumn::make('roles.name')
                ->label('Roles')
                ->colors(['primary', 'success', 'warning']),
                
            TextColumn::make('institution.name')
                ->label('Institución')
                ->searchable(),
                
            IconColumn::make('is_active')
                ->label('Activo')
                ->boolean(),
        ])
        ->filters([
            SelectFilter::make('roles')
                ->label('Filtrar por Rol')
                ->options(Role::pluck('name', 'id')),
                
            SelectFilter::make('institution_id')
                ->label('Filtrar por Institución')
                ->options(Institution::pluck('name', 'id')),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
```

#### Políticas de Autorización
```php
// app/Policies/UserPolicy.php
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Administrador');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasRole('Administrador');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Administrador');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasRole('Administrador');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('Administrador');
    }
}
```

---

## 📝 Módulo de Tests

### Configuración del Recurso TestResource

#### Ubicación
```
app/Filament/Resources/TestResource.php
```

#### Configuración Básica
```php
class TestResource extends Resource
{
    protected static ?string $model = Test::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Evaluaciones';
    protected static ?string $navigationLabel = 'Gestión de Tests';
}
```

#### Campos del Formulario
```php
public static function form(Form $form): Form
{
    return $form->schema([
        Section::make('Información del Test')
            ->schema([
                TextInput::make('name')
                    ->label('Nombre del Test')
                    ->required()
                    ->maxLength(255),
                    
                Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->required(),
                    
                Select::make('category_id')
                    ->label('Categoría')
                    ->options(Category::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                    
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ])->columns(2),
            
        Section::make('Configuración de Preguntas')
            ->schema([
                Repeater::make('questions')
                    ->label('Preguntas')
                    ->relationship('questions')
                    ->schema([
                        Textarea::make('question')
                            ->label('Pregunta')
                            ->required(),
                            
                        Select::make('area_id')
                            ->label('Área de Competencia')
                            ->options(Area::pluck('name', 'id'))
                            ->required(),
                            
                        Select::make('factor_id')
                            ->label('Factor')
                            ->options(Factor::pluck('name', 'id'))
                            ->required(),
                            
                        Toggle::make('is_multiple')
                            ->label('Selección Múltiple')
                            ->default(false),
                            
                        Repeater::make('options')
                            ->label('Opciones de Respuesta')
                            ->schema([
                                TextInput::make('option')
                                    ->label('Opción')
                                    ->required(),
                                    
                                TextInput::make('score')
                                    ->label('Puntuación')
                                    ->numeric()
                                    ->required(),
                                    
                                Toggle::make('is_correct')
                                    ->label('Correcta')
                                    ->default(false),
                            ])
                            ->columns(4)
                            ->minItems(2)
                            ->maxItems(5),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]),
    ]);
}
```

#### Configuración de Tabla
```php
public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')
                ->label('Nombre')
                ->searchable()
                ->sortable()
                ->weight('bold'),
                
            TextColumn::make('description')
                ->label('Descripción')
                ->limit(50)
                ->searchable(),
                
            TextColumn::make('questions_count')
                ->label('Preguntas')
                ->counts('questions')
                ->sortable(),
                
            BadgeColumn::make('category.name')
                ->label('Categoría')
                ->colors(['primary']),
                
            IconColumn::make('is_active')
                ->label('Activo')
                ->boolean(),
        ])
        ->filters([
            SelectFilter::make('category_id')
                ->label('Filtrar por Categoría')
                ->options(Category::pluck('name', 'id')),
                
            Tables\Filters\TernaryFilter::make('is_active')
                ->label('Estado'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
```

#### Acciones Personalizadas
```php
Tables\Actions\Action::make('duplicate')
    ->label('Duplicar')
    ->icon('heroicon-o-document-duplicate')
    ->color('warning')
    ->action(function (Test $record) {
        $newTest = $record->replicate();
        $newTest->name = $record->name . ' (Copia)';
        $newTest->save();
        
        // Duplicar preguntas
        foreach ($record->questions as $question) {
            $newQuestion = $question->replicate();
            $newQuestion->test_id = $newTest->id;
            $newQuestion->save();
            
            // Duplicar opciones
            foreach ($question->options as $option) {
                $newOption = $option->replicate();
                $newOption->question_id = $newQuestion->id;
                $newOption->save();
            }
        }
        
        Notification::make()
            ->title('Test duplicado exitosamente')
            ->success()
            ->send();
    })
```

---

## 📋 Módulo de Asignaciones

### Configuración del Recurso TestAssignmentResource

#### Ubicación
```
app/Filament/Resources/TestAssignmentResource.php
```

#### Configuración Básica
```php
class TestAssignmentResource extends Resource
{
    protected static ?string $model = TestAssignment::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Evaluaciones';
    protected static ?string $navigationLabel = 'Asignación de Evaluaciones';
}
```

#### Campos del Formulario
```php
public static function form(Form $form): Form
{
    return $form->schema([
        Section::make('Información de Asignación')
            ->schema([
                Select::make('user_id')
                    ->label('Docente')
                    ->options(User::role('Docente')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                    
                Select::make('test_id')
                    ->label('Evaluación')
                    ->options(Test::where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                    
                Textarea::make('instructions')
                    ->label('Instrucciones adicionales')
                    ->rows(3),
            ])->columns(2),
    ]);
}
```

#### Configuración de Tabla
```php
public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('user.name')
                ->label('Docente')
                ->searchable()
                ->sortable(),
                
            TextColumn::make('test.name')
                ->label('Evaluación')
                ->searchable()
                ->sortable(),
                
            BadgeColumn::make('status')
                ->label('Estado')
                ->colors([
                    'warning' => 'pending',
                    'info' => 'in_progress',
                    'success' => 'completed',
                    'danger' => 'expired',
                ])
                ->formatStateUsing(fn ($state) => match($state) {
                    'pending' => 'Pendiente',
                    'in_progress' => 'En progreso',
                    'completed' => 'Completado',
                    'expired' => 'Expirado',
                    default => 'Desconocido'
                }),
                
            TextColumn::make('created_at')
                ->label('Asignado')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('status')
                ->label('Estado')
                ->options([
                    'pending' => 'Pendientes',
                    'in_progress' => 'En progreso',
                    'completed' => 'Completados',
                    'expired' => 'Expirados',
                ]),
                
            SelectFilter::make('test_id')
                ->label('Evaluación')
                ->options(Test::pluck('name', 'id')),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
```

#### Acciones Personalizadas
```php
Tables\Actions\Action::make('ver_detalles')
    ->label('Ver Detalles')
    ->icon('heroicon-o-eye')
    ->modalHeading(fn ($record) => "Resultados de {$record->user->name}")
    ->modalContent(function ($record) {
        $responses = $record->responses->loadMissing('question.options', 'option');
        $areas = Area::all();
        $areaScores = $areas->map(function ($area) use ($record) {
            $areaResponses = $record->responses()
                ->whereHas('question', function ($query) use ($area) {
                    $query->where('area_id', $area->id);
                })->get();
            $totalScore = $areaResponses->sum(function ($response) {
                return $response->option->score ?? 0;
            });
            $maxPossibleScore = $areaResponses->sum(function ($response) {
                return $response->question->options->max('score');
            });
            $percentage = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;
            
            return [
                'area' => $area->name,
                'score' => $percentage,
                'totalScore' => $totalScore,
                'maxScore' => $maxPossibleScore
            ];
        });
        
        return view('filament.widgets.evaluacion-detalles', [
            'record' => $record,
            'responses' => $responses,
            'areaScores' => $areaScores
        ]);
    })
    ->modalWidth('7xl')
    ->visible(fn ($record) => $record->status === 'completed'),
```

---

## 📊 Módulo de Evaluaciones

### Configuración del Recurso RealizarTestResource

#### Ubicación
```
app/Filament/Resources/RealizarTestResource.php
```

#### Configuración Básica
```php
class RealizarTestResource extends Resource
{
    protected static ?string $model = TestAssignment::class;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationGroup = 'Evaluaciones';
    protected static ?string $navigationLabel = 'Realizar Test';
}
```

#### Configuración de Acceso
```php
public static function canViewAny(): bool
{
    return auth()->user()?->hasRole('Docente') ?? false;
}

public static function canCreate(): bool
{
    return false; // Los docentes no pueden crear asignaciones
}
```

#### Configuración de Tabla
```php
public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('test.name')
                ->label('Test')
                ->searchable()
                ->sortable()
                ->weight('bold'),
                
            TextColumn::make('avance_calculado')
                ->label('Avance')
                ->getStateUsing(function ($record) {
                    $total = $record->test?->questions?->count() ?? 0;
                    $respondidas = $record->responses()->distinct('question_id')->count();
                    $porcentaje = $record->status === 'completed'
                        ? 100
                        : ($total > 0 ? round(($respondidas / $total) * 100) : 0);
                    
                    return $porcentaje . '%';
                }),
                
            BadgeColumn::make('status')
                ->label('Estado')
                ->colors([
                    'warning' => 'pending',
                    'info' => 'in_progress',
                    'success' => 'completed',
                    'danger' => 'expired',
                ]),
        ])
        ->filters([
            SelectFilter::make('status')
                ->label('Estado')
                ->options([
                    'pending' => 'Pendientes',
                    'in_progress' => 'En progreso',
                    'completed' => 'Completados',
                    'expired' => 'Expirados',
                ]),
        ])
        ->actions([
            Tables\Actions\Action::make('responder')
                ->label(fn (TestAssignment $record) => 
                    $record?->status === 'completed' ? 'Ver Resultados' : 'Comenzar/Continuar Test')
                ->icon(fn (TestAssignment $record) => 
                    $record?->status === 'completed' ? 'heroicon-o-eye' : 'heroicon-o-play')
                ->color(fn (TestAssignment $record) => 
                    $record?->status === 'completed' ? 'success' : 'primary')
                ->button()
                ->modalWidth('5xl')
                ->form(function (TestAssignment $record) {
                    // Lógica del formulario de evaluación
                    return self::getEvaluationForm($record);
                }),
        ])
        ->bulkActions([])
        ->emptyStateHeading('No tienes tests pendientes')
        ->emptyStateDescription('Cuando te asignen nuevos tests, aparecerán aquí');
}
```

#### Formulario de Evaluación
```php
private static function getEvaluationForm(TestAssignment $record): array
{
    if ($record->status === 'completed') {
        return [
            // Mostrar resultados
            Forms\Components\Section::make('Resultados')
                ->schema([
                    // Componentes para mostrar resultados
                ]),
        ];
    }
    
    // Formulario de evaluación
    $allQuestions = $record->test->questions()
        ->with(['options', 'factor', 'area'])
        ->orderBy('order')
        ->get();
    
    $formFields = [];
    
    foreach ($allQuestions as $question) {
        $formFields[] = Forms\Components\Fieldset::make()
            ->label("Pregunta {$question->order}: {$question->question}")
            ->schema([
                $question->is_multiple 
                    ? Forms\Components\CheckboxList::make("answers.{$question->id}")
                        ->options($question->options->pluck('option', 'id'))
                        ->label('Selecciona todas las opciones que apliquen:')
                    : Forms\Components\Radio::make("answers.{$question->id}")
                        ->options($question->options->pluck('option', 'id'))
                        ->label('Selecciona una respuesta:'),
            ]);
    }
    
    return $formFields;
}
```

---

## 📈 Módulo de Reportes

### Configuración del Recurso ReportResource

#### Ubicación
```
app/Filament/Resources/ReportResource.php
```

#### Configuración Básica
```php
class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Reportes';
    protected static ?string $navigationLabel = 'Gestión de Reportes';
}
```

#### Campos del Formulario
```php
public static function form(Form $form): Form
{
    return $form->schema([
        Section::make('Configurar Reporte')
            ->schema([
                Grid::make(2)
                    ->schema([
                        Select::make('tipo_reporte')
                            ->label('Tipo de Reporte')
                            ->options([
                                'universidad' => 'Reporte por Universidad',
                                'facultad' => 'Reporte por Facultad',
                                'programa' => 'Reporte por Programa',
                                'profesor' => 'Reporte por Profesor',
                            ])
                            ->required()
                            ->reactive(),
                            
                        Select::make('universidad_id')
                            ->label('Universidad')
                            ->options(Institution::pluck('name', 'id'))
                            ->searchable()
                            ->visible(fn ($get) => $get('tipo_reporte') === 'universidad'),
                            
                        Select::make('facultad_id')
                            ->label('Facultad')
                            ->options(Facultad::pluck('nombre', 'id'))
                            ->searchable()
                            ->visible(fn ($get) => $get('tipo_reporte') === 'facultad'),
                            
                        Select::make('programa_id')
                            ->label('Programa')
                            ->options(Programa::pluck('nombre', 'id'))
                            ->searchable()
                            ->visible(fn ($get) => $get('tipo_reporte') === 'programa'),
                            
                        Select::make('profesor_id')
                            ->label('Profesor')
                            ->options(User::role('Docente')->pluck('name', 'id'))
                            ->searchable()
                            ->visible(fn ($get) => $get('tipo_reporte') === 'profesor'),
                            
                        DatePicker::make('date_from')
                            ->label('Fecha Desde'),
                            
                        DatePicker::make('date_to')
                            ->label('Fecha Hasta'),
                    ]),
            ]),
    ]);
}
```

#### Configuración de Tabla
```php
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
                ])
                ->formatStateUsing(fn ($state) => match ($state) {
                    'universidad' => 'Universidad',
                    'facultad' => 'Facultad',
                    'programa' => 'Programa',
                    'profesor' => 'Profesor',
                    default => $state,
                }),
                
            BadgeColumn::make('status')
                ->label('Estado')
                ->colors([
                    'warning' => 'pending',
                    'info' => 'generating',
                    'success' => 'completed',
                    'danger' => 'failed',
                ]),
                
            TextColumn::make('generated_at')
                ->label('Generado')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('type')
                ->label('Tipo de Reporte')
                ->options([
                    'universidad' => 'Universidad',
                    'facultad' => 'Facultad',
                    'programa' => 'Programa',
                    'profesor' => 'Profesor',
                ]),
                
            SelectFilter::make('status')
                ->label('Estado')
                ->options([
                    'pending' => 'Pendiente',
                    'generating' => 'Generando',
                    'completed' => 'Completado',
                    'failed' => 'Fallido',
                ]),
        ])
        ->actions([
            Tables\Actions\Action::make('view')
                ->label('Ver')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->modalContent(function (Report $record) {
                    // Lógica para mostrar vista previa
                    return self::getPreviewContent($record);
                })
                ->modalWidth('7xl'),
                
            Tables\Actions\Action::make('download')
                ->label('Descargar')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url(fn (Report $record) => route('reports.download', $record->id))
                ->openUrlInNewTab()
                ->visible(fn (Report $record) => $record->status === 'completed'),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
```

#### Vista Previa de Reportes
```php
private static function getPreviewContent(Report $record): View
{
    try {
        $reportService = app(ReportService::class);
        $parameters = $record->parameters ?? [];
        
        // Agregar el ID de la entidad si no existe
        if ($record->entity_id) {
            switch ($record->type) {
                case 'universidad':
                    $parameters['institution_id'] = $record->entity_id;
                    break;
                case 'facultad':
                    $parameters['facultad_id'] = $record->entity_id;
                    break;
                case 'programa':
                    $parameters['programa_id'] = $record->entity_id;
                    break;
                case 'profesor':
                    $parameters['profesor_id'] = $record->entity_id;
                    break;
            }
        }
        
        $previewData = $reportService->getPreviewData($record->type, $parameters);
        
        return view('filament.modals.report-preview', [
            'previewData' => $previewData,
            'tipo_reporte' => $record->type,
            'entityName' => $record->entity?->name,
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
}
```

---

## 🔌 Módulo de API

### Configuración de Rutas API

#### Ubicación
```
routes/api.php
```

#### Rutas de Autenticación
```php
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/auth/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
```

#### Rutas de Reportes
```php
Route::middleware('auth:sanctum')->group(function () {
    // Generar reporte
    Route::post('/reports/generate', [ReportController::class, 'generate']);
    
    // Descargar reporte
    Route::get('/reports/{id}/download', [ReportController::class, 'download']);
    
    // Listar reportes
    Route::get('/reports', [ReportController::class, 'index']);
    
    // Ver reporte específico
    Route::get('/reports/{id}', [ReportController::class, 'show']);
});
```

#### Rutas de Tests
```php
Route::middleware('auth:sanctum')->group(function () {
    // Obtener tests asignados
    Route::get('/tests/assigned', [TestController::class, 'assigned']);
    
    // Obtener test específico
    Route::get('/tests/{id}', [TestController::class, 'show']);
    
    // Enviar respuestas
    Route::post('/tests/{id}/submit', [TestController::class, 'submit']);
    
    // Guardar progreso
    Route::post('/tests/{id}/save', [TestController::class, 'save']);
});
```

### Controladores API

#### AuthController
```php
// app/Http/Controllers/Api/AuthController.php
class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user->load('roles'),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('roles', 'institution', 'facultad', 'programa')
        ]);
    }
}
```

#### ReportController
```php
// app/Http/Controllers/Api/ReportController.php
class ReportController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'tipo_reporte' => 'required|in:universidad,facultad,programa,profesor',
            'entidad_id' => 'required|integer',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        try {
            $reportService = app(ReportService::class);
            $parameters = $request->all();
            
            $report = $reportService->generateReport(
                $parameters['tipo_reporte'],
                $parameters['entidad_id'],
                $parameters
            );

            return response()->json([
                'message' => 'Reporte generado exitosamente',
                'report' => $report,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar reporte: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($id)
    {
        $report = Report::findOrFail($id);
        
        if ($report->status !== 'completed') {
            return response()->json([
                'message' => 'El reporte aún no está listo'
            ], 400);
        }

        $path = storage_path('app/' . $report->file_path);
        
        if (!file_exists($path)) {
            return response()->json([
                'message' => 'Archivo no encontrado'
            ], 404);
        }

        return response()->download($path, $report->name . '.pdf');
    }
}
```

---

## 📊 Módulo de Dashboard

### Configuración de Widgets

#### Widget de Estadísticas Generales
```php
// app/Filament/Widgets/StatsOverview.php
class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Usuarios', User::count())
                ->description('Usuarios registrados')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
                
            Stat::make('Tests Activos', Test::where('is_active', true)->count())
                ->description('Evaluaciones disponibles')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('success'),
                
            Stat::make('Evaluaciones Completadas', TestAssignment::where('status', 'completed')->count())
                ->description('Tests finalizados')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),
                
            Stat::make('Reportes Generados', Report::where('status', 'completed')->count())
                ->description('Análisis realizados')
                ->descriptionIcon('heroicon-m-document-chart-bar')
                ->color('warning'),
        ];
    }
}
```

#### Widget de Gráfico de Progreso
```php
// app/Filament/Widgets/EvaluacionesProgress.php
class EvaluacionesProgress extends BaseWidget
{
    protected static ?string $heading = 'Progreso de Evaluaciones';
    
    protected function getData(): array
    {
        $data = TestAssignment::selectRaw('
            DATE(created_at) as date,
            COUNT(*) as total,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed
        ')
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Asignadas',
                    'data' => $data->pluck('total')->toArray(),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Completadas',
                    'data' => $data->pluck('completed')->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
```

### Configuración de Dashboard por Rol

#### Dashboard de Administrador
```php
// app/Filament/Pages/Dashboard.php
class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.pages.dashboard';
    
    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
    
    protected function getFooterWidgets(): array
    {
        return [
            EvaluacionesProgress::class,
            RecentEvaluations::class,
        ];
    }
}
```

#### Dashboard de Coordinador
```php
// app/Filament/Coordinador/Pages/Dashboard.php
class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.coordinador.pages.dashboard';
    
    protected function getHeaderWidgets(): array
    {
        return [
            CoordinadorStatsOverview::class,
        ];
    }
    
    protected function getFooterWidgets(): array
    {
        return [
            FacultadProgress::class,
            RecentReports::class,
        ];
    }
}
```

#### Dashboard de Docente
```php
// app/Filament/Docente/Pages/Dashboard.php
class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.docente.pages.dashboard';
    
    protected function getHeaderWidgets(): array
    {
        return [
            DocenteStatsOverview::class,
        ];
    }
    
    protected function getFooterWidgets(): array
    {
        return [
            MyEvaluations::class,
            MyResults::class,
        ];
    }
}
```

---

## 🔐 Módulo de Permisos

### Configuración de Roles y Permisos

#### Seeder de Permisos
```php
// database/seeders/FinalPermissionsSeeder.php
class FinalPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Definir permisos
        $permissions = [
            // Permisos de administración
            ['name' => 'Gestionar usuarios', 'module' => 'administracion'],
            ['name' => 'Gestionar roles', 'module' => 'administracion'],
            ['name' => 'Gestionar permisos', 'module' => 'administracion'],
            
            // Permisos de tests
            ['name' => 'Gestionar tests', 'module' => 'evaluaciones'],
            ['name' => 'Crear test', 'module' => 'evaluaciones'],
            ['name' => 'Editar test', 'module' => 'evaluaciones'],
            ['name' => 'Eliminar test', 'module' => 'evaluaciones'],
            
            // Permisos de asignaciones
            ['name' => 'Ver asignaciones', 'module' => 'evaluaciones'],
            ['name' => 'Crear asignaciones', 'module' => 'evaluaciones'],
            ['name' => 'Editar asignaciones', 'module' => 'evaluaciones'],
            ['name' => 'Eliminar asignaciones', 'module' => 'evaluaciones'],
            
            // Permisos de realizar test
            ['name' => 'Realizar test', 'module' => 'evaluaciones'],
            ['name' => 'Ver resultados propios', 'module' => 'evaluaciones'],
            
            // Permisos de reportes
            ['name' => 'Ver reportes', 'module' => 'reportes'],
            ['name' => 'Generar reportes', 'module' => 'reportes'],
            ['name' => 'Descargar reportes', 'module' => 'reportes'],
            ['name' => 'Eliminar reportes', 'module' => 'reportes'],
        ];

        // Crear permisos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name'],
                'guard_name' => 'web'
            ], [
                'module' => $permission['module'],
                'description' => $permission['name']
            ]);
        }

        // Obtener roles
        $adminRole = Role::where('name', 'Administrador')->first();
        $coordinadorRole = Role::where('name', 'Coordinador')->first();
        $docenteRole = Role::where('name', 'Docente')->first();

        // Asignar permisos a Administrador (todos)
        $adminRole->syncPermissions(Permission::all());

        // Asignar permisos a Coordinador
        $coordinadorPermissions = [
            'Gestionar tests',
            'Crear test',
            'Editar test',
            'Eliminar test',
            'Ver asignaciones',
            'Ver reportes',
            'Generar reportes',
            'Descargar reportes',
            'Eliminar reportes',
        ];
        $coordinadorRole->syncPermissions($coordinadorPermissions);

        // Asignar permisos a Docente
        $docentePermissions = [
            'Realizar test',
            'Ver resultados propios',
        ];
        $docenteRole->syncPermissions($docentePermissions);
    }
}
```

#### Middleware de Verificación
```php
// app/Http/Middleware/CheckRole.php
class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasAnyRole($roles)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
```

#### Políticas de Autorización
```php
// app/Policies/TestPolicy.php
class TestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Coordinador']);
    }

    public function view(User $user, Test $test): bool
    {
        return $user->hasAnyRole(['Administrador', 'Coordinador']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Administrador', 'Coordinador']);
    }

    public function update(User $user, Test $test): bool
    {
        return $user->hasAnyRole(['Administrador', 'Coordinador']);
    }

    public function delete(User $user, Test $test): bool
    {
        return $user->hasAnyRole(['Administrador', 'Coordinador']);
    }
}
```

---

## ✅ Checklist de Configuración por Módulo

### ✅ Módulo de Usuarios
- [ ] UserResource configurado
- [ ] Formularios con validación
- [ ] Tabla con filtros y búsqueda
- [ ] Políticas de autorización
- [ ] Roles y permisos asignados

### ✅ Módulo de Tests
- [ ] TestResource configurado
- [ ] Gestión de preguntas y opciones
- [ ] Niveles de competencia
- [ ] Acciones personalizadas
- [ ] Validaciones implementadas

### ✅ Módulo de Asignaciones
- [ ] TestAssignmentResource configurado
- [ ] Estados de evaluación
- [ ] Seguimiento de progreso
- [ ] Acciones de visualización
- [ ] Filtros por estado

### ✅ Módulo de Evaluaciones
- [ ] RealizarTestResource configurado
- [ ] Formulario de evaluación
- [ ] Guardado de progreso
- [ ] Validación de respuestas
- [ ] Visualización de resultados

### ✅ Módulo de Reportes
- [ ] ReportResource configurado
- [ ] Generación de PDFs
- [ ] Vista previa de reportes
- [ ] Descarga de archivos
- [ ] Estados de generación

### ✅ Módulo de API
- [ ] Rutas API configuradas
- [ ] Autenticación con Sanctum
- [ ] Controladores implementados
- [ ] Validación de datos
- [ ] Respuestas JSON

### ✅ Módulo de Dashboard
- [ ] Widgets configurados
- [ ] Estadísticas en tiempo real
- [ ] Gráficos de progreso
- [ ] Dashboard por rol
- [ ] Información personalizada

### ✅ Módulo de Permisos
- [ ] Roles definidos
- [ ] Permisos granulares
- [ ] Middleware de verificación
- [ ] Políticas de autorización
- [ ] Seeder ejecutado

---

**🎯 ¡Todos los módulos están configurados correctamente!**

*Manual de Módulos - Versión 1.0*
*Última actualización: {{ date('d/m/Y') }}* 