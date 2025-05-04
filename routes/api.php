<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\InstitucionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas para ubicación
Route::get('/departamentos', [LocationController::class, 'getDepartamentos']);
Route::get('/departamentos/{departamentoId}/ciudades', [LocationController::class, 'getCiudadesPorDepartamento']);

// Rutas para instituciones
Route::post('/instituciones', [InstitucionController::class, 'store']);
Route::get('/instituciones/ciudad/{ciudad_id}', [InstitucionController::class, 'porCiudad']);
Route::put('/instituciones/{id}', [InstitucionController::class, 'update']);
Route::delete('/instituciones/{id}', [InstitucionController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); 