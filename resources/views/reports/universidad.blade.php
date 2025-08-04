<style>
    /* Estilos para modal (vista previa) */
    .university-report {
        font-family: Arial, sans-serif;
        color: #333;
        line-height: 1.4;
        font-size: 14px;
        margin: 0;
        padding: 20px;
    }
    
    .university-header {
        text-align: center;
        border-bottom: 2px solid #93c5fd;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .university-header h1 {
        color: #3b82f6;
        font-size: 24px;
        margin: 0 0 8px 0;
        font-weight: bold;
    }
    
    .university-header h2 {
        color: #1d4ed8;
        font-size: 18px;
        margin: 0 0 10px 0;
        font-weight: bold;
    }
    
    .university-header div {
        font-size: 14px;
        margin-top: 8px;
    }
    
    .university-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .university-info h3 {
        color: #3b82f6;
        margin: 0 0 15px 0;
        font-size: 18px;
        font-weight: bold;
    }
    
    .university-info p {
        margin-bottom: 10px;
        font-size: 14px;
    }
    
    .university-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
        width: 100%;
    }
    
    .university-stat {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 15px 12px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        min-width: 120px;
    }
    
    .university-stat-title {
        color: #3b82f6;
        font-weight: bold;
        font-size: 10px;
        margin-bottom: 5px;
        line-height: 1.2;
    }
    
    .university-stat-value {
        color: #1d4ed8;
        font-size: 16px;
        font-weight: bold;
        line-height: 1.2;
    }
    
    .university-table-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
    }
    
    .university-section-title {
        color: #3b82f6;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
        border-bottom: 2px solid #bae6fd;
        padding-bottom: 10px;
    }
    
    .university-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 12px;
    }
    
    .university-table th, .university-table td {
        padding: 12px 8px;
        text-align: center;
        border-bottom: 1px solid #bae6fd;
    }
    
    .university-table th {
        background: #f0f9ff;
        color: #3b82f6;
        font-weight: bold;
        font-size: 13px;
    }
    
    .university-table td {
        font-size: 12px;
        vertical-align: middle;
    }
    
    .university-table tr:last-child td {
        border-bottom: none;
    }
    
    /* Estilos específicos para PDF */
    @media print {
        * {
            box-sizing: border-box;
        }
        
        .university-report {
            font-family: Arial, sans-serif !important;
            color: #333 !important;
            line-height: 1.1 !important;
            font-size: 8px !important;
            margin: 0 !important;
            padding: 10px !important;
        }
        
        .university-header {
            text-align: center !important;
            border-bottom: 2px solid #93c5fd !important;
            padding-bottom: 10px !important;
            margin-bottom: 15px !important;
        }
        
        .university-header h1 {
            color: #3b82f6 !important;
            font-size: 14px !important;
            margin: 0 0 3px 0 !important;
            font-weight: bold !important;
        }
        
        .university-header h2 {
            color: #1d4ed8 !important;
            font-size: 11px !important;
            margin: 0 0 5px 0 !important;
            font-weight: bold !important;
        }
        
        .university-header div {
            font-size: 9px !important;
            margin-top: 3px !important;
        }
        
        .university-info {
            background: #f0f9ff !important;
            border: 1px solid #bae6fd !important;
            border-radius: 8px !important;
            padding: 10px !important;
            margin-bottom: 15px !important;
            box-shadow: none !important;
        }
        
        .university-info h3 {
            color: #3b82f6 !important;
            margin: 0 0 8px 0 !important;
            font-size: 10px !important;
            font-weight: bold !important;
        }
        
        .university-info p {
            margin-bottom: 5px !important;
            font-size: 8px !important;
        }
        
        .university-stats {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)) !important;
            gap: 10px !important;
            margin-bottom: 20px !important;
            width: 100% !important;
        }
        
        .university-stat {
            background: #f0f9ff !important;
            border: 1px solid #bae6fd !important;
            border-radius: 6px !important;
            padding: 10px 8px !important;
            text-align: center !important;
            box-shadow: none !important;
            min-width: 100px !important;
            break-inside: avoid;
            page-break-inside: avoid;
        }
        
        .university-stat-title {
            color: #3b82f6 !important;
            font-weight: bold !important;
            font-size: 8px !important;
            margin-bottom: 3px !important;
            line-height: 1.2 !important;
        }
        
        .university-stat-value {
            color: #1d4ed8 !important;
            font-size: 12px !important;
            font-weight: bold !important;
            line-height: 1.2 !important;
        }
        
        .university-table-container {
            background: white !important;
            border-radius: 8px !important;
            padding: 12px !important;
            box-shadow: none !important;
            margin-bottom: 15px !important;
        }
        
        .university-section-title {
            color: #3b82f6 !important;
            font-size: 11px !important;
            font-weight: bold !important;
            margin-bottom: 10px !important;
            border-bottom: 1px solid #bae6fd !important;
            padding-bottom: 5px !important;
        }
        
        .university-table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 8px !important;
            font-size: 6px !important;
            table-layout: fixed !important;
        }
        
        .university-table th, .university-table td {
            padding: 3px 2px !important;
            text-align: center !important;
            border-bottom: 1px solid #bae6fd !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .university-table th {
            background: #f0f9ff !important;
            color: #3b82f6 !important;
            font-weight: bold !important;
            font-size: 6px !important;
        }
        
        .university-table td {
            font-size: 6px !important;
            vertical-align: middle !important;
        }
        
        /* Ocultar botón en PDF */
        div[style*="text-align: right"] {
            display: none !important;
        }
    }
    
    .university-report {
        font-family: Arial, sans-serif;
        color: #333;
        line-height: 1.6;
    }
    .university-header {
        text-align: center;
        border-bottom: 3px solid #93c5fd;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }
    .university-header h1 {
        color: #3b82f6;
        font-size: 2.2rem;
        margin: 0 0 8px 0;
    }
    .university-header h2 {
        color: #1d4ed8;
        font-size: 1.5rem;
        margin: 0 0 10px 0;
    }
    .university-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
    }
    .university-info h3 {
        color: #3b82f6;
        margin-top: 0;
    }
    .university-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }
    .university-stat {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 15px 20px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
    }
    .university-stat-title {
        color: #3b82f6;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 8px;
    }
    .university-stat-value {
        color: #1d4ed8;
        font-size: 1.8rem;
        font-weight: 700;
    }
    .university-table-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }
    .university-section-title {
        color: #3b82f6;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 20px;
        border-bottom: 2px solid #bae6fd;
        padding-bottom: 10px;
    }
    .university-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
        font-size: 0.75rem;
        table-layout: fixed;
    }
    .university-table th, .university-table td {
        padding: 6px 4px;
        text-align: center;
        border-bottom: 1px solid #bae6fd;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    .university-table th {
        background: #f0f9ff;
        color: #3b82f6;
        font-weight: bold;
        font-size: 0.7rem;
    }
    .university-table td {
        font-size: 0.65rem;
        vertical-align: middle;
    }
    .university-table tr:last-child td {
        border-bottom: none;
    }
