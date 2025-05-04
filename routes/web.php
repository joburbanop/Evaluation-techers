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
use App\Http\Controllers\Panel\DocenteController;
use App\Http\Controllers\Panel\CoordinadorController;
use App\Http\Controllers\Panel\AdminController;

// Ruta raíz: redirige al panel correspondiente según el rol del usuario o al login
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        if ($user->hasRole('Administrador')) {
            return redirect('/admin');
        } elseif ($user->hasRole('Coordinador')) {
            return redirect('/coordinador');
        } elseif ($user->hasRole('Docente')) {
            return redirect('/docente');
        }
    }

    return redirect()->route('login');
});

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

// Ruta para cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para recuperación de contraseña
Route::post('/password/reset', function (Request $request) {
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

// Rutas de redirección para los paneles
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/coordinador', [CoordinadorController::class, 'dashboard'])->name('coordinador.dashboard');
    Route::get('/docente', [DocenteController::class, 'dashboard'])->name('docente.dashboard');
});

// Rutas de Filament
Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::get('/docente/dashboard', [DocenteController::class, 'dashboard'])->name('filament.docente.pages.dashboard');
});


