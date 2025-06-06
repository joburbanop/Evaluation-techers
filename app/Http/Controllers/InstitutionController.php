<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class InstitutionController extends Controller
{
    /**
     * GET /api/instituciones/ciudad/{ciudadId}
     *
     * Recibe el ID de la ciudad; primero la busca en la tabla `ciudades`
     * y luego filtra las instituciones cuyo campo `municipio_domicilio`
     * coincida o contenga el nombre de la ciudad.
     */
    public function getByCity($ciudadId): JsonResponse
    {
        try {
            // Buscar la ciudad por ID y obtener su nombre
            $ciudad = Ciudad::findOrFail($ciudadId);
            $ciudadNombre = trim($ciudad->nombre);

            $institutions = Institution::where('municipio_domicilio', $ciudadNombre)
                ->select('id', 'name', 'academic_character', 'programas_vigentes')
                ->orderBy('name')
                ->get()
                ->map(function($inst) {
                    return [
                        'id'                  => $inst->id,
                        'name'                => $inst->name,
                        'academic_character'  => $inst->academic_character,
                        'programas_vigentes'  => $inst->programas_vigentes,
                    ];
                });

            // Debug en el log: cuántas instituciones encontró y las primeras 3
            Log::info("Instituciones encontradas para ciudad ID {$ciudadId}", [
                'total_encontradas' => $institutions->count(),
                'primeras_3'        => $institutions->take(3)->toArray(),
            ]);

            // Devolvemos JSON con las instituciones
            return response()
                   ->json($institutions, 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Ciudad no encontrada
            Log::warning("Ciudad no encontrada con ID {$ciudadId}");
            return response()->json([
                'error'   => 'Ciudad no encontrada.',
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Throwable $e) {
            // Si ocurrió cualquier otro error, devolvemos 500
            Log::error("Error cargando instituciones de ciudad ID {$ciudadId}: " . $e->getMessage(), [
                'exception' => $e,
                'trace'     => $e->getTraceAsString()
            ]);

            return response()->json([
                'error'   => 'No se pudieron cargar las instituciones.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getByMunicipio($municipio): JsonResponse
{
    try {
        // Filtrar instituciones cuyo campo municipio_domicilio coincida exactamente con $municipio
        $institutions = Institution::where('municipio_domicilio', $municipio)
            ->select('id', 'name', 'academic_character', 'programas_vigentes')
            ->orderBy('name')
            ->get()
            ->map(function($inst) {
                return [
                    'id'                 => $inst->id,
                    'name'               => $inst->name,
                    'academic_character' => $inst->academic_character,
                    'programas_vigentes' => $inst->programas_vigentes,
                ];
            });

        // (Opcional) Log para verificar resultados
        Log::info("Instituciones encontradas para municipio '{$municipio}'", [
            'total_encontradas' => $institutions->count(),
            'primeras_3'       => $institutions->take(3)->toArray(),
        ]);

        return response()
            ->json($institutions, 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    } catch (\Throwable $e) {
        Log::error("Error cargando instituciones para municipio '{$municipio}': " . $e->getMessage(), [
            'exception' => $e,
            'trace'     => $e->getTraceAsString()
        ]);

        return response()->json([
            'error'   => 'No se pudieron cargar las instituciones.',
            'message' => $e->getMessage(),
        ], 500);
    }
}
}