<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Http\Request;

// Ruta principal: redirige al panel de Filament si el usuario está autenticado, de lo contrario al login.
Route::get('/', function () {
    return auth()->check() ? redirect('/admin') : redirect()->route('login');
});

// Rutas de autenticación con verificación de email
Auth::routes(['verify' => true]);

// Redirige la ruta "/home" al panel de Filament para usuarios autenticados
Route::get('/home', function () {
    return redirect('/admin');
})->middleware(['auth', 'verified']);

// Ruta para reenviar el correo de verificación
Route::post('/email/verify/resend', function (Request $request) {
    if ($request->user()) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('resent', true);
    }
    return redirect()->route('login');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// Ruta para cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin/ogout', function () {
    return redirect()->route('login');
})->name('filament.admin.auth.login');

// Rutas para recuperación de contraseña
Route::post('/password/email', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.update');