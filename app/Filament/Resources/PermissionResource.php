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
                            ->columnSpanFull()
                            ->disabled(fn ($record) => $record !== null),
                    ]),
                Section::make('Asignación de Permisos')
                    ->description('Selecciona los permisos que tendrá este rol')
                    ->schema([
                        static::getPermissionSections()
                    ])
                    ->columns(1)
            ]);
    }


    protected static function getPermissionSections(): \Filament\Forms\Components\Group
    {
       
        $permissions = Permission::all()->groupBy('module');
    
    
        $sections = $permissions->map(function ($permissions, $module) {
            
            $permissionOptions = $permissions->mapWithKeys(function ($permission) {
                return [
                    $permission->id => $permission->description
                ];
            });
    
            // Nombre legible del módulo
            $moduleName = match($module) {
                'evaluaciones' => 'Evaluaciones',
                'instituciones' => 'Instituciones',
                'administracion'  => 'Administración',
                default => Str::headline($module)
            };
    
            // Icono correspondiente
            $icon = match($module) {
                'evaluaciones' => 'heroicon-o-chart-bar',
                'instituciones' => 'heroicon-o-building-library',
                'administracion' => 'heroicon-o-shield-check',
                default => 'heroicon-o-cog'
            };
    
            // Sección de permisos para el módulo
            return \Filament\Forms\Components\Section::make($moduleName)
                ->icon($icon)
                ->collapsible()
                ->collapsed()
                ->compact()
                ->schema([
                    \Filament\Forms\Components\CheckboxList::make('permissions')
                        ->label('')
                        ->options($permissionOptions)
                        ->relationship(
                            name: 'permissions',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) => $query->where('module', $module)
                        )
                        ->bulkToggleable()
                        ->gridDirection('row')
                        ->columns(1)
                        ->searchable("Buscar permiso")
                ]);
        })->values()->toArray();
    
        return \Filament\Forms\Components\Group::make($sections)->columnSpanFull();
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

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('Gestionar roles') || auth()->user()?->can('Gestionar permisos');
    }
}