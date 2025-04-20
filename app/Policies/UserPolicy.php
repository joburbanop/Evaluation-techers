<?php

namespace App\Policies;

use App\Models\User;
// controla el recurso de usuarios UserResource

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('Ver usuarios');
    }

    public function create(User $user): bool
    {
        return $user->can('Crear usuario');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can('NA');
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('Editar usuario');
    }
}
