<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InstitutionTest; 
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class AsignacionTestPolicy
{
    
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        Log::info('AsignacionTestPolicy');
        return $user->hasRole('Administrador') ;
    }
    
    public function view(User $user, InstitutionTest $institutionTest): bool
    {
        return $user->hasRole('Administrador') ;
    }
    
    public function create(User $user): bool
    {
        return $user->hasRole('Administrador');
    }
    
    public function delete(User $user, InstitutionTest $institutionTest): bool
    {
        return $user->hasRole('Administrador') ;
    }
}