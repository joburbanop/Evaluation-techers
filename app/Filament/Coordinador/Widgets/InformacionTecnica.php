<?php

namespace App\Filament\Coordinador\Widgets;

use App\Models\TestAssignment;
use App\Models\User;
use App\Models\Test;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InformacionTecnica extends Widget
{
    protected int|string|array $columnSpan = 'full';

    protected static string $view = 'filament.coordinador.widgets.informacion-tecnica';

    public function getViewData(): array
    {
        $user = Auth::user();
        
        // Estadísticas técnicas
        $totalTests = Test::count();
        $testsActivos = Test::where('is_active', true)->count();
        
        $evaluacionesRecientes = TestAssignment::whereHas('user', function($q) use ($user) {
            $q->where('facultad_id', $user->facultad_id);
        })->where('created_at', '>=', now()->subDays(7))->count();
        
        $evaluacionesCompletadas = TestAssignment::whereHas('user', function($q) use ($user) {
            $q->where('facultad_id', $user->facultad_id);
        })->where('status', 'completed')->count();
        
        $ultimaActualizacion = TestAssignment::whereHas('user', function($q) use ($user) {
            $q->where('facultad_id', $user->facultad_id);
        })->latest()->first()?->updated_at;
        
        return [
            'totalTests' => $totalTests,
            'testsActivos' => $testsActivos,
            'evaluacionesRecientes' => $evaluacionesRecientes,
            'evaluacionesCompletadas' => $evaluacionesCompletadas,
            'ultimaActualizacion' => $ultimaActualizacion,
            'facultad' => $user->facultad,
            'institution' => $user->institution,
        ];
    }
} 