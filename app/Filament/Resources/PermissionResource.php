<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MultiSelect;
use Filament\Tables\Table;
use Filament\Tables;

class PermissionResource extends Resource
{
    // Cambiamos el modelo a Role para manejar roles
    protected static ?string $model = Role::class;

    // Actualizamos las propiedades de navegación
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Roles y Permisos';
    protected static ?string $navigationGroup = 'Administración';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre del Rol')
                    ->required()
                    ->maxLength(255),

                // Agregamos un MultiSelect para permisos
                MultiSelect::make('permissions')
                    ->label('Permisos')
                    ->relationship('permissions', 'name')  // Relacionamos con el modelo de permisos
                    ->preload()
                    ->options(Permission::all()->pluck('name', 'id')) // Obtén todos los permisos y los muestra
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Rol'),
                Tables\Columns\TextColumn::make('permissions')
                    ->label('Permisos')
                    ->getStateUsing(fn ($record) => $record->permissions->pluck('name')->join(', ')),
            ])
            ->defaultSort('name')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit'   => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}