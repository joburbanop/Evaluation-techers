<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administración del Sistema';
    protected static ?string $navigationLabel = 'Gestión de Usuarios';
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Personal')
                    ->description('Datos básicos del usuario')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre Completo')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(['md' => 2]),
                            
                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpan(['md' => 2]),
                            
                        Forms\Components\TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel()
                            ->maxLength(15)
                            ->nullable()
                            ->columnSpan(['md' => 1]),
                            
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Fecha de Nacimiento')
                            ->displayFormat('d/m/Y') 
                            ->native(false) 
                            ->maxDate(now()->subYears(15)) 
                            ->default(now()->subYears(20)) 
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->nullable()
                            ->columnSpan(['md' => 1])
                            ->helperText('Seleccione o escriba su fecha de nacimiento (opcional al editar)')
                            ->extraAttributes([
                                'class' => 'cursor-pointer', 
                            ])
                            ->extraInputAttributes([
                                'placeholder' => 'Ej: 15/05/1990',
                            ])
                            ->live() 
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                
                                if ($state) {
                                    $age = now()->diffInYears(Carbon::parse($state));
                                    $set('age', $age); 
                                }
                            }),
                    ])
                    ->columns(['md' => 3]),
                    
                Forms\Components\Section::make('Seguridad y Acceso')
                    ->description('Configuración de credenciales y roles')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->label('Roles')
                            ->required()
                            ->columnSpan(['md' => 1]),
                            
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->label('Contraseña')
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->rule(fn (string $operation): bool => $operation === 'create' ? Password::default() : false)
                            ->same('passwordConfirmation')
                            ->validationMessages([
                                'same' => 'Las contraseñas no coinciden',
                            ])
                            ->helperText('Dejar vacío para mantener la contraseña actual')
                            ->columnSpan(['md' => 1]),
                            
                        Forms\Components\TextInput::make('passwordConfirmation')
                            ->password()
                            ->label('Confirmar Contraseña')
                            ->requiredWith('password')
                            ->dehydrated(false)
                            ->helperText('Solo si va a cambiar la contraseña')
                            ->columnSpan(['md' => 1]),
                    ])
                    ->columns(['md' => 3]),
                    
                Forms\Components\Section::make('Estado del Usuario')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Usuario Activo')
                            ->default(true)
                            ->inline(false),
                            
                        Forms\Components\Toggle::make('email_verified_at')
                            ->label('Correo Verificado')
                            ->default(fn () => now())
                            ->dehydrateStateUsing(fn ($state) => $state ? now() : null)
                            ->hidden(fn (string $operation): bool => $operation === 'create'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=FFFFFF&background=111827'),
                    
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                    
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verificado')
                    ->boolean()
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Filtrar por Rol'),
                    
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Correo Verificado')
                    ->nullable(),
                    
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil')
                        ->tooltip('Editar usuario'),
                        
                    Tables\Actions\Action::make('changePassword')
                        ->icon('heroicon-o-key')
                        ->tooltip('Cambiar contraseña')
                        ->form([
                            Forms\Components\TextInput::make('new_password')
                                ->password()
                                ->label('Nueva Contraseña')
                                ->required()
                                ->rule(Password::default()),
                                
                            Forms\Components\TextInput::make('new_password_confirmation')
                                ->password()
                                ->label('Confirmar Nueva Contraseña')
                                ->required()
                                ->same('new_password'),
                        ])
                        ->action(function (User $user, array $data): void {
                            $user->update([
                                'password' => Hash::make($data['new_password'])
                            ]);
                            
                            Notification::make()
                                ->title('Contraseña actualizada exitosamente')
                                ->success()
                                ->send();
                        }),
                        
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->tooltip('Eliminar usuario')
                        ->before(function (User $record) {
                            if ($record->is(auth()->user())) {
                                Notification::make()
                                    ->title('No puedes eliminar tu propio usuario')
                                    ->danger()
                                    ->send();
                                
                                throw new \Exception('No puedes eliminar tu propio usuario');
                            }
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($action, $records) {
                            $authUser = auth()->user();
                            foreach ($records as $record) {
                                if ($record->is($authUser)) {
                                    Notification::make()
                                        ->title('No puedes eliminar tu propio usuario')
                                        ->danger()
                                        ->send();
                                    
                                    $action->cancel();
                                }
                            }
                        }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }
}