<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class RolePolicy
{
     /**
     * Permitir que solo los administradores vean todos los usuarios.
     */
    public function viewAny(User $user): bool
    {
        Log::info('RolePolicy');
        return $user->hasRole('Administrador');
    }

    /**
     * Permitir que un usuario vea su propia cuenta o que un administrador vea cualquier usuario.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasRole('Administrador') || $user->id === $model->id;
    }

    /**
     * Permitir que solo los administradores creen nuevos usuarios.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Administrador');
    }

    /**
     * Permitir que solo los administradores actualicen usuarios.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasRole('Administrador');
    }

    /**
     * Permitir que solo los administradores eliminen usuarios.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('Administrador');
    }

    /**
     * Permitir que solo los administradores restauren usuarios eliminados.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('Administrador');
    }

    /**
     * Permitir que solo los administradores eliminen usuarios permanentemente.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasRole('Administrador');
    }
}