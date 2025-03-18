<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePolicy
{
    /**
     * Permitir que solo los administradores vean todos los roles.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Administrador');
    }

    /**
     * Permitir que un usuario vea un rol si es administrador o el mismo rol.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasRole('Administrador') || $user->id === $role->id;
    }

    /**
     * Permitir que solo los administradores creen nuevos roles.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Administrador');
    }

    /**
     * Permitir que solo los administradores actualicen roles.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasRole('Administrador');
    }

    /**
     * Permitir que solo los administradores eliminen roles.
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasRole('Administrador');
    }

    /**
     * Permitir que solo los administradores restauren roles eliminados.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->hasRole('Administrador');
    }

    /**
     * Permitir que solo los administradores eliminen roles permanentemente.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->hasRole('Administrador');
    }
}