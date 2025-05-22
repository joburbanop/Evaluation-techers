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

        $user = auth()->user();

        // Si el usuario está autenticado pero no tiene el rol requerido
        if (!$user->hasRole($role)) {
            // Si está intentando acceder a una ruta de Filament
            if ($request->is('filament/*')) {
                // Verificar si el usuario tiene acceso al panel de Filament
                if ($user->canAccessPanel(\Filament\Facades\Filament::getCurrentPanel())) {
                    return $next($request);
                }
                return redirect('/login');
            }

            // Si está intentando acceder a una ruta de logout, login o auth
            if ($request->is('*/logout') || 
                $request->is('*/login') || 
                $request->is('*/auth/*')) {
                return $next($request);
            }
            
            // Para cualquier otra ruta, redirigir al login principal
            return redirect('/login');
        }

        return $next($request);
    }
} 