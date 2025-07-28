<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Profesor - {{ $previewData['profesor']['nombre_completo'] ?? 'N/A' }}</title>
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
            margin: 0 0 10px 0;
        }
        .header h2 {
            color: #1d4ed8;
            font-size: 1.8rem;
            margin: 0 0 15px 0;
        }
        .professor-info {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
        }
        .professor-info h3 {
            color: #3b82f6;
            margin-top: 0;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
        }
        .stat-title {
            color: #3b82f6;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 8px;
        }
        .stat-value {
            color: #1d4ed8;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .table-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        .section-title {
            color: #3b82f6;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            border-bottom: 2px solid #bae6fd;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background: #f0f9ff;
            color: #3b82f6;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #bae6fd;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        tr:hover {
            background-color: #f8fafc;
        }
        .comparison-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
        }
        .progress-bar {
            width: 100%;
            background-color: #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            margin: 10px 0;
            height: 20px;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            transition: width 0.3s ease;
        }
        .footer {
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
            margin-top: 2rem;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Profesor</h1>
        <h2>{{ $previewData['profesor']['nombre_completo'] ?? 'N/A' }}</h2>
        <div style="font-size: 1.1rem; margin-top: 0.5rem;">
            Fecha de Generación: {{ $previewData['fecha_generacion'] ?? now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="professor-info">
        <h3>Información del Profesor</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px;">
            <div>
                <p><strong>Nombre Completo:</strong> {{ $previewData['profesor']['nombre_completo'] ?? 'N/A' }}</p>
                <p><strong>Email:</strong> {{ $previewData['profesor']['email'] ?? 'N/A' }}</p>
                <p><strong>Estado:</strong> 
                    @if(isset($previewData['tests_completados']) && isset($previewData['total_tests']))
                        @if($previewData['tests_completados'] == $previewData['total_tests'])
                            <span style="color: #059669; font-weight: 600;">✓ Completado</span>
                        @else
                            <span style="color: #dc2626; font-weight: 600;">⚠ Pendiente</span>
                        @endif
                    @else
                        <span style="color: #dc2626; font-weight: 600;">⚠ Pendiente</span>
                    @endif
                </p>
            </div>
            <div>
                @if(isset($previewData['institution']))
                    <p><strong>Institución:</strong> {{ $previewData['institution']['name'] ?? 'N/A' }}</p>
                @endif
                @if(isset($previewData['facultad']))
                    <p><strong>Facultad:</strong> {{ $previewData['facultad']['nombre'] ?? 'N/A' }}</p>
                @endif
                @if(isset($previewData['programa']))
                    <p><strong>Programa:</strong> {{ $previewData['programa']['nombre'] ?? 'N/A' }}</p>
                @endif
                <p><strong>Fecha de Registro:</strong> {{ $previewData['profesor']['created_at'] ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="stats">
        <div class="stat">
            <div class="stat-title">Total de Evaluaciones</div>
            <div class="stat-value">{{ $previewData['total_evaluaciones'] ?? 0 }}</div>
        </div>
        <div class="stat">
            <div class="stat-title">Evaluaciones Realizadas</div>
            <div class="stat-value" style="color: #059669;">{{ $previewData['evaluaciones_realizadas'] ?? 0 }}</div>
        </div>
        <div class="stat">
            <div class="stat-title">Evaluaciones Pendientes</div>
            <div class="stat-value" style="color: #dc2626;">{{ $previewData['evaluaciones_pendientes'] ?? 0 }}</div>
        </div>
        <div class="stat">
            <div class="stat-title">Tests Completados</div>
            <div class="stat-value">{{ $previewData['tests_completados'] ?? 0 }}/{{ $previewData['total_tests'] ?? 0 }}</div>
        </div>
        <div class="stat">
            <div class="stat-title">Promedio General</div>
            <div class="stat-value">{{ number_format($previewData['promedio_general'] ?? 0, 2) }}</div>
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

    @if(isset($previewData['tests_asignados']) && count($previewData['tests_asignados']) > 0)
    <div class="table-container">
        <h2 class="section-title">Tests Asignados</h2>
        <table>
            <thead>
                <tr>
                    <th>Test</th>
                    <th>Estado</th>
                    <th>Fecha de Asignación</th>
                    <th>Fecha de Completado</th>
                    <th>Puntaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($previewData['tests_asignados'] as $test)
                <tr>
                    <td style="font-weight: 600; color: #1d4ed8;">{{ $test['nombre'] ?? 'N/A' }}</td>
                    <td style="text-align: center;">
                        @if($test['completado'] ?? false)
                            <span style="color: #059669; font-weight: 600;">✓ Completado</span>
                        @else
                            <span style="color: #dc2626; font-weight: 600;">⚠ Pendiente</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $test['fecha_asignacion'] ?? 'N/A' }}</td>
                    <td style="text-align: center;">{{ $test['fecha_completado'] ?? 'Pendiente' }}</td>
                    <td style="text-align: center; font-weight: 700; color: #3b82f6;">
                        {{ $test['puntaje'] ?? '0.00' }}/{{ $test['puntaje_maximo'] ?? '100.00' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(isset($previewData['resultados_por_area']) && count($previewData['resultados_por_area']) > 0)
    <div class="table-container">
        <h2 class="section-title">Rendimiento por Área</h2>
        <table>
            <thead>
                <tr>
                    <th>Área</th>
                    <th>Puntaje Obtenido</th>
                    <th>Puntaje Máximo</th>
                    <th>Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($previewData['resultados_por_area'] as $area)
                <tr>
                    <td style="font-weight: 600; color: #1d4ed8;">{{ $area['area_name'] }}</td>
                    <td style="text-align: center; font-weight: 700; color: #3b82f6;">{{ number_format($area['puntaje_obtenido'], 2) }}</td>
                    <td style="text-align: center;">{{ number_format($area['puntaje_maximo'], 2) }}</td>
                    <td style="text-align: center;">
                        <div style="font-weight: 700; color: #3b82f6;">{{ $area['porcentaje'] }}%</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $area['porcentaje'] }}%;"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        Reporte generado el {{ $previewData['fecha_generacion'] ?? now()->format('d/m/Y H:i:s') }}<br>
        Sistema de Evaluación de Profesores &copy; 2025
    </div>
</body>
</html> 