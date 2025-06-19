<?php
namespace App\Http\Controllers;

use App\Models\Ciudad;
use Illuminate\Http\JsonResponse;

class CiudadController extends Controller
{
    public function getByDepartamento($departamentoId): JsonResponse
    {
        $ciudades = Ciudad::where('departamento_id', $departamentoId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(fn($c) => [
                'id'     => $c->id,
                'nombre' => $c->name,
            ]);

        return response()->json($ciudades);
    }
}