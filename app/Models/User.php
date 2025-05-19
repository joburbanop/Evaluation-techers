<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Models\Contracts\FilamentUser;   
use Filament\Panel;                           


class User extends Authenticatable implements MustVerifyEmail,FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',         
        'date_of_birth', 
        'document_type',
        'document_number',
        'position',
        'institution_id',
        'is_active',
        'departamento_id',
        'ciudad_id',
        'institution',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Los tipos de documento disponibles
     */
    public const DOCUMENT_TYPES = [
        'CC' => 'Cédula de Ciudadanía',
        'CE' => 'Cédula de Extranjería',
        'TI' => 'Tarjeta de Identidad',
        'PP' => 'Pasaporte'
    ];

    public function testAssignments()
    {
        return $this->hasMany(TestAssignment::class);
    }

    /**
     * Obtiene la institución a la que pertenece el usuario.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }
    public function canAccessPanel(Panel $panel): bool
{
    return match ($panel->getId()) {
        'admin'       => $this->hasRole('Administrador'),
        'coordinador' => $this->hasRole('Coordinador'),
        'docente'     => $this->hasRole('Docente'),
        default       => false,
    };
}

}
