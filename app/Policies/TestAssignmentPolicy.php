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
        return $user->can('asignar_test');
    }

    public function update(User $user, TestAssignment $assignment): bool
    {
        return $user->can('editar_asignaciones');
    }

    public function delete(User $user, TestAssignment $assignment): bool
    {
        return $user->can('eliminar_asignaciones');
    }

    public function viewResults(User $user, TestAssignment $assignment): bool
    {
        return $user->can('ver_resultados') || 
               ($assignment->user_id === $user->id);
    }
}
// Activa Asignaciones De Evaluaciones y Test Asignados