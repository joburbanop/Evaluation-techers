<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

// Ruta principal: redirige al panel de Filament si el usuario está autenticado, de lo contrario al login.
Route::get('/', function () {
    
    Log::info('Ruta princial');
    return redirect()->route('login');
});

// Rutas de autenticación con verificación de email
Auth::routes(['verify' => true]);

Route::get('/home', function () {
    Log::info('Ruta home ejecutada', ['user' => auth()->user()]);

    if (auth()->check()) {
        $user = auth()->user(); // Obtener el usuario autenticado

        // Redirigir a Filament según el rol
        switch ($user->getRoleNames()->first()) {
            case 'Administrador':
                return redirect('/admin');
            case 'Coordinador':
                return redirect('/coordinadorPanel');
            case 'Docente':
                return redirect('/docentePanel');
            default:
                // Si el rol no está en la lista, redirigir al home por defecto
                return redirect()->route('home');
        }
    }

    return redirect()->route('login'); // Si no está autenticado, redirigir al login
})->middleware(['auth', 'verified']);

// Ruta para reenviar el correo de verificación
Route::post('/email/verify/resend', function (Request $request) {
    Log::info('Ruta para reenviar el correo de verificación');
    if ($request->user()) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('resent', true);
    }
    return redirect()->route('login');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// Ruta para cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para recuperación de contraseña
Route::post('/password/reset', function (Request $request) {
    Log::info('recuperar o envio de correo ');
    $request->validate([
        'email' => 'required|email',
        'token' => 'required',
        'password' => 'required|min:8|confirmed',
    ]);
    
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password),
            ])->save();
        }
    );
    
    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');


