<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstitutionResource\Pages;
use App\Models\Institution;
use App\Models\Test;  // Importar el modelo Test
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstitutionResource extends Resource
{
    protected static ?string $model = Institution::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Instituciones';
    protected static ?string $label = 'Institución';
    protected static ?string $pluralLabel = 'Instituciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // NIT
                Forms\Components\TextInput::make('nit')
                    ->label('NIT')
                    ->maxLength(50)
                    ->placeholder('Ej: 123456789-0')
                    ->helperText('Identificación de la institución')
                    ->columnSpan('full'),

                // Nombre
                Forms\Components\TextInput::make('name')
                    ->label('Nombre de la Institución')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),

                // Dirección
                Forms\Components\TextInput::make('address')
                    ->label('Dirección')
                    ->maxLength(255),

                // Ciudad
                Forms\Components\TextInput::make('city')
                    ->label('Ciudad')
                    ->maxLength(100),

                // Teléfono
                Forms\Components\TextInput::make('phone')
                    ->label('Teléfono')
                    ->maxLength(20),

                // Persona de contacto
                Forms\Components\TextInput::make('contact_person')
                    ->label('Persona de Contacto')
                    ->maxLength(100),

                // Email
                Forms\Components\TextInput::make('email')
                    ->label('Correo Electrónico')
                    ->email()
                    ->maxLength(255),

                // Agregar el campo Select para los tests
                Forms\Components\Select::make('test_id') // Asumimos que tienes una relación con 'Test'
                    ->label('Seleccionar Test')
                    ->options(Test::all()->pluck('name', 'id')) // Cargar los tests desde la base de datos
                    ->searchable()
                    ->required(), // Si deseas que este campo sea obligatorio
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nit')->label('NIT')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Nombre')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('city')->label('Ciudad')->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('Teléfono'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListInstitutions::route('/'),
            'create' => Pages\CreateInstitution::route('/create'),
            'edit'   => Pages\EditInstitution::route('/{record}/edit'),
        ];
    }
}