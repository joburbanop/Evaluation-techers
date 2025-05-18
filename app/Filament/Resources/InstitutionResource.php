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
                                Grid::make(2)
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
                                                'Universidad' => '🏛️ Universidad',
                                                'Institución Universitaria' => '🏫 Institución Universitaria',
                                                'Institución Tecnológica' => '🏢 Institución Tecnológica',
                                                'Institución Técnica' => '🛠️ Institución Técnica',
                                            ])
                                            ->required()
                                            ->helperText('Naturaleza institucional de la IES')
                                            ->columnSpan(1),
                                        
                                        Forms\Components\TextInput::make('programas_vigentes')
                                            ->label('Número de Programas Vigentes')
                                            ->numeric()
                                            ->required()
                                            ->minValue(1)
                                            ->helperText('Cantidad de programas académicos activos')
                                            ->columnSpan(1),
                                        
                                        Forms\Components\TextInput::make('departamento_domicilio')
                                            ->label('Departamento')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        
                                        Forms\Components\TextInput::make('municipio_domicilio')
                                            ->label('Ciudad/Municipio')
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                    ]),
                            ]),
                        
                        Tabs\Tab::make('Contactos y Configuración')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Forms\Components\Section::make('Persona de Contacto')
                                    ->description('Información del representante principal (opcional)')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('contact_person')
                                                    ->label('Nombre Completo')
                                                    ->maxLength(100),
                                                
                                                Forms\Components\TextInput::make('contact_position')
                                                    ->label('Cargo/Puesto')
                                                    ->maxLength(100),
                                                
                                                Forms\Components\TextInput::make('contact_phone')
                                                    ->label('Teléfono de Contacto')
                                                    ->tel()
                                                    ->maxLength(20),
                                                
                                                Forms\Components\TextInput::make('contact_email')
                                                    ->label('Email de Contacto')
                                                    ->email()
                                                    ->maxLength(255),
                                            ]),
                                    ]),
                                
                                    Forms\Components\Section::make('Configuración Académica')
                                    ->schema([
                                        Forms\Components\Select::make('tests')
                                            ->label('Tests Asociados')
                                            ->multiple()
                                            ->relationship('tests', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->createOptionForm([
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('name')
                                                            ->label('Nombre del Test')
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->columnSpan(1),
                                                        
                                                        Forms\Components\TextInput::make('description')
                                                            ->label('Descripción')
                                                            ->maxLength(255)
                                                            ->columnSpan(1),
                                                        
                                                        Forms\Components\ColorPicker::make('color')
                                                            ->label('Color identificativo')
                                                            ->columnSpan(1),
                                                            
                                                        Forms\Components\FileUpload::make('icon')
                                                            ->label('Icono')
                                                            ->image()
                                                            ->directory('test-icons')
                                                            ->columnSpan(1),
                                                    ])
                                            ])
                                            ->helperText('Selecciona o crea tests para asociar a esta institución')
                                            ->optionsLimit(20)
                                            ->maxItems(10)
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                $set('tests_count', count($state ?? []));
                                            })
                                            ->columnSpanFull(),
                                            
                                        Forms\Components\Placeholder::make('tests_info')
                                            ->content(fn ($get): string => count($get('tests') ?? []) > 0 
                                                ? '✅ ' . count($get('tests')) . ' tests asociados' 
                                                : '⚠️ No hay tests asociados')
                                            ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->description(fn (Institution $record): string => $record->municipio_domicilio ?? '')
                    ->weight(FontWeight::Medium)
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('academic_character')
                    ->label('Carácter Académico')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'Universidad' => '🏛️ Universidad',
                        'Institución Universitaria' => '🏫 Institución Universitaria',
                        'Institución Tecnológica' => '🏢 Institución Tecnológica',
                        'Institución Técnica' => '🛠️ Institución Técnica',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('programas_vigentes')
                    ->label('Programas Vigentes')
                    ->numeric()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('municipio_domicilio')
                    ->label('Ciudad')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('departamento_domicilio')
                    ->label('Departamento')
                    ->searchable()
                    ->sortable(),
                
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
                        'Universidad' => 'Universidad',
                        'Institución Universitaria' => 'Institución Universitaria',
                        'Institución Tecnológica' => 'Institución Tecnológica',
                        'Institución Técnica' => 'Institución Técnica',
                    ]),
                
                Tables\Filters\SelectFilter::make('departamento_domicilio')
                    ->label('Departamento')
                    ->searchable(),
                
                Tables\Filters\SelectFilter::make('municipio_domicilio')
                    ->label('Ciudad')
                    ->searchable(),
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
                Tables\Grouping\Group::make('departamento_domicilio')
                    ->label('Departamento')
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