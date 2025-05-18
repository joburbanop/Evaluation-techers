<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;           // ← IMPORTA EL FACADE Route
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\InstitutionController;

// Rutas públicas para cargar datos desde la base de datos
Route::get('/departamentos', [DepartamentoController::class, 'index']);

// Cambié los placeholders para que sean IDs y coincidan con los parámetros de tus controladores:
Route::get('/ciudades/{departamentoId}', [CiudadController::class, 'getByDepartamento']);
Route::get('/instituciones/ciudad/{ciudadId}', [InstitutionController::class, 'getByCity']);