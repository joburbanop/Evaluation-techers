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
                        Tabs\Tab::make('Información de intitución')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Forms\Components\FileUpload::make('logo')
                                            ->label('Logo de la Institución')
                                            ->image()
                                            ->directory('institutions/logos')
                                            ->imageEditor()
                                            ->imageResizeTargetWidth(500) // Reemplaza a resize()
                                            ->imageResizeTargetHeight(500)
                                            ->imageResizeMode('cover')
                                            ->maxSize(2048) // 2MB máximo
                                            ->disk('public') // Asegurar usar el disco correcto
                                            ->visibility('public') // Permisos de acceso
                                            ->columnSpan(1)
                                            ->alignCenter()
                                            ->helperText('Formatos aceptados: JPG, PNG, WEBP (Máx. 2MB)')
                                            ->downloadable() // Permitir descarga
                                            ->openable() // Permitir visualización
                                            ->previewable(true) // Habilitar previsualización
                                            ->loadingIndicatorPosition('right')
                                            ->panelAspectRatio('3:2'), // Relación de aspecto
                                        
                                        Grid::make(1)
                                            ->columnSpan(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('nit')
                                                    ->label('NIT/Número de Identificación')
                                                    ->maxLength(50)
                                                    ->placeholder('Ej: 123456789-0')
                                                    ->helperText('Identificación fiscal única de la institución')
                                                    ->required(),
                                                
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Nombre Oficial')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->helperText('Nombre completo según registro')
                                                    ->columnSpanFull(),
                                                
                                                // Agregar los campos faltantes aquí
                                                Grid::make(2)
                                                    ->columnSpanFull()
                                                    ->schema([
                                                        Forms\Components\TextInput::make('address')
                                                            ->label('Dirección')
                                                            ->maxLength(255)
                                                            ->required()
                                                            ->columnSpan(1),
                                                        
                                                        Forms\Components\TextInput::make('city')
                                                            ->label('Ciudad/Municipio')
                                                            ->maxLength(100)
                                                            ->required()
                                                            ->columnSpan(1),
                                                        
                                                        Forms\Components\TextInput::make('phone')
                                                            ->label('Teléfono Principal')
                                                            ->tel()
                                                            ->maxLength(20)
                                                            ->required()
                                                            ->columnSpan(1),
                                                        
                                                        Forms\Components\TextInput::make('email')
                                                            ->label('Correo Electrónico')
                                                            ->email()
                                                            ->maxLength(255)
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
                    ->size(40)->disk('public') 
                    ->visibility('public'),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->description(fn (Institution $record): string => $record->ciudad->name ?? '')
                    ->weight(FontWeight::Medium)
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('nit')
                    ->label('NIT')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('address')
                    ->label('Dirección')
                    ->toggleable(isToggledHiddenByDefault: true),    
                
                Tables\Columns\TextColumn::make('phone')
                    ->label('Teléfono')
                    ->toggleable(),

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
                Tables\Filters\SelectFilter::make('ciudad_id')
                    ->label('Ciudad')
                    ->relationship('ciudad', 'name')
                    ->searchable()
                    ->preload(),
                
                Tables\Filters\Filter::make('has_test')
                    ->label('Con test asignado')
                    ->query(fn ($query) => $query->whereNotNull('test_id')),
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