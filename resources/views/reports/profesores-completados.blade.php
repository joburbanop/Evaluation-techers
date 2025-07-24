<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Participación en Evaluación de Competencias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background-color: white;
            color: #000000;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000000;
        }
        .header h1 {
            color: #000000;
            margin: 0;
            font-size: 20px;
        }
        .header h2 {
            color: #000000;
            margin: 2px 0 0 0;
            font-size: 14px;
        }

        .table-container {
            margin-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 8px;
        }
        th, td {
            border: 1px solid #000000;
            padding: 2px 3px;
            text-align: left;
            word-wrap: break-word;
            max-width: 80px;
            color: #000000;
        }
        th {
            background-color: #000000;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #ffffff;
        }
        tr:nth-child(odd) {
            background-color: #f8f8f8;
        }
        tr:hover {
            background-color: #f0f0f0;
        }
        .status-completado {
            background-color: #10b981;
            color: white;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
        }
        .status-pendiente {
            background-color: #f59e0b;
            color: white;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            color: #000000;
            font-size: 10px;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
        .filtro-info {
            background-color: #ffffff;
            padding: 5px;
            border-radius: 2px;
            margin-bottom: 10px;
            border: 1px solid #000000;
        }
        .filtro-info h3 {
            margin: 0 0 5px 0;
            color: #000000;
            font-size: 14px;
        }
        .filtro-info p {
            margin: 2px 0;
            color: #000000;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reporte de Participación en Evaluación de Competencias</h1>
            <h2>Evaluación de Competencias Digitales</h2>
            <p>Generado el {{ $data['fecha_generacion'] }}</p>
        </div>

        <div class="filtro-info">
            <h3>Información del Reporte</h3>
            <p><strong>Filtro aplicado:</strong> 
                @switch($data['filtro_aplicado'])
                    @case('completados')
                        Solo profesores que participaron en la evaluación
                        @if($data['profesores_completados'] == 0)
                            <br><em style="color: #000000;">Nota: No hay profesores que hayan completado evaluaciones en el período seleccionado. Se muestran todos los profesores.</em>
                        @endif
                        @break
                    @case('pendientes')
                        Solo profesores pendientes de participar
                        @break
                    @case('todos')
                        Todos los profesores
                        @break
                    @default
                        {{ ucfirst($data['filtro_aplicado']) }}
                @endswitch
            </p>
            @if(isset($data['parametros']['date_from']) || isset($data['parametros']['date_to']))
                <p><strong>Período:</strong> 
                    @if(isset($data['parametros']['date_from']))
                        Desde: {{ \Carbon\Carbon::parse($data['parametros']['date_from'])->format('d/m/Y') }}
                    @endif
                    @if(isset($data['parametros']['date_to']))
                        Hasta: {{ \Carbon\Carbon::parse($data['parametros']['date_to'])->format('d/m/Y') }}
                    @endif
                </p>
            @endif
        </div>



        <div class="table-container">
            <h3>Detalle de Participación Docente</h3>
            
            @if($data['profesores']->count() > 0)
                <table>
                                    <thead>
                    <tr>
                        <th style="width: 3%;">ID</th>
                        <th style="width: 8%;">Identificación</th>
                        <th style="width: 12%;">Nombres</th>
                        <th style="width: 15%;">Email</th>
                        <th style="width: 12%;">Programa</th>
                        <th style="width: 12%;">Facultad</th>
                        <th style="width: 12%;">Institución</th>
                        <th style="width: 5%;">Total</th>
                        <th style="width: 5%;">Completadas</th>
                        <th style="width: 5%;">Pendientes</th>
                        <th style="width: 5%;">Progreso</th>
                        <th style="width: 6%;">Último Test</th>
                        <th style="width: 6%;">Estado</th>
                    </tr>
                </thead>
                    <tbody>
                        @php
                            $profesoresMostrados = $data['profesores']->count() > 100 ? $data['profesores']->take(100) : $data['profesores'];
                            $hayMasRegistros = $data['profesores']->count() > 100;
                        @endphp
                        
                        @foreach($profesoresMostrados as $profesor)
                                                    <tr>
                            <td>{{ $profesor['id'] }}</td>
                            <td>{{ $profesor['identificacion'] }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($profesor['nombres_completos'], 20) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($profesor['email'], 25) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($profesor['programa'], 20) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($profesor['facultad'], 20) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($profesor['institucion'], 20) }}</td>
                            <td>{{ $profesor['total_tests'] }}</td>
                            <td>{{ $profesor['tests_completados'] }}</td>
                            <td>{{ $profesor['tests_pendientes'] }}</td>
                            <td>{{ $profesor['tests_en_progreso'] }}</td>
                            <td>{{ $profesor['ultimo_test'] ? \Illuminate\Support\Str::limit($profesor['ultimo_test'], 10) : 'N/A' }}</td>
                            <td>
                                @if($profesor['estado'] === 'Completado')
                                    <span class="status-completado">✓</span>
                                @else
                                    <span class="status-pendiente">⏳</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        
                        @if($hayMasRegistros)
                            <tr>
                                <td colspan="13" style="text-align: center; font-style: italic; color: #000000;">
                                    <strong>Nota:</strong> Se muestran los primeros 100 registros de {{ $data['total_profesores'] }} totales. 
                                    Para ver todos los registros, genere el reporte con filtros más específicos.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @else
                <div style="background: #fef3c7; border: 1px solid #f59e0b; border-radius: 6px; padding: 1rem; text-align: center; margin: 1rem 0;">
                    <div style="font-size: 1.2rem; color: #000000; margin-bottom: 0.5rem;">
                        ⚠️ No hay datos disponibles
                    </div>
                    <p style="color: #000000; font-size: 0.9rem; margin: 0;">
                        No se encontraron profesores que cumplan con el filtro: 
                        <strong>
                            @switch($data['filtro_aplicado'])
                                @case('completados')
                                    Solo completados
                                    @break
                                @case('pendientes')
                                    Solo pendientes
                                    @break
                                @default
                                    {{ ucfirst($data['filtro_aplicado']) }}
                            @endswitch
                        </strong>
                    </p>
                    <p style="color: #000000; font-size: 0.8rem; margin-top: 0.5rem;">
                        Intente cambiar el filtro o el período de fechas.
                    </p>
                </div>
            @endif
        </div>

        @if(isset($data['es_vista_previa']) && $data['es_vista_previa'])
            <div class="footer">
                <p><strong>Vista Previa:</strong> Mostrando los primeros {{ $data['profesores']->count() }} registros de {{ $data['total_profesores'] }} totales.</p>
            </div>
        @endif

        <div class="footer">
            <p>Reporte generado automáticamente por el Sistema de Evaluación de Competencias Digitales</p>
            <p>Fecha de generación: {{ $data['fecha_generacion'] }}</p>
        </div>
    </div>
</body>
</html> 