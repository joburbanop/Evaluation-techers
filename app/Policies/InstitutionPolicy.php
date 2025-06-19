<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Institution;

class InstitutionPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->hasRole('Administrador')) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return $user->can('Ver instituciones');
    }

    public function create(User $user): bool
    {
        return $user->can('Crear institucion');
    }

    public function update(User $user, Institution $institution): bool
    {
        return $user->can('Editar institucion');
    }

    public function delete(User $user, Institution $institution): bool
    {
        return $user->can('Eliminar institucion');
    }

}