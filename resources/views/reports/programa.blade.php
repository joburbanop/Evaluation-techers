<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Programa - {{ $previewData['entidad']['nombre'] ?? 'N/A' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #93c5fd;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #3b82f6;
            font-size: 2.5rem;
            margin: 0;
        }
        .header h2 {
            color: #1d4ed8;
            font-size: 1.5rem;
            margin: 0;
        }
        .stats {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .stat {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            padding: 1.5rem 2rem;
            min-width: 220px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
        }
        .stat-title {
            color: #3b82f6;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .stat-value {
            font-size: 2.2rem;
            font-weight: bold;
            color: #1d4ed8;
        }
        .table-container {
            overflow-x: auto;
            margin-bottom: 2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
        }
        th, td {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid #bae6fd;
        }
        th {
            background: #f0f9ff;
            color: #3b82f6;
            font-weight: bold;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .footer {
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Programa</h1>
        <h2>{{ $previewData['entidad']['nombre'] ?? 'N/A' }}</h2>
        <div style="font-size: 1.1rem; margin-top: 0.5rem;">
            Facultad: {{ $previewData['facultad']['nombre'] ?? 'N/A' }}<br>
            Institución: {{ $previewData['facultad']['institution']['name'] ?? 'N/A' }}<br>
            Fecha de Aplicación: {{ $previewData['fecha_aplicacion'] ?? 'N/A' }}
        </div>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="stat-title">Total de Profesores</div>
            <div class="stat-value">{{ $previewData['total_profesores'] ?? 0 }}</div>
        </div>
        <div class="stat">
            <div class="stat-title">Profesores Completados</div>
            <div class="stat-value" style="color: #059669;">{{ $previewData['total_profesores_completados'] ?? 0 }}</div>
        </div>
        <div class="stat">
            <div class="stat-title">Profesores Pendientes</div>
            <div class="stat-value" style="color: #dc2626;">{{ $previewData['total_profesores_pendientes'] ?? 0 }}</div>
        </div>
        <div class="stat">
            <div class="stat-title">Promedio del Programa</div>
            <div class="stat-value">{{ number_format($previewData['promedio_programa'] ?? 0, 2) }}</div>
        </div>
        <div class="stat">
            <div class="stat-title">Puntuación Máxima</div>
            <div class="stat-value" style="color: #059669;">{{ $previewData['puntuacion_maxima'] ?? 0 }}</div>
        </div>
        <div class="stat">
            <div class="stat-title">Puntuación Mínima</div>
            <div class="stat-value" style="color: #dc2626;">{{ $previewData['puntuacion_minima'] ?? 0 }}</div>
        </div>
    </div>

    <div class="table-container">
        <h2 style="color: #3b82f6; text-align: left; margin-bottom: 1rem;">Resultados por Profesor (Ordenados de Mayor a Menor)</h2>
        <table>
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

    <div class="footer">
        Reporte generado el {{ $previewData['fecha_generacion'] ?? now()->format('d/m/Y H:i:s') }}<br>
        Sistema de Evaluación de Profesores &copy; 2025
    </div>
</body>
</html> 