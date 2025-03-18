<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Test;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class TestPolicy
{
    
    use HandlesAuthorization;

    /**
     * Permitir que solo los administradores o usuarios con el permiso 'ver tests' vean los tests.
     */
    public function viewAny(User $user): bool
    {
        Log::info('estPolicy');  
        return $user->hasRole('Administrador') || $user->can('ver tests');
    }

    /**
     * Permitir que solo los administradores o el propio usuario vea un test.
     */
    public function view(User $user, Test $test): bool
    {
        return $user->hasRole('Administrador') || $user->can('ver tests');
    }

    /**
     * Permitir que solo los administradores o usuarios con el permiso 'crear tests' puedan crear tests.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Administrador') || $user->can('crear tests');
    }

    /**
     * Permitir que solo los administradores o usuarios con el permiso 'editar tests' puedan editar tests.
     */
    public function update(User $user, Test $test): bool
    {
        return $user->hasRole('Administrador') || $user->can('editar tests');
    }

    /**
     * Permitir que solo los administradores o usuarios con el permiso 'eliminar tests' puedan eliminar tests.
     */
    public function delete(User $user, Test $test): bool
    {
        return $user->hasRole('Administrador') || $user->can('eliminar tests');
    }

    /**
     * Permitir que solo los administradores o usuarios con el permiso 'restaurar tests' puedan restaurar tests eliminados.
     */
    public function restore(User $user, Test $test): bool
    {
        return $user->hasRole('Administrador') || $user->can('restaurar tests');
    }

    /**
     * Permitir que solo los administradores o usuarios con el permiso 'eliminar tests permanentemente' puedan eliminarlos permanentemente.
     */
    public function forceDelete(User $user, Test $test): bool
    {
        return $user->hasRole('Administrador') || $user->can('eliminar tests permanentemente');
    }
}