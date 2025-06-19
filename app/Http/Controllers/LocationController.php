<?php

namespace App\Http\Controllers;

use App\Models\Ciudad;
use App\Models\Departamento;
use App\Models\Institution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Obtiene todos los departamentos.
     */
    public function getDepartamentos(): JsonResponse
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        return response()->json($departamentos);
    }

    /**
     * Obtiene las ciudades de un departamento.
     */
    public function getCiudades(Request $request): JsonResponse
    {
        $departmentId = $request->query('departmentId');
        $ciudades = Ciudad::where('departamento_id', $departmentId)
            ->orderBy('nombre')
            ->get();
        return response()->json($ciudades);
    }

    /**
     * Obtiene las instituciones de una ciudad.
     */
    public function getInstituciones(Request $request): JsonResponse
    {
        $ciudadId = $request->query('ciudad_id');
        $instituciones = Institution::where('ciudad_id', $ciudadId)
            ->select('id', 'name', 'tipo')
            ->orderBy('name')
            ->get();
        return response()->json($instituciones);
    }
} 