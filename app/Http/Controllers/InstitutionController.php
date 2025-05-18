<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class InstitutionController extends Controller
{
    public function getByCity($ciudadNombre): JsonResponse
    {
        try {
            // Normalizar el nombre de la ciudad para la búsqueda
            $ciudadNormalizada = trim($ciudadNombre);
            
            // Búsqueda más flexible usando LIKE y COLLATE
            $institutions = Institution::where(function($query) use ($ciudadNormalizada) {
                    $query->where('municipio_domicilio', 'LIKE', $ciudadNormalizada)
                          ->orWhere('municipio_domicilio', 'LIKE', str_replace('á', 'a', $ciudadNormalizada))
                          ->orWhere('municipio_domicilio', 'LIKE', str_replace('Á', 'A', $ciudadNormalizada));
                })
                ->select('id', 'name', 'academic_character', 'programas_vigentes')
                ->orderBy('name')
                ->get()
                ->map(function($inst) {
                    return [
                        'id' => $inst->id,
                        'name' => $inst->name,
                        'academic_character' => $inst->academic_character,
                        'programas_vigentes' => $inst->programas_vigentes
                    ];
                });

            // Log para debugging
            Log::info("Búsqueda de instituciones para ciudad: {$ciudadNormalizada}", [
                'total_encontradas' => $institutions->count(),
                'primeras_3' => $institutions->take(3)->toArray()
            ]);

            return response()->json($institutions, 200, [
                'Content-Type' => 'application/json; charset=utf-8',
            ], JSON_UNESCAPED_UNICODE);

        } catch (\Throwable $e) {
            Log::error("Error cargando instituciones de ciudad {$ciudadNombre}: " . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'No se pudieron cargar las instituciones.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}