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
        // Si el usuario no está autenticado, redirigir al login principal
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Si el usuario está autenticado pero no tiene el rol requerido
        if (!auth()->user()->hasRole($role)) {
            // Si está intentando acceder a una ruta de logout, login o auth de Filament, permitir el acceso
            if ($request->is('*/logout') || 
                $request->is('*/login') || 
                $request->is('*/auth/*') ||
                $request->is('filament.*')) {
                return $next($request);
            }
            
            // Para cualquier otra ruta, redirigir al login principal
            return redirect('/login');
        }

        return $next($request);
    }
} 