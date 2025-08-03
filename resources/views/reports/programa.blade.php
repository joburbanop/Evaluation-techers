<style>
    /* Estilos para modal (vista previa) */
    .program-report {
        font-family: Arial, sans-serif;
        color: #333;
        line-height: 1.4;
        font-size: 14px;
        margin: 0;
        padding: 20px;
    }
    
    .program-header {
        text-align: center;
        border-bottom: 2px solid #93c5fd;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .program-header h1 {
        color: #3b82f6;
        font-size: 24px;
        margin: 0 0 8px 0;
        font-weight: bold;
    }
    
    .program-header h2 {
        color: #1d4ed8;
        font-size: 18px;
        margin: 0 0 10px 0;
        font-weight: bold;
    }
    
    .program-header div {
        font-size: 14px;
        margin-top: 8px;
    }
    
    .program-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .program-info h3 {
        color: #3b82f6;
        margin: 0 0 15px 0;
        font-size: 18px;
        font-weight: bold;
    }
    
    .program-info p {
        margin-bottom: 10px;
        font-size: 14px;
    }
    
    .program-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 30px;
        justify-content: space-between;
        width: 100%;
    }
    
    .program-stat {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 15px 12px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        flex: 0 0 calc(16.66% - 13px);
        min-width: 120px;
    }
    
    .program-stat-title {
        color: #3b82f6;
        font-weight: bold;
        font-size: 10px;
        margin-bottom: 5px;
        line-height: 1.2;
    }
    
    .program-stat-value {
        color: #1d4ed8;
        font-size: 16px;
        font-weight: bold;
        line-height: 1.2;
    }
    
    .program-table-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
    }
    
    .program-section-title {
        color: #3b82f6;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
        border-bottom: 2px solid #bae6fd;
        padding-bottom: 10px;
    }
    
    .program-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 12px;
    }
    
    .program-table th, .program-table td {
        padding: 12px 8px;
        text-align: center;
        border-bottom: 1px solid #bae6fd;
    }
    
    .program-table th {
        background: #f0f9ff;
        color: #3b82f6;
        font-weight: bold;
        font-size: 13px;
    }
    
    .program-table td {
        font-size: 12px;
        vertical-align: middle;
    }
    
    .program-table tr:last-child td {
        border-bottom: none;
    }
    
    /* Estilos específicos para PDF */
    @media print {
        * {
            box-sizing: border-box;
        }
        
        .program-report {
            font-family: Arial, sans-serif !important;
            color: #333 !important;
            line-height: 1.1 !important;
            font-size: 8px !important;
            margin: 0 !important;
            padding: 10px !important;
        }
        
        .program-header {
            text-align: center !important;
            border-bottom: 2px solid #93c5fd !important;
            padding-bottom: 10px !important;
            margin-bottom: 15px !important;
        }
        
        .program-header h1 {
            color: #3b82f6 !important;
            font-size: 14px !important;
            margin: 0 0 3px 0 !important;
            font-weight: bold !important;
        }
        
        .program-header h2 {
            color: #1d4ed8 !important;
            font-size: 11px !important;
            margin: 0 0 5px 0 !important;
            font-weight: bold !important;
        }
        
        .program-header div {
            font-size: 9px !important;
            margin-top: 3px !important;
        }
        
        .program-info {
            background: #f0f9ff !important;
            border: 1px solid #bae6fd !important;
            border-radius: 8px !important;
            padding: 10px !important;
            margin-bottom: 15px !important;
            box-shadow: none !important;
        }
        
        .program-info h3 {
            color: #3b82f6 !important;
            margin: 0 0 8px 0 !important;
            font-size: 10px !important;
            font-weight: bold !important;
        }
        
        .program-info p {
            margin-bottom: 5px !important;
            font-size: 8px !important;
        }
        
        .program-stats {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 10px !important;
            margin-bottom: 20px !important;
            justify-content: space-between !important;
            width: 100% !important;
        }
        
        .program-stat {
            background: #f0f9ff !important;
            border: 1px solid #bae6fd !important;
            border-radius: 6px !important;
            padding: 10px 8px !important;
            text-align: center !important;
            box-shadow: none !important;
            flex: 0 0 calc(16.66% - 9px) !important;
            min-width: 100px !important;
            break-inside: avoid;
            page-break-inside: avoid;
        }
        
        .program-stat-title {
            color: #3b82f6 !important;
            font-weight: bold !important;
            font-size: 8px !important;
            margin-bottom: 3px !important;
            line-height: 1.2 !important;
        }
        
        .program-stat-value {
            color: #1d4ed8 !important;
            font-size: 12px !important;
            font-weight: bold !important;
            line-height: 1 !important;
        }
        
        .program-table-container {
            background: white !important;
            border-radius: 8px !important;
            padding: 12px !important;
            box-shadow: none !important;
            margin-bottom: 15px !important;
        }
        
        .program-section-title {
            color: #3b82f6 !important;
            font-size: 11px !important;
            font-weight: bold !important;
            margin-bottom: 10px !important;
            border-bottom: 1px solid #bae6fd !important;
            padding-bottom: 5px !important;
        }
        
        .program-table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 8px !important;
            font-size: 6px !important;
            table-layout: fixed !important;
        }
        
        .program-table th, .program-table td {
            padding: 3px 2px !important;
            text-align: center !important;
            border-bottom: 1px solid #bae6fd !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .program-table th {
            background: #f0f9ff !important;
            color: #3b82f6 !important;
            font-weight: bold !important;
            font-size: 6px !important;
        }
        
        .program-table td {
            font-size: 6px !important;
            vertical-align: middle !important;
        }
        
        /* Ocultar botón en PDF */
        div[style*="text-align: right"] {
            display: none !important;
        }
    }
    
    .program-report {
        font-family: Arial, sans-serif;
        color: #333;
        line-height: 1.6;
    }
    .program-header {
        text-align: center;
        border-bottom: 3px solid #93c5fd;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }
    .program-header h1 {
        color: #3b82f6;
        font-size: 2.2rem;
        margin: 0 0 8px 0;
    }
    .program-header h2 {
        color: #1d4ed8;
        font-size: 1.5rem;
        margin: 0 0 10px 0;
    }
    .program-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }
    .program-stat {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 15px 20px;
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
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }
    .program-section-title {
        color: #3b82f6;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 20px;
        border-bottom: 2px solid #bae6fd;
        padding-bottom: 10px;
    }
    .program-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 0.75rem;
        table-layout: fixed;
    }
    .program-table th, .program-table td {
        padding: 6px 4px;
        text-align: center;
        border-bottom: 1px solid #bae6fd;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    .program-table th {
        background: #f0f9ff;
        color: #3b82f6;
        font-weight: bold;
        font-size: 0.7rem;
    }
    .program-table td {
        font-size: 0.65rem;
        vertical-align: middle;
    }
    .program-table tr:last-child td {
        border-bottom: none;
    }
