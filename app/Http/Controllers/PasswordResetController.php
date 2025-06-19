<?php

namespace App\Http\Controllers;
use App\Notifications\PasswordResetSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Lang;

class PasswordResetController extends Controller
{
    // Método para enviar el enlace de restablecimiento de contraseña
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Te hemos enviado un enlace de recuperación a tu correo.');
        } else {
            return back()->with('error', 'No pudimos enviar el enlace. Verifica que el correo esté registrado.');
        }
    }

    // Método para restablecer la contraseña
    public function resetPassword(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required'
        ]);

        // Intento de restablecer la contraseña
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Actualiza la contraseña del usuario
                $user->forceFill(['password' => bcrypt($password)])->save();

                // Envía una notificación al usuario sobre el cambio de contraseña
                $user->notify(new \App\Notifications\PasswordResetSuccess());
            }
        );

        // Verifica si el restablecimiento fue exitoso
        if ($status === Password::PASSWORD_RESET) {
            return redirect('/home')->with('success', 'Tu contraseña ha sido restablecida con éxito. Bienvenido de nuevo.');
        }

        return back()->withErrors(['email' => 'Error al restablecer la contraseña.']);
    }
}
