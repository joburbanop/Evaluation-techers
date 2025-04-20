<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Test;
// Se maneja el recurso de Crear evaluaciones TestResource
class TestPolicy
{
   
    public function view(User $user, Test $test): bool
    {
        return $user->can('Ver tests');
    }

    public function create(User $user): bool
    {
        return $user->can('Crear test');
    }

    public function update(User $user, Test $test): bool
    {
        return $user->can('Editar test');
    }

    public function delete(User $user, Test $test): bool
    {
        return $user->can('Eliminar test');
    }

   
}