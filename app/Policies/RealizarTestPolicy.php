<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TestAssignment;

class RealizarTestPolicy
{


    public function viewAny(User $user): bool
    {
        return $user->can('Ver asignaciones');
    }

    public function view(User $user, TestAssignment $assignment): bool
    {
        return $user->can('Ver asignaciones') || ($assignment->user_id === $user->id);
    }

    public function before(User $user, $ability)
    {
        if ($user->hasRole('Administrador')) {
            return true;
        }
    }

    
}
