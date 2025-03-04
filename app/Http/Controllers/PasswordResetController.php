<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Lang;

class PasswordResetController extends Controller
{
    // Método para enviar el enlace de restablecimiento de contraseña
    public function sendResetLink(Request $request)
    {
        // Validación de la dirección de correo electrónico
        $request->validate(['email' => 'required|email']);
        
        // Enviar el enlace de restablecimiento
        $status = Password::sendResetLink($request->only('email'));

        // Verifica si se envió correctamente el enlace y devuelve un mensaje adecuado
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', Lang::get($status))
            : back()->withErrors(['email' => Lang::get($status)]);
    }

    // Método para restablecer la contraseña
    public function resetPassword(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'token' => 'required'
        ]);

        // Intento de restablecer la contraseña
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Actualiza la contraseña del usuario
                $user->forceFill(['password' => bcrypt($password)])->save();
            }
        );

        // Verifica si el restablecimiento fue exitoso
        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('status', 'Contraseña restablecida con éxito.')
            : back()->withErrors(['email' => 'Error al restablecer la contraseña.']);
    }
}
