<?php

namespace App\Http\Controllers;

use App\Models\Facultad;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class FacultadController extends Controller
{
    /**
     * GET /api/facultades/institucion/{institutionId}
     * Devuelve todas las facultades cuyo institution_id coincida con {institutionId}.
     */
    public function getByInstitution($institutionId): JsonResponse
    {
        try {
            // Consulta a la tabla 'facultades' donde institution_id = $institutionId
            $facultades = Facultad::where('institution_id', $institutionId)
                ->select('id', 'nombre')
                ->orderBy('nombre')
                ->get();

            // Logueamos para depuración (opcional)
            Log::info("Facultades encontradas para institución {$institutionId}: " . $facultades->count());

            return response()
                ->json($facultades, 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            Log::error("Error cargando facultades de institución {$institutionId}: " . $e->getMessage(), [
                'exception' => $e,
                'trace'     => $e->getTraceAsString()
            ]);

            return response()->json([
                'error'   => 'No se pudieron cargar las facultades.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}