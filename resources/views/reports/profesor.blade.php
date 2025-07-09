<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Profesor - {{ $profesor->name }} {{ $profesor->apellido1 }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .professor-info {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .comparison-box {
            background-color: #e8f4fd;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .progress-bar {
            width: 100%;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            margin: 5px 0;
        }
        .progress-fill {
            height: 20px;
            background-color: #007bff;
            transition: width 0.3s ease;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Profesor</h1>
        <h2>{{ $profesor->name }} {{ $profesor->apellido1 }} {{ $profesor->apellido2 }}</h2>
        <p>Generado el: {{ $fecha_generacion }}</p>
    </div>

    <div class="professor-info">
        <h3>Información del Profesor</h3>
        <p><strong>Nombre:</strong> {{ $profesor->name }} {{ $profesor->apellido1 }} {{ $profesor->apellido2 }}</p>
        <p><strong>Email:</strong> {{ $profesor->email }}</p>
        @if($profesor->institution)
            <p><strong>Institución:</strong> {{ $profesor->institution->name }}</p>
        @endif
        @if($profesor->facultad)
            <p><strong>Facultad:</strong> {{ $profesor->facultad->nombre }}</p>
        @endif
        @if($profesor->programa)
            <p><strong>Programa:</strong> {{ $profesor->programa->nombre }}</p>
        @endif
    </div>

    @if($stats)
    <div class="section">
        <h3 class="section-title">Estadísticas Generales</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats->total_evaluaciones ?? 0 }}</div>
                <div class="stat-label">Total Evaluaciones</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($stats->promedio_score ?? 0, 2) }}</div>
                <div class="stat-label">Promedio General</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats->max_score ?? 0 }}</div>
                <div class="stat-label">Puntuación Máxima</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats->min_score ?? 0 }}</div>
                <div class="stat-label">Puntuación Mínima</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats->total_respuestas ?? 0 }}</div>
                <div class="stat-label">Total Respuestas</div>
            </div>
        </div>
    </div>
    @endif

    @if($areas && $areas->count() > 0)
    <div class="section">
        <h3 class="section-title">Rendimiento por Área</h3>
        <table>
            <thead>
                <tr>
                    <th>Área</th>
                    <th>Evaluaciones</th>
                    <th>Promedio</th>
                    <th>Máximo</th>
                    <th>Mínimo</th>
                    <th>Total Respuestas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($areas as $area)
                <tr>
                    <td>{{ $area['area_name'] }}</td>
                    <td>{{ $area['total_evaluaciones'] }}</td>
                    <td>{{ number_format($area['promedio_score'], 2) }}</td>
                    <td>{{ $area['max_score'] }}</td>
                    <td>{{ $area['min_score'] }}</td>
                    <td>{{ $area['total_respuestas'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($historial && $historial->count() > 0)
    <div class="section">
        <h3 class="section-title">Historial de Evaluaciones</h3>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Área</th>
                    <th>Puntuación</th>
                    <th>Total Respuestas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historial as $evaluacion)
                <tr>
                    <td>{{ $evaluacion['fecha'] }}</td>
                    <td>{{ $evaluacion['area'] }}</td>
                    <td>{{ number_format($evaluacion['score'], 2) }}</td>
                    <td>{{ $evaluacion['total_respuestas'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($comparacion)
    <div class="section">
        <h3 class="section-title">Comparación con el Programa</h3>
        <div class="comparison-box">
            <h4>Estadísticas del Programa</h4>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($comparacion->promedio_programa ?? 0, 2) }}</div>
                    <div class="stat-label">Promedio del Programa</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($comparacion->max_programa ?? 0, 2) }}</div>
                    <div class="stat-label">Máximo del Programa</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ number_format($comparacion->min_programa ?? 0, 2) }}</div>
                    <div class="stat-label">Mínimo del Programa</div>
                </div>
            </div>
            
            @if($stats && $comparacion)
            <div style="margin-top: 20px;">
                <h4>Comparación de Rendimiento</h4>
                @php
                    $profesorPromedio = $stats->promedio_score ?? 0;
                    $programaPromedio = $comparacion->promedio_programa ?? 0;
                    $diferencia = $profesorPromedio - $programaPromedio;
                    $porcentaje = $programaPromedio > 0 ? ($profesorPromedio / $programaPromedio) * 100 : 0;
                @endphp
                
                <p><strong>Promedio del Profesor:</strong> {{ number_format($profesorPromedio, 2) }}</p>
                <p><strong>Promedio del Programa:</strong> {{ number_format($programaPromedio, 2) }}</p>
                <p><strong>Diferencia:</strong> 
                    <span style="color: {{ $diferencia >= 0 ? 'green' : 'red' }};">
                        {{ $diferencia >= 0 ? '+' : '' }}{{ number_format($diferencia, 2) }}
                    </span>
                </p>
                <p><strong>Porcentaje del promedio del programa:</strong> {{ number_format($porcentaje, 1) }}%</p>
                
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ min($porcentaje, 100) }}%;"></div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if(isset($parametros['date_from']) || isset($parametros['date_to']))
    <div class="section">
        <h3 class="section-title">Filtros Aplicados</h3>
        <p><strong>Período:</strong> 
            @if(isset($parametros['date_from']))
                Desde: {{ \Carbon\Carbon::parse($parametros['date_from'])->format('d/m/Y') }}
            @endif
            @if(isset($parametros['date_to']))
                Hasta: {{ \Carbon\Carbon::parse($parametros['date_to'])->format('d/m/Y') }}
            @endif
        </p>
    </div>
    @endif

    <div class="footer">
        <p>Este reporte fue generado automáticamente por el sistema de evaluación de profesores.</p>
        <p>© {{ date('Y') }} Sistema de Evaluación de Profesores</p>
    </div>
</body>
</html> 