<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TestAssignment;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestAssignmentPolicy
{
    use HandlesAuthorization;

    /**
     * Permitir que solo los administradores o coordinadores vean las asignaciones de tests.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Administrador') || $user->hasRole('Coordinador');
    }

    /**
     * Permitir que solo los administradores o coordinadores vean una asignación específica.
     */
    public function view(User $user, TestAssignment $testAssignment): bool
    {
        return $user->hasRole('Administrador') || $user->hasRole('Coordinador');
    }

    /**
     * Permitir que solo los administradores o coordinadores creen asignaciones de tests.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Administrador') || $user->hasRole('Coordinador');
    }

    /**
     * Permitir que solo los administradores o coordinadores actualicen asignaciones de tests.
     */
    public function update(User $user, TestAssignment $testAssignment): bool
    {
        return $user->hasRole('Administrador') || $user->hasRole('Coordinador');
    }

    /**
     * Permitir que solo los administradores o coordinadores eliminen asignaciones de tests.
     */
    public function delete(User $user, TestAssignment $testAssignment): bool
    {
        return $user->hasRole('Administrador') || $user->hasRole('Coordinador');
    }
}