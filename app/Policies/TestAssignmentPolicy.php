<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TestAssignment;

class TestAssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('Ver asignaciones');
    }

    public function view(User $user, TestAssignment $assignment): bool
    {
        return $user->can('Ver asignaciones') || 
               ($assignment->user_id === $user->id && $user->can('ver_asignaciones_propias'));
    }

    public function create(User $user): bool
    {
        return $user->can('Crear asignaciones');
    }

    public function update(User $user, TestAssignment $assignment): bool
    {
        return $user->can('Editar asignaciones');
    }

    public function delete(User $user, TestAssignment $assignment): bool
    {
        return $user->can('Eliminar asignaciones');
    }

    public function viewResults(User $user, TestAssignment $assignment): bool
    {
        return $user->can('Ver resultados') || 
               ($assignment->user_id === $user->id);
    }
}
// Activa Asignaciones De Evaluaciones y Test Asignados