</style>

<div class="university-report">
    {{-- Botón de descarga --}}
    @if(!isset($isViewingExistingReport) || !$isViewingExistingReport)
    <div style="text-align: right; margin-bottom: 20px;">
        <a
            href="{{ auth()->user()->hasRole('Coordinador') ? route('coordinador.reports.pdf') : route('admin.reports.pdf') }}?tipo_reporte=universidad&entidad_id={{ $previewData['entidad']['id'] ?? '' }}&redirect=1"
            target="_blank"
            onclick="if(!confirm('¿Está seguro de que desea generar el reporte?')) return false; this.style.pointerEvents='none'; this.innerHTML='🔄 Generando...'; setTimeout(() => { @if(auth()->user()->hasRole('Coordinador')) window.location.href='{{ route('coordinador.reports.index') }}'; @else window.location.href='/admin/reports'; @endif }, 2000);"
            style="background-color: #3b82f6; color: white; padding: 12px 20px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 14px;"
        >
            ⬇️ Generar Reporte
        </a>
    </div>
    @endif

    <div class="university-header">
        <h1>Reporte de Universidad</h1>
        <h2>{{ $previewData['entidad']['name'] ?? 'N/A' }}</h2>
        <div style="font-size: 1.1rem; margin-top: 0.5rem;">
            Fecha de Generación: {{ $previewData['fecha_generacion'] ?? now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="university-info">
        <h3>Información de la Institución</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            <div>
                <p style="margin-bottom: 15px;"><strong>Nombre:</strong> {{ $previewData['entidad']['name'] ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="university-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 15px; margin-bottom: 30px; width: 100%;">
        <div class="university-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; min-width: 120px;">
            <div class="university-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Total de Facultades</div>
            <div class="university-stat-value" style="color: #1d4ed8; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['total_facultades'] ?? 0 }}</div>
        </div>
        <div class="university-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; min-width: 120px;">
            <div class="university-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Total de Programas</div>
            <div class="university-stat-value" style="color: #1d4ed8; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['total_programas'] ?? 0 }}</div>
        </div>
        <div class="university-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; min-width: 120px;">
            <div class="university-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Total de Profesores</div>
            <div class="university-stat-value" style="color: #1d4ed8; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['total_profesores'] ?? 0 }}</div>
        </div>
        <div class="university-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; min-width: 120px;">
            <div class="university-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Profesores Completados</div>
            <div class="university-stat-value" style="color: #059669; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['total_profesores_completados'] ?? 0 }}</div>
        </div>
        <div class="university-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; min-width: 120px;">
            <div class="university-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Profesores Pendientes</div>
            <div class="university-stat-value" style="color: #dc2626; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['total_profesores_pendientes'] ?? 0 }}</div>
        </div>
        <div class="university-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; min-width: 120px;">
            <div class="university-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Promedio de la Institución</div>
            <div class="university-stat-value" style="color: #1d4ed8; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ number_format($previewData['promedio_institucion'] ?? 0, 2) }}%</div>
        </div>
        @if(isset($previewData['promedios_por_test']) && count($previewData['promedios_por_test']) > 0)
            @foreach($previewData['promedios_por_test'] as $testPromedio)
                <div class="university-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; min-width: 120px;">
                    <div class="university-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Promedio {{ $testPromedio['test_name'] }}</div>
                    <div class="university-stat-value" style="color: #7c3aed; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ number_format($testPromedio['promedio'], 2) }}%</div>
                </div>
            @endforeach
        @endif
        <div class="university-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; min-width: 120px;">
            <div class="university-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Puntuación Máxima</div>
            <div class="university-stat-value" style="color: #059669; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['puntuacion_maxima'] ?? 0 }}</div>
        </div>
        <div class="university-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; min-width: 120px;">
            <div class="university-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Puntuación Mínima</div>
            <div class="university-stat-value" style="color: #dc2626; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['puntuacion_minima'] ?? 0 }}</div>
        </div>
    </div>

    <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px; margin-bottom: 20px; font-size: 0.8rem; color: #1d4ed8;">
        <strong>Información sobre los Promedios:</strong><br>
        • <strong>Promedio de la Institución:</strong> Calculado considerando todos los tests asignados a todos los profesores de la institución.<br>
        • <strong>Promedio por Test:</strong> Promedio específico de cada evaluación individual.<br>
        • <strong>Promedio General de Facultades:</strong> Cada facultad tiene su propio promedio basado en sus profesores y tests asignados.
    </div>

    <div class="university-table-container">
        <h2 class="university-section-title">Facultades de la Institución</h2>
        <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px; margin-bottom: 15px; font-size: 0.8rem; color: #1d4ed8;">
            <strong>Nota:</strong> El "Promedio General" de cada facultad se calcula considerando todos los tests asignados a sus profesores. 
            El "Promedio General" total de la institución (6.03%) no es un promedio simple de los promedios de las facultades, 
            sino un promedio ponderado que considera el número de profesores y tests de cada facultad.
        </div>
        <table class="university-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Pos.</th>
                    <th style="width: 25%;">Nombre de la Facultad</th>
                    <th style="width: 12%;">Total Profesores</th>
                    <th style="width: 12%;">Estado</th>
                    <th style="width: 12%;">Promedio General</th>
                    <th style="width: 12%;">Profesores Completados</th>
                    <th style="width: 12%;">Profesores Pendientes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($previewData['resultados_por_facultad'] as $index => $resultado)
                    <tr>
                        <td style="text-align: center; font-weight: 600; color: #3b82f6;">{{ $index + 1 }}</td>
                        <td style="font-weight: 600; color: #1d4ed8;">{{ $resultado['nombre_facultad'] }}</td>
                        <td style="text-align: center; font-weight: 700; color: #3b82f6;">{{ $resultado['total_profesores'] }}</td>
                        <td>
                            @if(isset($resultado['ha_completado_todos']) && $resultado['ha_completado_todos'])
                                <span style="color: #059669; font-weight: 600; font-size: 0.6rem;">Completado</span>
                                <div style="font-size: 0.55rem; color: #666;">{{ $resultado['tests_completados'] }}/{{ $resultado['total_tests'] }}</div>
                            @else
                                <span style="color: #dc2626; font-weight: 600; font-size: 0.6rem;">Pendiente</span>
                                <div style="font-size: 0.55rem; color: #666;">{{ $resultado['tests_completados'] }}/{{ $resultado['total_tests'] }}</div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div style="font-weight: 700; font-size: 0.7rem; color: #3b82f6;">{{ number_format($resultado['promedio_general'], 2) }}%</div>
                        </td>
                        <td style="text-align: center; color: #059669; font-weight: 700; font-size: 0.7rem;">
                            {{ $resultado['profesores_completados'] }}
                        </td>
                        <td style="text-align: center; color: #dc2626; font-weight: 700; font-size: 0.7rem;">
                            {{ $resultado['profesores_pendientes'] }}
                        </td>
                    </tr>
                @endforeach
                <!-- Fila de Totales para Facultades -->
                <tr style="background: #f8fafc; border-top: 2px solid #3b82f6;">
                    <td style="text-align: center; font-weight: 700; color: #1d4ed8; font-size: 0.8rem;">TOTAL</td>
                    <td style="font-weight: 700; color: #1d4ed8; font-size: 0.8rem;">{{ $previewData['resultados_por_facultad']->count() }} Facultades</td>
                    <td style="text-align: center; font-weight: 700; color: #1d4ed8; font-size: 0.8rem;">{{ $previewData['resultados_por_facultad']->sum('total_profesores') }}</td>
                    <td style="text-align: center; font-weight: 700; color: #1d4ed8; font-size: 0.8rem;">
                        {{ $previewData['resultados_por_facultad']->sum('tests_completados') }}/{{ $previewData['resultados_por_facultad']->sum('total_tests') }}
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #1d4ed8; font-size: 0.8rem;">
                        {{ number_format($previewData['promedio_institucion'] ?? 0, 2) }}%
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #059669; font-size: 0.8rem;">
                        {{ $previewData['resultados_por_facultad']->sum('profesores_completados') }}
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #dc2626; font-size: 0.8rem;">
                        {{ $previewData['resultados_por_facultad']->sum('profesores_pendientes') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="university-table-container">
        <h2 class="university-section-title">Programas de la Institución</h2>
        <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px; margin-bottom: 15px; font-size: 0.8rem; color: #1d4ed8;">
            <strong>Nota:</strong> El "Promedio General" de cada programa se calcula considerando todos los tests asignados a sus profesores. 
            Al igual que con las facultades, el promedio total no es un promedio simple de los promedios de los programas.
        </div>
        <table class="university-table">
            <thead>
                <tr>
                    <th style="width: 6%;">Pos.</th>
                    <th style="width: 25%;">Nombre del Programa</th>
                    <th style="width: 20%;">Facultad</th>
                    <th style="width: 12%;">Total Profesores</th>
                    <th style="width: 12%;">Estado</th>
                    <th style="width: 12%;">Promedio General</th>
                    <th style="width: 12%;">Profesores Completados</th>
                    <th style="width: 12%;">Profesores Pendientes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($previewData['resultados_por_programa'] as $index => $resultado)
                    <tr>
                        <td style="text-align: center; font-weight: 600; color: #3b82f6;">{{ $index + 1 }}</td>
                        <td style="font-weight: 600; color: #1d4ed8;">{{ $resultado['nombre_programa'] }}</td>
                        <td style="font-size: 0.6rem; color: #666;">{{ $resultado['facultad_nombre'] }}</td>
                        <td style="text-align: center; font-weight: 700; color: #3b82f6;">{{ $resultado['total_profesores'] }}</td>
                        <td>
                            @if(isset($resultado['ha_completado_todos']) && $resultado['ha_completado_todos'])
                                <span style="color: #059669; font-weight: 600; font-size: 0.6rem;">Completado</span>
                                <div style="font-size: 0.55rem; color: #666;">{{ $resultado['tests_completados'] }}/{{ $resultado['total_tests'] }}</div>
                            @else
                                <span style="color: #dc2626; font-weight: 600; font-size: 0.6rem;">Pendiente</span>
                                <div style="font-size: 0.55rem; color: #666;">{{ $resultado['tests_completados'] }}/{{ $resultado['total_tests'] }}</div>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <div style="font-weight: 700; font-size: 0.7rem; color: #3b82f6;">{{ number_format($resultado['promedio_general'], 2) }}%</div>
                        </td>
                        <td style="text-align: center; color: #059669; font-weight: 700; font-size: 0.7rem;">
                            {{ $resultado['profesores_completados'] }}
                        </td>
                        <td style="text-align: center; color: #dc2626; font-weight: 700; font-size: 0.7rem;">
                            {{ $resultado['profesores_pendientes'] }}
                        </td>
                    </tr>
                @endforeach
                <!-- Fila de Totales para Programas -->
                <tr style="background: #f8fafc; border-top: 2px solid #3b82f6;">
                    <td style="text-align: center; font-weight: 700; color: #1d4ed8; font-size: 0.8rem;">TOTAL</td>
                    <td style="font-weight: 700; color: #1d4ed8; font-size: 0.8rem;">{{ $previewData['resultados_por_programa']->count() }} Programas</td>
                    <td style="font-weight: 700; color: #1d4ed8; font-size: 0.8rem;">-</td>
                    <td style="text-align: center; font-weight: 700; color: #1d4ed8; font-size: 0.8rem;">{{ $previewData['resultados_por_programa']->sum('total_profesores') }}</td>
                    <td style="text-align: center; font-weight: 700; color: #1d4ed8; font-size: 0.8rem;">
                        {{ $previewData['resultados_por_programa']->sum('tests_completados') }}/{{ $previewData['resultados_por_programa']->sum('total_tests') }}
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #1d4ed8; font-size: 0.8rem;">
                        {{ number_format($previewData['promedio_institucion'] ?? 0, 2) }}%
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #059669; font-size: 0.8rem;">
                        {{ $previewData['resultados_por_programa']->sum('profesores_completados') }}
                    </td>
                    <td style="text-align: center; font-weight: 700; color: #dc2626; font-size: 0.8rem;">
                        {{ $previewData['resultados_por_programa']->sum('profesores_pendientes') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div> 