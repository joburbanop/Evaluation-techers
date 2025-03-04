<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordResetController;

// Ruta principal redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación proporcionadas por Laravel
Auth::routes();

// Ruta para el home del usuario autenticado
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rutas protegidas por los middleware 'auth' y 'role:admin' (área de administración)
Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});

// Rutas de autenticación manual (si las necesitas, como login y logout)
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para recuperación de contraseña
Route::post('/password/email', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.update');
