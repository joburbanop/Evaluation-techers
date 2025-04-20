<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TestAssignment;

class RealizarTestPolicy
{


    public function viewAny(User $user): bool
    {
        return $user->can('Ver_asignaciones');
    }

    
}
