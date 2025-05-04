<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || !$request->user()->hasRole($role)) {
            if ($request->user()) {
                // Si el usuario está autenticado pero no tiene el rol correcto,
                // redirigir al panel correspondiente a su rol
                $userRoles = $request->user()->roles->pluck('name')->toArray();
                if (in_array('admin', $userRoles)) {
                    return redirect('/admin');
                } elseif (in_array('coordinador', $userRoles)) {
                    return redirect('/coordinador');
                } elseif (in_array('docente', $userRoles)) {
                    return redirect('/docente');
                }
            }
            
            // Si el usuario no está autenticado, redirigir al login
            return redirect('/login');
        }

        return $next($request);
    }
} 