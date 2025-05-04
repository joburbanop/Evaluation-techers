<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $role = $user->getRoleNames()->first();

        Log::info('Redirección según rol', [
            'user_id' => $user->id,
            'role' => $role
        ]);

        // Redirigir directamente a las rutas de Filament
        return match(strtolower($role)) {
            'admin' => redirect('/admin'),
            'coordinador' => redirect('/coordinador'),
            'docente' => redirect('/docente'),
            default => redirect()->route('login')->with('error', 'No tienes un rol asignado.')
        };
    }
}
