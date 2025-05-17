<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstitutionResource\Pages;
use App\Models\Institution;
use App\Models\Test;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\ImageColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Alignment;
use Carbon\Carbon;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library'; 
    protected static ?string $navigationGroup = 'Gestión Institucional';
    protected static ?string $navigationLabel = 'Instituciones';
    protected static ?string $modelLabel = 'Institución';
    protected static ?string $pluralModelLabel = 'Instituciones';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Configuración de Institución')
                    ->tabs([
                        Tabs\Tab::make('Información de la IES')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Forms\Components\FileUpload::make('logo')
                                            ->label('Logo de la Institución')
                                            ->image()
                                            ->directory('institutions/logos')
                                            ->imageEditor()
                                            ->imageResizeTargetWidth(500)
                                            ->imageResizeTargetHeight(500)
                                            ->imageResizeMode('cover')
                                            ->maxSize(2048)
                                            ->disk('public')
                                            ->visibility('public')
                                            ->columnSpan(1)
                                            ->alignCenter()
                                            ->helperText('Formatos aceptados: JPG, PNG, WEBP (Máx. 2MB)')
                                            ->downloadable()
                                            ->openable()
                                            ->previewable(true)
                                            ->loadingIndicatorPosition('right')
                                            ->panelAspectRatio('3:2'),
                                        
                                        Grid::make(1)
                                            ->columnSpan(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nombre de la IES')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->helperText('Nombre completo según registro')
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\Select::make('academic_character')
                                                    ->label('Carácter Académico')
                                                    ->options([
                                                        'universidad' => '🏛️ Universidad',
                                                        'institucion_universitaria' => '🏫 Institución Universitaria o Escuela Tecnológica',
                                                        'institucion_tecnologica' => '🏢 Institución Tecnológica',
                                                        'institucion_tecnica' => '🛠️ Institución Técnica Profesional',
                                                    ])
                                                    ->required()
                                                    ->helperText('Naturaleza institucional de la IES')
                                                    ->columnSpanFull(),
                                                
                                                Forms\Components\TextInput::make('active_programs')
                                                    ->label('Número de Programas Vigentes')
                                                    ->numeric()
                                                    ->required()
                                                    ->minValue(1)
                                                    ->helperText('Cantidad de programas académicos activos')
                                                    ->columnSpan(1),
                                                
                                                Forms\Components\Toggle::make('is_accredited')
                                                    ->label('¿Está Acreditada en Alta Calidad?')
                                                    ->helperText('Indica si la institución tiene acreditación de alta calidad')
                                                    ->columnSpan(1),
                                                
                                                Grid::make(2)
                                                    ->columnSpanFull()
                                                    ->schema([
                                                        Forms\Components\Select::make('departamento_id')
                                                            ->label('Departamento')
                                                            ->relationship('departamento', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->required()
                                                            ->live()
                                                            ->afterStateUpdated(fn (Forms\Set $set) => $set('ciudad_id', null))
                                                            ->columnSpan(1),
                                                        
                                                        Forms\Components\Select::make('ciudad_id')
                                                            ->label('Ciudad/Municipio')
                                                            ->relationship('ciudad', 'name')
                                                            ->searchable()
                                                            ->preload()
                                                            ->required()
                                                            ->columnSpan(1),
                                                    ]),
                                            ]),
                                    ]),
                            ]),
                        
                        Tabs\Tab::make('Contactos y Configuración')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Forms\Components\Section::make('Persona de Contacto')
                                    ->description('Información del representante principal')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('contact_person')
                                                    ->label('Nombre Completo')
                                                    ->maxLength(100)
                                                    ->required(),
                                                
                                                Forms\Components\TextInput::make('contact_position')
                                                    ->label('Cargo/Puesto')
                                                    ->maxLength(100)
                                                    ->required(),
                                                
                                                Forms\Components\TextInput::make('contact_phone')
                                                    ->label('Teléfono de Contacto')
                                                    ->tel()
                                                    ->maxLength(20)
                                                    ->required(),
                                                
                                                Forms\Components\TextInput::make('contact_email')
                                                    ->label('Email de Contacto')
                                                    ->email()
                                                    ->maxLength(255)
                                                    ->required(),
                                            ]),
                                    ]),
                                
                                Forms\Components\Section::make('Configuración Académica')
                                    ->schema([
                                        Forms\Components\Select::make('test_id')
                                            ->label('Test Asociado')
                                            ->options(Test::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->required()
                                            ->helperText('Selecciona el test principal para esta institución'),
                                        
                                        Forms\Components\Textarea::make('additional_notes')
                                            ->label('Notas Adicionales')
                                            ->columnSpanFull()
                                            ->rows(3)
                                            ->helperText('Información adicional relevante'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=FFFFFF&background=4f46e5')
                    ->size(40)
                    ->disk('public')
                    ->visibility('public'),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->description(fn (Institution $record): string => $record->ciudad->name ?? '')
                    ->weight(FontWeight::Medium)
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('academic_character')
                    ->label('Carácter Académico')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'universidad' => '🏛️ Universidad',
                        'institucion_universitaria' => '🏫 Institución Universitaria',
                        'institucion_tecnologica' => '🏢 Institución Tecnológica',
                        'institucion_tecnica' => '🛠️ Institución Técnica',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('active_programs')
                    ->label('Programas Activos')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_accredited')
                    ->label('Acreditada')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('contact_person')
                    ->label('Contacto')
                    ->description(fn (Institution $record): string => $record->contact_position ?? '')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->alignEnd()
                    ->description(fn (Institution $record): string => 'Hace '.$record->created_at->diffForHumans()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('academic_character')
                    ->label('Carácter Académico')
                    ->options([
                        'universidad' => 'Universidad',
                        'institucion_universitaria' => 'Institución Universitaria',
                        'institucion_tecnologica' => 'Institución Tecnológica',
                        'institucion_tecnica' => 'Institución Técnica',
                    ]),
                
                Tables\Filters\SelectFilter::make('ciudad_id')
                    ->label('Ciudad')
                    ->relationship('ciudad', 'name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\TernaryFilter::make('is_accredited')
                    ->label('Acreditada')
                    ->placeholder('Todas')
                    ->trueLabel('Acreditadas')
                    ->falseLabel('No acreditadas'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->tooltip('Editar institución'),
                
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => InstitutionResource::getUrl('edit', ['record' => $record]))
                    ->color('gray')
                    ->tooltip('Ver detalles'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Eliminar seleccionadas')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-trash')
                        ->modalHeading('¿Eliminar instituciones seleccionadas?'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->groups([
                Tables\Grouping\Group::make('academic_character')
                    ->label('Carácter Académico')
                    ->collapsible(),
                Tables\Grouping\Group::make('ciudad.name')
                    ->label('Ciudad')
                    ->collapsible(),
            ])
            ->emptyStateHeading('No hay instituciones registradas')
            ->emptyStateDescription('Comienza registrando tu primera institución')
            ->emptyStateIcon('heroicon-o-building-library')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Registrar Institución')
                    ->icon('heroicon-o-plus-circle')
                    ->button(),
            ])
            ->striped()
            ->deferLoading()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInstitutions::route('/'),
            'create' => Pages\CreateInstitution::route('/create'),
            'edit'   => Pages\EditInstitution::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}