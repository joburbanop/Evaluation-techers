<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Institution;

class InstitutionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('Ver institucion');
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