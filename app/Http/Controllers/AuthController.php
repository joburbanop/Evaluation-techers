<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            dd($user->roles->pluck('name'));
            if ($user->hasRole('Administrador')) {
                return redirect('/admin/dashboard');
            } elseif ($user->hasRole('Coordinador')) {
                return redirect('/coordinador/dashboard');
            } elseif ($user->hasRole('Docente')) {
                return redirect('/docente/dashboard');
            }
            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

}
