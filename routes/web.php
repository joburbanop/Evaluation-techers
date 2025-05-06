<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\RiasController;

// Ruta de inicio que redirige al dashboard según el rol
Route::get('/', [HomeController::class, 'index'])->name('home');

// Ruta principal: redirige al panel correspondiente según el rol del usuario
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->hasRole('Administrador')) {
            return redirect('/admin');
        } elseif ($user->hasRole('Coordinador')) {
            return redirect('/coordinador');
        } elseif ($user->hasRole('Docente')) {
            return redirect('/docente');
        } else {
            return redirect('/')->with('error', 'No tienes un rol asignado.');
        }
    })->name('dashboard');
});

// Interceptar rutas de login específicas y redirigir a login principal
Route::get('/admin/login', function () {
    return redirect('/login');
});

Route::get('/coordinador/login', function () {
    return redirect('/login');
});

Route::get('/docente/login', function () {
    return redirect('/login');
});

// Rutas para manejar el logout de cada panel
Route::post('/admin/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('filament.admin.auth.logout');

Route::post('/coordinador/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('filament.coordinador.auth.logout');

Route::post('/docente/logout', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('filament.docente.auth.logout');

// Rutas de restablecimiento de contraseña
Route::get('/password/reset/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->name('password.reset');

Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
    ->name('password.update');

// Rutas de autenticación con verificación de email
Auth::routes(['verify' => true]);

// Rutas de verificación de correo
Route::get('/email/verify', [VerificationController::class, 'show'])
    ->middleware('auth')
    ->name('verification.notice');

// ruta para verificar el correo
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

// ruta para reenviar el correo de verificacion
Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// Rutas de perfil
Route::middleware(['auth'])->group(function () {
    Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.update-email');
});

// Las rutas de los paneles /admin, /coordinador y /docente son gestionadas automáticamente por Filament mediante los PanelProviders
// Por tanto, no es necesario definir rutas manuales aquí.


