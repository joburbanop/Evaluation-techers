<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Spatie\Permission\Models\Permission;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Table;
use Filament\Tables;
use App\Models\Role;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Str;

class PermissionResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Gestión de Roles';
    protected static ?string $modelLabel = 'Rol';
    protected static ?string $navigationGroup = 'Administración del Sistema';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información Básica del Rol')
                    ->description('Define los detalles principales del rol')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del Rol')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Ej: Coordinador Académico')
                            ->columnSpanFull(),
                    ]),
                
                Section::make('Asignación de Permisos')
                    ->description('Selecciona los permisos que tendrá este rol')
                    ->schema([
                        static::getPermissionSections()
                    ])
                    ->columns(1)
            ]);
    }

    protected static array $mainResources = [
        'user' => 'Usuarios',
        'institution' => 'Instituciones',
        'test' => 'Tests',
        'assignment' => 'Asignaciones',
        'role' => 'Roles'
    ];

    protected static function getPermissionSections(): Group
    {
        $permissions = Permission::all()->unique('name');
        
        // Filtrar y agrupar solo los permisos de los recursos principales
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            $resource = $parts[0] ?? 'general';
            
            // Si el permiso no pertenece a un recurso principal, lo agrupamos como "Otros"
            return array_key_exists($resource, static::$mainResources) ? $resource : 'other';
        })->sortBy(function ($items, $key) {
            // Ordenar según el orden definido en $mainResources
            return array_search($key, array_keys(static::$mainResources)) ?? PHP_INT_MAX;
        });
        
        $sections = $groupedPermissions->map(function ($permissions, $group) {
            $groupName = static::$mainResources[$group] ?? 'Permisos Generales';
            
            $permissionOptions = $permissions->mapWithKeys(function ($permission) {
                $parts = explode('.', $permission->name);
                $action = end($parts);
                return [
                    $permission->id => Str::headline($action) // Formato más limpio
                ];
            })->sortKeys();
            
            return Section::make($groupName)
                ->icon(self::getGroupIcon($group))
                ->collapsible()
                ->collapsed()
                ->compact()
                ->schema([
                    CheckboxList::make('permissions')
                        ->label('')
                        ->options($permissionOptions)
                        ->relationship('permissions', 'name')
                        ->bulkToggleable()
                        ->gridDirection('row')
                        ->columns(1)
                        ->searchable()
                ]);
        })->values()->toArray();
        
        return Group::make($sections)->columnSpanFull();
    }

    protected static function getGroupIcon(string $group): string
    {
        return match($group) {
            'user' => 'heroicon-o-users',
            'institution' => 'heroicon-o-building-library',
            'test' => 'heroicon-o-clipboard-document',
            'assignment' => 'heroicon-o-document-check',
            'role' => 'heroicon-o-shield-exclamation',
            default => 'heroicon-o-cog'
        };
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre del Rol')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn ($record) => $record->users_count.' usuarios', position: 'below'),
                
                BadgeColumn::make('permissions_count')
                    ->label('Permisos')
                    ->counts('permissions')
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('permissions')
                    ->label('Filtrar por Permiso')
                    ->relationship('permissions', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip('Editar rol'),
                
                Action::make('duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->tooltip('Duplicar rol')
                    ->form([
                        TextInput::make('name')
                            ->label('Nuevo nombre del rol')
                            ->required()
                            ->unique(),
                    ])
                    ->action(function (Role $role, array $data): void {
                        $newRole = $role->replicate();
                        $newRole->name = $data['name'];
                        $newRole->save();
                        $newRole->syncPermissions($role->permissions);
                    }),
                
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Eliminar rol')
                    ->before(function (Role $record) {
                        if ($record->users()->count() > 0) {
                            throw new \Exception('No se puede eliminar un rol que tiene usuarios asignados.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No hay roles registrados')
            ->emptyStateDescription('Crea un nuevo rol para comenzar')
            ->emptyStateIcon('heroicon-o-shield-check')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear nuevo rol')
                    ->icon('heroicon-o-plus'),
            ])
            ->defaultSort('created_at', 'desc')
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withCount(['users', 'permissions']);
    }
}