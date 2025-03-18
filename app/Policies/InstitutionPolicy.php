<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Institution;

class InstitutionPolicy
{
   /**
     * Permitir que solo los administradores o usuarios con el permiso 'ver instituciones' puedan ver la lista de instituciones.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver instituciones');
    }

    /**
     * Permitir que solo los administradores o usuarios con el permiso 'crear instituciones' puedan crear instituciones.
     */
    public function create(User $user): bool
    {
        return $user->can('crear instituciones');
    }

    /**
     * Permitir que solo los administradores o usuarios con el permiso 'editar instituciones' puedan editar instituciones.
     */
    public function update(User $user, Institution $institution): bool
    {
        return $user->can('editar instituciones');
    }

    /**
     * Permitir que solo los administradores o usuarios con el permiso 'eliminar instituciones' puedan eliminar instituciones.
     */
    public function delete(User $user, Institution $institution): bool
    {
        return $user->can('eliminar instituciones');
    }
}