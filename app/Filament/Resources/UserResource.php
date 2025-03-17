<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            TextInput::make('phone')
                ->tel()
                ->label('Teléfono')
                ->maxLength(15)
                ->nullable(),
            Forms\Components\DatePicker::make('date_of_birth')
                ->label('Fecha de Nacimiento')
                ->maxDate(now()->subYears(15)->format('Y-m-d')) // Ajustar la fecha máxima a 15 años atrás desde hoy
                ->required()
                ->nullable(),
            
            Select::make('roles')
                ->relationship('roles', 'name')
                ->preload()
                ->label('Rol')
                ->required(),

            TextInput::make('password')
                ->required()
                ->password()
                ->maxLength(255)
                ->label('Contraseña'),
            
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre'),
                Tables\Columns\TextColumn::make('email')->label('Correo Electrónico'),
                Tables\Columns\TextColumn::make('roles')
                ->label('Rol')
                ->getStateUsing(fn ($record) => $record->getRoleNames()->join(', ')),
                
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Puedes agregar filtros aquí si lo necesitas.
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}