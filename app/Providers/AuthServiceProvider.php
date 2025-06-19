<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Role;
use App\Policies\UserPolicy;
use App\Policies\RolePolicy;
use Spatie\Permission\Models\Permission;
use App\Policies\PermissionPolicy;
use App\Models\Test;
use App\Policies\TestPolicy;
use App\Models\Institution;
use App\Policies\InstitutionPolicy;
use App\Models\TestAssignment;
use App\Policies\TestAssignmentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Test::class => TestPolicy::class,
        TestAssignment::class => TestAssignmentPolicy::class,
        Institution::class => InstitutionPolicy::class,
        Permission::class => PermissionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        
    }
}