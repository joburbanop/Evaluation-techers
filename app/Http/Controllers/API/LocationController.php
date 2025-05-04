<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $baseUrl = 'https://api-colombia.com/api/v1';

    public function getDepartamentos()
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->get($this->baseUrl . '/Department');
            
            if ($response->successful()) {
                $departamentos = collect($response->json())->map(function($dep) {
                    return [
                        'id' => $dep['id'],
                        'name' => $dep['name']
                    ];
                })->values();
                
                return response()->json($departamentos);
            }
            
            return response()->json(['error' => 'Error al cargar los departamentos'], 500);
        } catch (\Exception $e) {
            \Log::error('Error al conectar con la API de Colombia: ' . $e->getMessage());
            return response()->json(['error' => 'Error al conectar con el servicio'], 500);
        }
    }

    public function getCiudadesPorDepartamento($departamentoId)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->get($this->baseUrl . "/Department/{$departamentoId}/cities");
            
            if ($response->successful()) {
                $ciudades = collect($response->json())->map(function($city) {
                    return [
                        'id' => $city['id'],
                        'name' => $city['name']
                    ];
                })->values();
                
                return response()->json($ciudades);
            }
            
            return response()->json(['error' => 'Error al cargar las ciudades'], 500);
        } catch (\Exception $e) {
            \Log::error('Error al conectar con la API de Colombia: ' . $e->getMessage());
            return response()->json(['error' => 'Error al conectar con el servicio'], 500);
        }
    }
} 