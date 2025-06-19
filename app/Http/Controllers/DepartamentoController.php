<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\JsonResponse;

class DepartamentoController extends Controller
{
    public function index(): JsonResponse
    {
        // Traigo id+name de mi tabla departamentos
        $departamentos = Departamento::select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(fn($d) => [
                'id'     => $d->id,
                'nombre' => $d->name,
            ]);

        return response()
            ->json(
                $departamentos,
                200,
                ['Content-Type' => 'application/json; charset=utf-8'],
                JSON_UNESCAPED_UNICODE
            );
    }
}