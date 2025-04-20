<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Spatie\Permission\Models\Permission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Role;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Str;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\Group;

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
        'test_assignment' => 'Asignaciones',
        'role' => 'Roles',
        'permission' => 'Permisos',
        'evaluation' => 'Evaluaciones'
    ];

    protected static function getPermissionSections(): \Filament\Forms\Components\Group
    {
        // Obtener todos los permisos
        $permissions = Permission::all()->unique('name');
        
        // Agrupar los permisos por recurso (usuarios, instituciones, etc.)
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            // Agrupar por el primer parte del nombre del permiso (por ejemplo, 'user', 'institution', etc.)
            return $parts[0] ?? 'general';
        });
        
        // Filtrar y crear secciones por cada grupo de permisos (usuarios, instituciones, etc.)
        $sections = $groupedPermissions->map(function ($permissions, $group) {
            $groupName = match ($group) {
                'user' => 'Usuarios',
                'institution' => 'Instituciones',
                'test' => 'Tests',
                'test_assignment' => 'Asignaciones',
                'role' => 'Roles',
                'permission' => 'Permisos',
                'evaluation' => 'Evaluaciones',
                'general' => 'Permisos Generales', // Mejoramos el nombre aquí
                default => 'Otros Permisos'
            };
    
            // Crear opciones de permisos para cada grupo
            $permissionOptions = $permissions->mapWithKeys(function ($permission) {
                $parts = explode('.', $permission->name);
                $action = end($parts);
                return [
                    $permission->id => Str::headline($action)
                ];
            });
    
            // Crear una sección para cada grupo de permisos
            return \Filament\Forms\Components\Section::make($groupName)
                ->icon(self::getGroupIcon($group)) // Asignamos el ícono del grupo
                ->collapsible() // Permitir que las secciones sean colapsables
                ->collapsed() // La sección se muestra colapsada por defecto
                ->compact() // Hacer la sección más compacta
                ->schema([
                    \Filament\Forms\Components\CheckboxList::make('permissions')
                        ->label('')
                        ->options($permissionOptions)
                        ->relationship('permissions', 'name')
                        ->bulkToggleable() // Permite alternar la selección en bloque
                        ->gridDirection('row')
                        ->columns(1)
                        ->searchable()
                ]);
        })->values()->toArray();
        
        // Devolver las secciones de permisos agrupadas
        return \Filament\Forms\Components\Group::make($sections)->columnSpanFull();
    }
    
    
    

    protected static function getGroupIcon(string $group): string
    {
        return match($group) {
            'user' => 'heroicon-o-users',
            'institution' => 'heroicon-o-building-library',
            'test' => 'heroicon-o-clipboard-document',
            'test_assignment' => 'heroicon-o-document-check',
            'role' => 'heroicon-o-shield-exclamation',
            'permission' => 'heroicon-o-key',
            'evaluation' => 'heroicon-o-chart-bar',
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
                
                Tables\Columns\BadgeColumn::make('permissions_count')
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
                Tables\Filters\SelectFilter::make('permissions')
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