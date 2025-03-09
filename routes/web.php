<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Ruta principal redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación con verificación de email
Auth::routes(['verify' => true]);

// Ruta para el home del usuario autenticado, requiere autenticación y verificación de email
Route::get('/home', [HomeController::class, 'index'])
    ->name('home')
    ->middleware(['auth', 'verified']);

// Ruta para reenviar el correo de verificación
Route::post('/email/verify/resend', function (Request $request) {
    if ($request->user()) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('resent', true);
    }
    return redirect()->route('login');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// Rutas protegidas por middleware 'auth' y 'role:admin' (área de administración)
Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});

// Ruta para cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para recuperación de contraseña
Route::post('/password/email', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
