<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Normalizo el nombre del rol para coincidir con los almacenados (mayúscula inicial)
        $normalizedRole = ucfirst(strtolower($role));
        // Depuración: registro información sobre el chequeo de roles
        Log::debug('EnsureUserHasRole', [
            'path' => $request->path(),
            'user_id' => $request->user()?->id,
            'role_param' => $role,
            'normalized_role' => $normalizedRole,
            'user_has_role_original' => $request->user()?->hasRole($role),
            'user_has_role' => $request->user()?->hasRole($normalizedRole),
        ]);
        if (!$request->user() || !$request->user()->hasRole($normalizedRole)) {
            if ($request->user()) {
                // Si el usuario está autenticado pero no tiene el rol correcto,
                // redirigir al panel correspondiente según su rol real
                if ($request->user()->hasRole('Administrador')) {
                    return redirect('/admin');
                } elseif ($request->user()->hasRole('Coordinador')) {
                    return redirect('/coordinador');
                } elseif ($request->user()->hasRole('Docente')) {
                    return redirect('/docente');
                }
            }
            
            // Si el usuario no está autenticado, redirigir al login
            return redirect('/login');
        }

        return $next($request);
    }
} 