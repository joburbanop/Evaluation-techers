<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Programa - {{ $programa->nombre }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #059669;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #059669;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            color: #059669;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
        }
        .stat-label {
            color: #64748b;
            font-size: 14px;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #f1f5f9;
            font-weight: bold;
            color: #374151;
        }
        .nivel-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        .nivel-a1 { background: #dc2626; }
        .nivel-a2 { background: #ea580c; }
        .nivel-b1 { background: #d97706; }
        .nivel-b2 { background: #65a30d; }
        .nivel-c1 { background: #059669; }
        .nivel-c2 { background: #0891b2; }
        .top-performer {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Evaluación por Programa</h1>
        <p><strong>Programa:</strong> {{ $programa->nombre }}</p>
        <p><strong>Facultad:</strong> {{ $facultad->nombre }}</p>
        <p><strong>Institución:</strong> {{ $facultad->institution->name }}</p>
        <p><strong>Tipo:</strong> {{ $programa->tipo }}</p>
        <p><strong>Fecha de Generación:</strong> {{ $fecha_generacion }}</p>
        @if(!empty($parametros))
            <p><strong>Filtros Aplicados:</strong> 
                @if(isset($parametros['date_from']))
                    Desde: {{ \Carbon\Carbon::parse($parametros['date_from'])->format('d/m/Y') }}
                @endif
                @if(isset($parametros['date_to']))
                    Hasta: {{ \Carbon\Carbon::parse($parametros['date_to'])->format('d/m/Y') }}
                @endif
            </p>
        @endif
    </div>

    <div class="section">
        <h2>Resumen General</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $total_evaluaciones }}</div>
                <div class="stat-label">Total de Evaluaciones</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $total_usuarios }}</div>
                <div class="stat-label">Usuarios Evaluados</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ count($area_stats) }}</div>
                <div class="stat-label">Áreas Evaluadas</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Resultados por Área de Competencia</h2>
        <table>
            <thead>
                <tr>
                    <th>Área</th>
                    <th>Evaluaciones</th>
                    <th>Promedio</th>
                    <th>Máximo</th>
                    <th>Mínimo</th>
                    <th>Distribución de Niveles</th>
                </tr>
            </thead>
            <tbody>
                @foreach($area_stats as $areaName => $stats)
                <tr>
                    <td><strong>{{ $areaName }}</strong></td>
                    <td>{{ $stats['total_evaluaciones'] }}</td>
                    <td>{{ $stats['promedio_score'] }}</td>
                    <td>{{ $stats['max_score'] }}</td>
                    <td>{{ $stats['min_score'] }}</td>
                    <td>
                        @foreach($stats['niveles'] as $nivel => $cantidad)
                            <span class="nivel-badge nivel-{{ strtolower($nivel) }}">{{ $nivel }}: {{ $cantidad }}</span>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Top 10 Mejores Evaluados</h2>
        <table>
            <thead>
                <tr>
                    <th>Posición</th>
                    <th>Docente</th>
                    <th>Puntaje</th>
                    <th>Fecha de Evaluación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top_evaluados as $index => $evaluado)
                <tr class="{{ $index < 3 ? 'top-performer' : '' }}">
                    <td><strong>{{ $index + 1 }}</strong></td>
                    <td>{{ $evaluado['user']->full_name }}</td>
                    <td><strong>{{ $evaluado['score'] }}</strong></td>
                    <td>{{ $evaluado['fecha']->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Análisis de Rendimiento</h2>
        @if($total_evaluaciones > 0)
            @php
                $promedioGeneral = $top_evaluados->avg('score');
                $maxScore = $top_evaluados->max('score');
                $minScore = $top_evaluados->min('score');
                $desviacion = $top_evaluados->std('score');
            @endphp
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ round($promedioGeneral, 2) }}</div>
                    <div class="stat-label">Promedio General</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $maxScore }}</div>
                    <div class="stat-label">Puntaje Máximo</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $minScore }}</div>
                    <div class="stat-label">Puntaje Mínimo</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ round($desviacion, 2) }}</div>
                    <div class="stat-label">Desviación Estándar</div>
                </div>
            </div>
        @else
            <p>No hay datos suficientes para realizar el análisis de rendimiento.</p>
        @endif
    </div>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Evaluación de Profesores</p>
        <p>Este documento contiene información confidencial y está destinado únicamente para uso interno</p>
    </div>
</body>
</html> 