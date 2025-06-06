<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProgramaController extends Controller
{
    /**
     * GET /api/programas/facultad/{facultadId}
     * Devuelve todos los programas cuyo campo `facultad_id` = {facultadId}.
     */
    public function getByFacultad($facultadId): JsonResponse
    {
        try {
            $programas = Programa::where('facultad_id', $facultadId)
                ->select('id', 'nombre', 'tipo')
                ->orderBy('nombre')
                ->get();

            Log::info("Programas encontrados para facultad {$facultadId}: " . $programas->count());

            return response()
                ->json($programas, 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            Log::error("Error cargando programas de facultad {$facultadId}: " . $e->getMessage(), [
                'exception' => $e,
                'trace'     => $e->getTraceAsString()
            ]);

            return response()->json([
                'error'   => 'No se pudieron cargar los programas.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}