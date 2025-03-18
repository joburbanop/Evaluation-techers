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
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Roles y Permisos';
    protected static ?string $navigationLabel = 'Usuarios';
    protected static ?string $pluralLabel = 'Usuarios';

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
                    ->maxDate(now()->subYears(15)->format('Y-m-d')) 
                    ->required()
                    ->nullable(),
                Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name') 
                    ->preload()
                    ->label('Rol')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->label('Contraseña')
                    ->nullable()  // La contraseña es opcional al editar
                    ->dehydrated(fn ($state) => filled($state)) // Solo se incluye si el campo tiene un valor
                    ->required(fn ($get) => !$get('id'))
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // Añadir un hook para sincronizar los roles al guardar el usuario
    public static function afterSave(Form $form, User $user): void
    {
        $data = $form->getState(); // Obtener el estado del formulario
        $roles = $data['roles'] ?? []; // Obtener los roles seleccionados

        // Sincronizar los roles con el usuario
        $user->roles()->sync($roles);

        Notification::make()
            ->title('Roles sincronizados correctamente')
            ->success()
            ->send();
    }
}