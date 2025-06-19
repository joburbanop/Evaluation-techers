<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('Ver Gestion de roles');
    }


    public function create(User $user): bool
    {
        return $user->can('Crear permisos');
    }

    public function update(User $user): bool
    {
        return $user->can('Editar permisos');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->can('Eliminar permiso');
    }

}
