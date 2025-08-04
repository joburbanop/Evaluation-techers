<?php

namespace App\Policies;

use App\Models\User;
// controla el recurso de usuarios UserResource

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('Gestionar usuarios');
    }

    public function create(User $user): bool
    {
        return $user->can('Gestionar usuarios');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can('Gestionar usuarios');
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('Gestionar usuarios');
    }
}
