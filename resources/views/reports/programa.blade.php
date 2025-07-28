<style>
    .program-report {
        font-family: Arial, sans-serif;
        color: #333;
        line-height: 1.6;
    }
    .program-header {
        text-align: center;
        border-bottom: 3px solid #93c5fd;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    .program-header h1 {
        color: #3b82f6;
        font-size: 2.5rem;
        margin: 0;
    }
    .program-header h2 {
        color: #1d4ed8;
        font-size: 1.5rem;
        margin: 0;
    }
    .program-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        justify-content: center;
        margin-bottom: 2rem;
    }
    .program-stat {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 10px;
        padding: 1.5rem 2rem;
        min-width: 220px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
    }
    .program-stat-title {
        color: #3b82f6;
        font-weight: bold;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    .program-stat-value {
        font-size: 2.2rem;
        font-weight: bold;
        color: #1d4ed8;
    }
    .program-table-container {
        overflow-x: auto;
        margin-bottom: 2rem;
    }
    .program-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
    }
    .program-table th, .program-table td {
        padding: 1rem;
        text-align: center;
        border-bottom: 1px solid #bae6fd;
    }
    .program-table th {
        background: #f0f9ff;
        color: #3b82f6;
        font-weight: bold;
    }
    .program-table tr:last-child td {
        border-bottom: none;
    }
</style>

<div class="program-report">
    {{-- Botón de descarga --}}
    <div style="text-align: right; margin-bottom: 20px;">
        <a
            href="{{ auth()->user()->hasRole('Coordinador') ? route('coordinador.reports.pdf') : route('admin.reports.pdf') }}?tipo_reporte=programa&entidad_id={{ $previewData['programa']['id'] ?? '' }}&redirect=1"
            target="_blank"
            onclick="if(!confirm('¿Está seguro de que desea generar el reporte?')) return false; this.style.pointerEvents='none'; this.innerHTML='🔄 Generando...'; setTimeout(() => { @if(auth()->user()->hasRole('Coordinador')) window.location.href='{{ route('coordinador.reports.index') }}'; @else window.location.href='/admin/reports'; @endif }, 2000);"
            style="background-color: #3b82f6; color: white; padding: 12px 20px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 14px;"
        >
            ⬇️ Generar Reporte
        </a>
    </div>

    <div class="program-header">
        <h1>Reporte de Programa</h1>
        <h2>{{ $previewData['entidad']['nombre'] ?? 'N/A' }}</h2>
        <div style="font-size: 1.1rem; margin-top: 0.5rem;">
            Facultad: {{ $previewData['facultad']['nombre'] ?? 'N/A' }}<br>
            Institución: {{ $previewData['facultad']['institution']['name'] ?? 'N/A' }}<br>
            Fecha de Aplicación: {{ $previewData['fecha_aplicacion'] ?? 'N/A' }}
        </div>
    </div>

    <div class="program-stats">
        <div class="program-stat">
            <div class="program-stat-title">Total de Profesores</div>
            <div class="program-stat-value">{{ $previewData['total_profesores'] ?? 0 }}</div>
        </div>
        <div class="program-stat">
            <div class="program-stat-title">Profesores Completados</div>
            <div class="program-stat-value" style="color: #059669;">{{ $previewData['total_profesores_completados'] ?? 0 }}</div>
        </div>
        <div class="program-stat">
            <div class="program-stat-title">Profesores Pendientes</div>
            <div class="program-stat-value" style="color: #dc2626;">{{ $previewData['total_profesores_pendientes'] ?? 0 }}</div>
        </div>
        <div class="program-stat">
            <div class="program-stat-title">Promedio del Programa</div>
            <div class="program-stat-value">{{ number_format($previewData['promedio_programa'] ?? 0, 2) }}</div>
        </div>
        <div class="program-stat">
            <div class="program-stat-title">Puntuación Máxima</div>
            <div class="program-stat-value" style="color: #059669;">{{ $previewData['puntuacion_maxima'] ?? 0 }}</div>
        </div>
        <div class="program-stat">
            <div class="program-stat-title">Puntuación Mínima</div>
            <div class="program-stat-value" style="color: #dc2626;">{{ $previewData['puntuacion_minima'] ?? 0 }}</div>
        </div>
    </div>

    <div class="program-table-container">
        <h2 style="color: #3b82f6; text-align: left; margin-bottom: 1rem;">Resultados por Profesor (Ordenados de Mayor a Menor)</h2>
        <table class="program-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Pos.</th>
                    <th style="width: 200px;">Nombre Completo</th>
                    <th style="width: 120px;">Estado</th>
                    <th style="width: 120px;">Promedio General</th>
                    @if(isset($previewData['area_stats']) && count($previewData['area_stats']) > 0)
                        @foreach($previewData['area_stats'] as $area)
                            <th style="width: 150px;">{{ is_array($area) ? $area['area_name'] : $area->area_name }}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($previewData['resultados_por_profesor'] as $index => $resultado)
                    <tr>
                        <td style="text-align: center; font-weight: 600; color: #3b82f6;">{{ $index + 1 }}</td>
                        <td style="font-weight: 600; color: #1d4ed8;">{{ $resultado['nombre_completo'] }}</td>
                        <td>
                            @if(isset($resultado['ha_completado_todos']) && $resultado['ha_completado_todos'])
                                <span style="color: #059669; font-weight: 600;">✓ Completado</span>
                                <div style="font-size: 0.8rem; color: #666;">{{ $resultado['tests_completados'] }}/{{ $resultado['total_tests'] }} tests</div>
                            @else
                                <span style="color: #dc2626; font-weight: 600;">⚠ Pendiente</span>
                                <div style="font-size: 0.8rem; color: #666;">{{ $resultado['tests_completados'] }}/{{ $resultado['total_tests'] }} tests</div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div style="font-weight: 700; font-size: 1.1rem; color: #3b82f6;">{{ number_format($resultado['promedio_general'], 2) }}</div>
                        </td>
                        @if(isset($previewData['area_stats']) && count($previewData['area_stats']) > 0)
                            @foreach($previewData['area_stats'] as $area)
                                @php
                                    $areaResultado = $resultado['resultados_por_area']->firstWhere('area_name', is_array($area) ? $area['area_name'] : $area->area_name);
                                @endphp
                                <td style="text-align: center;">
                                    @if($areaResultado)
                                        <div style="font-weight: 700; color: #3b82f6; font-size: 1rem;">{{ number_format($areaResultado['puntaje'], 2) }}/{{ $areaResultado['total_posible'] }}</div>
                                    @else
                                        <span style="color: #999; font-style: italic;">0.00/100</span>
                                    @endif
                                </td>
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> 