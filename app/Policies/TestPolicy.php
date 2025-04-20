<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Test;
// Se maneja el recurso de Crear evaluaciones TestResource

class TestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('Ver gestion Evaluaciones');
    }
    
    public function view(User $user, Test $test): bool
    {
        return $user->can('Ver gestion Evaluaciones');
    }

    public function create(User $user): bool
    {
        return $user->can('Crear evaluaciones');
    }

    public function update(User $user, Test $test): bool
    {
        return $user->can('Editar evaluaciones');
    }

    public function delete(User $user, Test $test): bool
    {
        return $user->can('Eliminar evaluaciones');
    }

   
}