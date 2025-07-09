<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Universidad - {{ $institution->name }}</title>
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
        .institution-info {
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
        <h1>Reporte de Universidad</h1>
        <h2>{{ $institution->name }}</h2>
        <p>Generado el: {{ $fecha_generacion }}</p>
    </div>

    <div class="institution-info">
        <h3>Información de la Institución</h3>
        <p><strong>Nombre:</strong> {{ $institution->name }}</p>
        <p><strong>Carácter Académico:</strong> {{ $institution->academic_character ?? 'No especificado' }}</p>
        <p><strong>Departamento:</strong> {{ $institution->departamento_domicilio ?? 'No especificado' }}</p>
        <p><strong>Municipio:</strong> {{ $institution->municipio_domicilio ?? 'No especificado' }}</p>
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
                <div class="stat-number">{{ $stats->total_usuarios ?? 0 }}</div>
                <div class="stat-label">Total Usuarios</div>
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
        </div>
    </div>
    @endif

    @if($facultades && $facultades->count() > 0)
    <div class="section">
        <h3 class="section-title">Estadísticas por Facultad</h3>
        <table>
            <thead>
                <tr>
                    <th>Facultad</th>
                    <th>Evaluaciones</th>
                    <th>Usuarios</th>
                    <th>Promedio</th>
                    <th>Máximo</th>
                    <th>Mínimo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facultades as $facultad)
                <tr>
                    <td>{{ $facultad->facultad_nombre }}</td>
                    <td>{{ $facultad->total_evaluaciones }}</td>
                    <td>{{ $facultad->total_usuarios }}</td>
                    <td>{{ number_format($facultad->promedio_score, 2) }}</td>
                    <td>{{ $facultad->max_score }}</td>
                    <td>{{ $facultad->min_score }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($areas && $areas->count() > 0)
    <div class="section">
        <h3 class="section-title">Estadísticas por Área</h3>
        <table>
            <thead>
                <tr>
                    <th>Área</th>
                    <th>Evaluaciones</th>
                    <th>Promedio</th>
                    <th>Máximo</th>
                    <th>Mínimo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($areas as $area)
                <tr>
                    <td>{{ $area->area_name }}</td>
                    <td>{{ $area->total_evaluaciones }}</td>
                    <td>{{ number_format($area->promedio_score, 2) }}</td>
                    <td>{{ $area->max_score }}</td>
                    <td>{{ $area->min_score }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($top_profesores && $top_profesores->count() > 0)
    <div class="section">
        <h3 class="section-title">Top 10 Mejores Profesores</h3>
        <table>
            <thead>
                <tr>
                    <th>Posición</th>
                    <th>Profesor</th>
                    <th>Facultad</th>
                    <th>Programa</th>
                    <th>Promedio</th>
                    <th>Evaluaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($top_profesores as $index => $profesor)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $profesor->user_name }} {{ $profesor->apellido1 }} {{ $profesor->apellido2 }}</td>
                    <td>{{ $profesor->facultad_nombre }}</td>
                    <td>{{ $profesor->programa_nombre }}</td>
                    <td>{{ number_format($profesor->promedio_general, 2) }}</td>
                    <td>{{ $profesor->total_evaluaciones }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
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