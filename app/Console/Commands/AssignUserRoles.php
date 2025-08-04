<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class AssignUserRoles extends Command
{
    protected $signature = 'users:assign-roles {--admin= : Email del administrador} {--coordinador= : Email del coordinador} {--all-docentes : Asignar rol Docente a todos los usuarios sin roles}';
    protected $description = 'Asigna roles a los usuarios del sistema';

    public function handle()
    {
        $this->info('🔧 Iniciando asignación de roles...');

        // Verificar que los roles existan
        $roles = ['Administrador', 'Coordinador', 'Docente'];
        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                $this->error("❌ El rol '{$roleName}' no existe");
                return 1;
            }
            $this->info("✅ Rol '{$roleName}' encontrado");
        }

        // Asignar administrador
        $adminEmail = $this->option('admin');
        if ($adminEmail) {
            $adminUser = User::where('email', $adminEmail)->first();
            if ($adminUser) {
                $adminUser->assignRole('Administrador');
                $this->info("✅ Administrador asignado a: {$adminUser->name} ({$adminEmail})");
            } else {
                $this->warn("⚠️ Usuario con email '{$adminEmail}' no encontrado");
            }
        }

        // Asignar coordinador
        $coordinadorEmail = $this->option('coordinador');
        if ($coordinadorEmail) {
            $coordinadorUser = User::where('email', $coordinadorEmail)->first();
            if ($coordinadorUser) {
                $coordinadorUser->assignRole('Coordinador');
                $this->info("✅ Coordinador asignado a: {$coordinadorUser->name} ({$coordinadorEmail})");
            } else {
                $this->warn("⚠️ Usuario con email '{$coordinadorEmail}' no encontrado");
            }
        }

        // Asignar rol Docente a todos los usuarios sin roles
        if ($this->option('all-docentes')) {
            $usersWithoutRoles = User::whereDoesntHave('roles')->get();
            $count = 0;
            
            $this->info("📊 Asignando rol 'Docente' a {$usersWithoutRoles->count()} usuarios...");
            
            foreach ($usersWithoutRoles as $user) {
                $user->assignRole('Docente');
                $count++;
                
                if ($count % 50 == 0) {
                    $this->info("✅ Procesados {$count} usuarios...");
                }
            }
            
            $this->info("✅ Rol 'Docente' asignado a {$count} usuarios");
        }

        // Mostrar estadísticas finales
        $this->info("\n📊 Estadísticas finales:");
        $this->info("👥 Total usuarios: " . User::count());
        $this->info("👑 Administradores: " . User::role('Administrador')->count());
        $this->info("🎯 Coordinadores: " . User::role('Coordinador')->count());
        $this->info("👨‍🏫 Docentes: " . User::role('Docente')->count());
        $this->info("❌ Sin roles: " . User::whereDoesntHave('roles')->count());

        $this->info("\n🎉 Asignación de roles completada!");
        return 0;
    }
}
