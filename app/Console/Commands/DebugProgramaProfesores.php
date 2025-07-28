<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Programa;
use App\Models\User;

class DebugProgramaProfesores extends Command
{
    protected $signature = 'debug:programa-profesores {programa_id}';
    protected $description = 'Debug cuántos profesores hay en un programa específico';

    public function handle()
    {
        $programaId = $this->argument('programa_id');
        
        $programa = Programa::find($programaId);
        if (!$programa) {
            $this->error("Programa con ID {$programaId} no encontrado");
            return;
        }
        
        $this->info("Programa: {$programa->nombre}");
        
        // Todos los usuarios del programa
        $todosUsuarios = User::where('programa_id', $programaId)->get();
        $this->info("Total usuarios en el programa: {$todosUsuarios->count()}");
        
        // Usuarios con rol Docente
        $usuariosConRolDocente = User::whereHas('roles', function($q) {
            $q->where('name', 'Docente');
        })->where('programa_id', $programaId)->get();
        $this->info("Usuarios con rol Docente: {$usuariosConRolDocente->count()}");
        
        // Mostrar detalles de cada usuario
        $this->info("\nDetalles de usuarios:");
        foreach ($todosUsuarios as $usuario) {
            $roles = $usuario->roles->pluck('name')->implode(', ');
            $this->line("- ID: {$usuario->id}, Nombre: {$usuario->name} {$usuario->apellido1}, Roles: {$roles}");
        }
        
        $this->info("\nDetalles de usuarios con rol Docente:");
        foreach ($usuariosConRolDocente as $usuario) {
            $roles = $usuario->roles->pluck('name')->implode(', ');
            $this->line("- ID: {$usuario->id}, Nombre: {$usuario->name} {$usuario->apellido1}, Roles: {$roles}");
        }
    }
} 