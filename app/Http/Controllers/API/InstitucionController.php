<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Institucion;

class InstitucionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'departamento_id' => 'required|integer',
            'ciudad_id' => 'required|integer'
        ]);

        $institucion = Institucion::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Institución creada exitosamente',
            'data' => $institucion
        ]);
    }

    public function porCiudad($ciudadId)
    {
        $instituciones = Institucion::where('ciudad_id', $ciudadId)->get();

        return response()->json([
            'success' => true,
            'data' => $instituciones
        ]);
    }

    public function update(Request $request, $id)
    {
        $institucion = Institucion::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'departamento_id' => 'required|integer',
            'ciudad_id' => 'required|integer'
        ]);

        $institucion->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Institución actualizada exitosamente',
            'data' => $institucion
        ]);
    }

    public function destroy($id)
    {
        $institucion = Institucion::findOrFail($id);
        $institucion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Institución eliminada exitosamente'
        ]);
    }
} 