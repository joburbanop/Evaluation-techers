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

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Los atributos que se pueden asignar en masa.
     *
     * @var array<int, string>
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

        // Llaves foráneas en lugar de texto
        'institution_id',
        'departamento_id',
        'ciudad_id',
        'facultad_id',
        'programa_id',

        'is_active',
        // no incluimos 'institution' de tipo string, pues ahora es 'institution_id'
    ];

    /**
     * Atributos que se ocultan al serializar (p. ej. en JSON).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos de columnas.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    /**
     * Los tipos de documento disponibles.
     */
    public const DOCUMENT_TYPES = [
        'CC' => 'Cédula de Ciudadanía',
        'CE' => 'Cédula de Extranjería',
        'TI' => 'Tarjeta de Identidad',
        'PP' => 'Pasaporte'
    ];

    /**
     * Relación: Usuario pertenece a una Institution.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    /**
     * Relación: Usuario pertenece a un Departamento.
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    /**
     * Relación: Usuario pertenece a una Ciudad.
     */
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    /**
     * Relación: Usuario pertenece a una Facultad.
     */
    public function facultad(): BelongsTo
    {
        return $this->belongsTo(Facultad::class, 'facultad_id');
    }

    /**
     * Relación: Usuario pertenece a un Programa.
     */
    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class, 'programa_id');
    }

    /**
     * Ejemplo de relación específica que ya tenías:
     */
    public function testAssignments()
    {
        return $this->hasMany(TestAssignment::class);
    }

    /**
     * Método que Filament usa para verificar si el usuario puede acceder al panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if (!$panel) {
            return false;
        }

        try {
            $panelId = $panel->getId();
            if (!$panelId) {
                return false;
            }

            return match ($panelId) {
                'admin'       => $this->hasRole('Administrador'),
                'coordinador' => $this->hasRole('Coordinador'),
                'docente'     => $this->hasRole('Docente'),
                default       => false,
            };
        } catch (\Exception $e) {
            \Log::error('Error al verificar acceso al panel: ' . $e->getMessage(), [
                'user_id'  => $this->id,
                'panel_id' => $panel->getId() ?? 'unknown'
            ]);
            return false;
        }
    }
}