<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Exceptions\UnauthorizedException;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si el usuario no está autenticado, redirigir al login principal
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Validar que el rol no sea nulo
        if (empty($role)) {
            Log::error('Rol no especificado en el middleware EnsureUserHasRole');
            return redirect('/login');
        }

        try {
            // Si el usuario está autenticado pero no tiene el rol requerido
            if (!$user->hasRole($role)) {
                // Si está intentando acceder a una ruta de Filament
                if ($request->is('filament/*')) {
                    // Verificar si el usuario tiene acceso al panel de Filament
                    $panel = \Filament\Facades\Filament::getCurrentPanel();
                    if ($panel && $user->canAccessPanel($panel)) {
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
                
                throw new UnauthorizedException(403, 'No tienes el rol requerido para acceder a esta página.');
            }

            return $next($request);
        } catch (UnauthorizedException $e) {
            Log::warning('Acceso no autorizado', [
                'user_id' => $user->id,
                'role' => $role,
                'path' => $request->path()
            ]);
            return redirect('/login');
        } catch (\Exception $e) {
            Log::error('Error en EnsureUserHasRole: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'role' => $role,
                'path' => $request->path()
            ]);
            return redirect('/login');
        }
    }
} 