</style>

<div class="program-report">
    {{-- Botón de descarga --}}
    @if(!isset($isViewingExistingReport) || !$isViewingExistingReport)
    <div style="text-align: right; margin-bottom: 20px;">
        <a
            href="{{ auth()->user()->hasRole('Coordinador') ? route('coordinador.reports.pdf') : route('admin.reports.pdf') }}?tipo_reporte=programa&entidad_id={{ $previewData['entidad']['id'] ?? '' }}&redirect=1"
            target="_blank"
            onclick="if(!confirm('¿Está seguro de que desea generar el reporte?')) return false; this.style.pointerEvents='none'; this.innerHTML='🔄 Generando...'; setTimeout(() => { @if(auth()->user()->hasRole('Coordinador')) window.location.href='{{ route('coordinador.reports.index') }}'; @else window.location.href='/admin/reports'; @endif }, 2000);"
            style="background-color: #3b82f6; color: white; padding: 12px 20px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 14px;"
        >
            ⬇️ Generar Reporte
        </a>
    </div>
    @endif

    <div class="program-header">
        <h1>Reporte de Programa</h1>
        <h2>{{ $previewData['entidad']['nombre'] ?? 'N/A' }}</h2>
        <div style="font-size: 1rem; margin-top: 0.5rem;">
            <strong>Facultad:</strong> {{ $previewData['facultad']['nombre'] ?? 'N/A' }} | 
            <strong>Institución:</strong> {{ $previewData['facultad']['institution']['name'] ?? 'N/A' }} | 
            <strong>Fecha:</strong> {{ $previewData['fecha_aplicacion'] ?? 'N/A' }}
        </div>
    </div>

    <div class="program-stats" style="display: flex; flex-direction: row; flex-wrap: nowrap; gap: 8px; margin-bottom: 20px; justify-content: space-between; width: 100%;">
        <div class="program-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; flex: 1; min-width: 80px; max-width: none;">
            <div class="program-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Total de Profesores</div>
            <div class="program-stat-value" style="color: #1d4ed8; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['total_profesores'] ?? 0 }}</div>
        </div>
        <div class="program-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; flex: 1; min-width: 80px; max-width: none;">
            <div class="program-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Profesores Completados</div>
            <div class="program-stat-value" style="color: #059669; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['total_profesores_completados'] ?? 0 }}</div>
        </div>
        <div class="program-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; flex: 1; min-width: 80px; max-width: none;">
            <div class="program-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Profesores Pendientes</div>
            <div class="program-stat-value" style="color: #dc2626; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['total_profesores_pendientes'] ?? 0 }}</div>
        </div>
        <div class="program-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; flex: 1; min-width: 80px; max-width: none;">
            <div class="program-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Promedio del Programa</div>
            <div class="program-stat-value" style="color: #1d4ed8; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ number_format($previewData['promedio_programa'] ?? 0, 2) }}%</div>
        </div>
        @if(isset($previewData['promedios_por_test']) && count($previewData['promedios_por_test']) > 0)
            @foreach($previewData['promedios_por_test'] as $testPromedio)
                <div class="program-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; flex: 1; min-width: 80px; max-width: none;">
                    <div class="program-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Promedio {{ $testPromedio['test_name'] }}</div>
                    <div class="program-stat-value" style="color: #7c3aed; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ number_format($testPromedio['promedio'], 2) }}%</div>
                </div>
            @endforeach
        @endif
        <div class="program-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; flex: 1; min-width: 80px; max-width: none;">
            <div class="program-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Puntuación Máxima</div>
            <div class="program-stat-value" style="color: #059669; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['puntuacion_maxima'] ?? 0 }}</div>
        </div>
        <div class="program-stat" style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 12px 8px; text-align: center; flex: 1; min-width: 80px; max-width: none;">
            <div class="program-stat-title" style="color: #3b82f6; font-weight: bold; font-size: 10px; margin-bottom: 4px; line-height: 1.2;">Puntuación Mínima</div>
            <div class="program-stat-value" style="color: #dc2626; font-size: 16px; font-weight: bold; line-height: 1.2;">{{ $previewData['puntuacion_minima'] ?? 0 }}</div>
        </div>
    </div>

    <div class="program-table-container">
        <h2 style="color: #3b82f6; text-align: left; margin-bottom: 1rem;">Resultados por Profesor (Ordenados de Mayor a Menor)</h2>
        <table class="program-table">
            <thead>
                <tr>
                    <th style="width: 6%;">Pos.</th>
                    <th style="width: 18%;">Nombre Completo</th>
                    <th style="width: 10%;">Estado</th>
                    <th style="width: 10%;">Promedio General</th>
                    <th style="width: 56%;">Rendimiento por Área y Tests</th>
                </tr>
            </thead>
            <tbody>
                @foreach($previewData['resultados_por_profesor'] as $index => $resultado)
                    <tr>
                        <td style="text-align: center; font-weight: 600; color: #3b82f6;">{{ $index + 1 }}</td>
                        <td style="font-weight: 600; color: #1d4ed8;">{{ $resultado['nombre_completo'] }}</td>
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
                        <td style="text-align: left; padding: 8px;">
                            <div style="display: flex; gap: 15px;">
                                <!-- Tests Individuales -->
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 600; color: #3b82f6; font-size: 0.65rem; margin-bottom: 4px; border-bottom: 1px solid #e5e7eb; padding-bottom: 2px;">
                                        Tests Completados:
                                    </div>
                                    @if(isset($resultado['tests_individuales']) && count($resultado['tests_individuales']) > 0)
                                        @foreach($resultado['tests_individuales'] as $test)
                                            <div style="margin-bottom: 3px; padding: 2px 4px; background: #f0f9ff; border-radius: 3px; border-left: 2px solid #3b82f6;">
                                                <div style="font-weight: 600; color: #1d4ed8; font-size: 0.6rem;">
                                                    {{ $test['test_name'] }}: {{ $test['puntaje'] }}/{{ $test['puntaje_maximo'] }} ({{ $test['porcentaje'] }}%)
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <span style="color: #999; font-style: italic; font-size: 0.55rem;">Sin tests completados</span>
                                    @endif
                                </div>
                                
                                <!-- Resultados por Área -->
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-weight: 600; color: #3b82f6; font-size: 0.65rem; margin-bottom: 4px; border-bottom: 1px solid #e5e7eb; padding-bottom: 2px;">
                                        Rendimiento por Área:
                                    </div>
                                    @if(isset($resultado['resultados_por_area']) && count($resultado['resultados_por_area']) > 0)
                                        @foreach($resultado['resultados_por_area'] as $area)
                                            <div style="margin-bottom: 3px; padding: 2px 4px; background: #f0f9ff; border-radius: 3px; border-left: 2px solid #10b981;">
                                                <div style="font-weight: 600; color: #059669; font-size: 0.6rem;">
                                                    {{ $area['area_name'] }}: {{ $area['puntaje'] }}/{{ $area['total_posible'] }} ({{ $area['porcentaje'] }}%)
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <span style="color: #999; font-style: italic; font-size: 0.55rem;">Sin datos de áreas</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> 