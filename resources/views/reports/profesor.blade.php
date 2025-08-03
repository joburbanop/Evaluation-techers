<style>
    .professor-report {
        font-family: Arial, sans-serif;
        color: #333;
        line-height: 1.6;
    }
    .professor-header {
        text-align: center;
        border-bottom: 3px solid #93c5fd;
        padding-bottom: 30px;
        margin-bottom: 40px;
    }
    .professor-header h1 {
        color: #3b82f6;
        font-size: 2.5rem;
        margin: 0 0 10px 0;
    }
    .professor-header h2 {
        color: #1d4ed8;
        font-size: 1.8rem;
        margin: 0 0 15px 0;
    }
    .professor-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
    }
    .professor-info h3 {
        color: #3b82f6;
        margin-top: 0;
    }
    .professor-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    .professor-stat {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 30px 25px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
    }
    .professor-stat-title {
        color: #3b82f6;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 8px;
    }
    .professor-stat-value {
        color: #1d4ed8;
        font-size: 1.8rem;
        font-weight: 700;
    }
    .professor-table-container {
        background: white;
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 40px;
    }
    .professor-section-title {
        color: #3b82f6;
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 30px;
        border-bottom: 2px solid #bae6fd;
        padding-bottom: 15px;
    }
    .professor-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
    }
    .professor-table th {
        background: #f0f9ff;
        color: #3b82f6;
        padding: 20px 15px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #bae6fd;
        font-size: 1.1rem;
    }
    .professor-table td {
        padding: 18px 15px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
        font-size: 1rem;
    }
    .professor-table tr:hover {
        background-color: #f8fafc;
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
</style>

<div class="professor-report">
    {{-- Botón de descarga --}}
    <div style="text-align: right; margin-bottom: 20px;">
        <a
            href="{{ auth()->user()->hasRole('Coordinador') ? route('coordinador.reports.pdf') : route('admin.reports.pdf') }}?tipo_reporte=profesor&entidad_id={{ $previewData['profesor']['id'] ?? '' }}&redirect=1"
            target="_blank"
            onclick="if(!confirm('¿Está seguro de que desea generar el reporte?')) return false; this.style.pointerEvents='none'; this.innerHTML='🔄 Generando...'; setTimeout(() => { @if(auth()->user()->hasRole('Coordinador')) window.location.href='{{ route('coordinador.reports.index') }}'; @else window.location.href='/admin/reports'; @endif }, 2000);"
            style="background-color: #3b82f6; color: white; padding: 12px 20px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 14px;"
        >
            ⬇️ Generar Reporte
        </a>
    </div>

    <div class="professor-header">
        <h1>Reporte de Profesor</h1>
        <h2>{{ $previewData['profesor']['nombre_completo'] ?? 'N/A' }}</h2>
        <div style="font-size: 1.1rem; margin-top: 0.5rem;">
            Fecha de Generación: {{ $previewData['fecha_generacion'] ?? now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="professor-info">
        <h3>Información del Profesor</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            <div>
                <p style="margin-bottom: 15px;"><strong>Nombre Completo:</strong> {{ $previewData['profesor']['nombre_completo'] ?? 'N/A' }}</p>
                <p style="margin-bottom: 15px;"><strong>Email:</strong> {{ $previewData['profesor']['email'] ?? 'N/A' }}</p>
                <p style="margin-bottom: 15px;"><strong>Estado:</strong> 
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
                    <p style="margin-bottom: 15px;"><strong>Institución:</strong> {{ $previewData['institution']['name'] ?? 'N/A' }}</p>
                @endif
                @if(isset($previewData['facultad']))
                    <p style="margin-bottom: 15px;">
                        <strong>Facultad:</strong> 
                        {{ $previewData['facultad']['nombre'] ?? 'N/A' }}
                    </p>
                @endif
                @if(isset($previewData['programa']))
                    <p style="margin-bottom: 15px;">
                        <strong>Programa:</strong> 
                        {{ $previewData['programa']['nombre'] ?? 'N/A' }}
                    </p>
                @endif
                <p style="margin-bottom: 15px;"><strong>Fecha de Registro:</strong> {{ $previewData['profesor']['created_at'] ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="professor-stats">
        <div class="professor-stat">
            <div class="professor-stat-title">Total de Evaluaciones</div>
            <div class="professor-stat-value">{{ $previewData['total_evaluaciones'] ?? 0 }}</div>
        </div>
        <div class="professor-stat">
            <div class="professor-stat-title">Evaluaciones Realizadas</div>
            <div class="professor-stat-value" style="color: #059669;">{{ $previewData['evaluaciones_realizadas'] ?? 0 }}</div>
        </div>
        <div class="professor-stat">
            <div class="professor-stat-title">Evaluaciones Pendientes</div>
            <div class="professor-stat-value" style="color: #dc2626;">{{ $previewData['evaluaciones_pendientes'] ?? 0 }}</div>
        </div>
        <div class="professor-stat">
            <div class="professor-stat-title">Tests Completados</div>
            <div class="professor-stat-value">{{ $previewData['tests_completados'] ?? 0 }}/{{ $previewData['total_tests'] ?? 0 }}</div>
        </div>
        <div class="professor-stat">
            <div class="professor-stat-title">Promedio General</div>
            <div class="professor-stat-value">{{ number_format($previewData['promedio_general'] ?? 0, 2) }}</div>
        </div>
        <div class="professor-stat">
            <div class="professor-stat-title">Puntuación Máxima</div>
            <div class="professor-stat-value" style="color: #059669;">{{ $previewData['puntuacion_maxima'] ?? 0 }}</div>
        </div>
        <div class="professor-stat">
            <div class="professor-stat-title">Puntuación Mínima</div>
            <div class="professor-stat-value" style="color: #dc2626;">{{ $previewData['puntuacion_minima'] ?? 0 }}</div>
        </div>
    </div>

    @if(isset($previewData['tests_asignados']) && count($previewData['tests_asignados']) > 0)
    <div class="professor-table-container">
        <h2 class="professor-section-title">Tests Asignados</h2>
        <table class="professor-table">
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
    <div class="professor-table-container">
        <h2 class="professor-section-title">Rendimiento por Área y Test</h2>
        
        <!-- Rendimiento por Test Individual -->
        @if(isset($previewData['tests_asignados']) && count($previewData['tests_asignados']) > 0)
            @foreach($previewData['tests_asignados'] as $test)
                @if($test['completado'] ?? false)
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #059669; font-size: 18px; font-weight: 600; margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid #e5e7eb;">
                        {{ $test['nombre'] ?? 'N/A' }} - Puntaje: {{ $test['puntaje'] }}/{{ $test['puntaje_maximo'] }}
                    </h3>
                    <table class="professor-table" style="margin-bottom: 20px;">
                        <thead>
                            <tr>
                                <th>Área</th>
                                <th>Puntaje Obtenido</th>
                                <th>Puntaje Máximo</th>
                                <th>Porcentaje</th>
                                <th>Nivel</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalPuntajeObtenido = 0;
                                $totalPuntajeMaximo = 0;
                            @endphp
                            @foreach($test['resultados_por_area'] as $area)
                            @php
                                $totalPuntajeObtenido += $area['puntaje_obtenido'];
                                $totalPuntajeMaximo += $area['puntaje_maximo'];
                            @endphp
                            <tr>
                                <td style="font-weight: 600; color: #374151;">{{ $area['area_name'] }}</td>
                                <td style="text-align: center; font-weight: 700; color: #059669;">{{ floor($area['puntaje_obtenido']) }}</td>
                                <td style="text-align: center;">{{ floor($area['puntaje_maximo']) }}</td>
                                <td style="text-align: center;">
                                    <div style="font-weight: 700; color: #059669;">{{ $area['porcentaje'] }}%</div>
                                    <div class="progress-bar" style="margin-top: 5px;">
                                        <div class="progress-fill" style="width: {{ $area['porcentaje'] }}%; background: linear-gradient(90deg, #059669, #047857);"></div>
                                    </div>
                                </td>
                                <td style="text-align: center; font-weight: 600;">
                                    @if($area['porcentaje'] >= 90)
                                        <span style="color: #059669;">A1</span>
                                    @elseif($area['porcentaje'] >= 80)
                                        <span style="color: #059669;">A2</span>
                                    @elseif($area['porcentaje'] >= 70)
                                        <span style="color: #d97706;">B1</span>
                                    @elseif($area['porcentaje'] >= 60)
                                        <span style="color: #d97706;">B2</span>
                                    @elseif($area['porcentaje'] >= 50)
                                        <span style="color: #dc2626;">C1</span>
                                    @else
                                        <span style="color: #dc2626;">C2</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @php
                                $porcentajeTotal = $totalPuntajeMaximo > 0 ? round(($totalPuntajeObtenido / $totalPuntajeMaximo) * 100, 1) : 0;
                            @endphp
                            <tr style="background-color: #f3f4f6; border-top: 2px solid #059669;">
                                <td style="font-weight: 700; color: #059669; font-size: 16px;">TOTAL</td>
                                <td style="text-align: center; font-weight: 700; color: #059669; font-size: 16px;">{{ floor($totalPuntajeObtenido) }}</td>
                                <td style="text-align: center; font-weight: 700; color: #059669; font-size: 16px;">{{ floor($totalPuntajeMaximo) }}</td>
                                <td style="text-align: center;">
                                    <div style="font-weight: 700; color: #059669; font-size: 16px;">{{ $porcentajeTotal }}%</div>
                                    <div class="progress-bar" style="margin-top: 5px;">
                                        <div class="progress-fill" style="width: {{ $porcentajeTotal }}%; background: linear-gradient(90deg, #059669, #047857);"></div>
                                    </div>
                                </td>
                                <td style="text-align: center; font-weight: 700; font-size: 16px;">
                                    @if($porcentajeTotal >= 90)
                                        <span style="color: #059669;">A1</span>
                                    @elseif($porcentajeTotal >= 80)
                                        <span style="color: #059669;">A2</span>
                                    @elseif($porcentajeTotal >= 70)
                                        <span style="color: #d97706;">B1</span>
                                    @elseif($porcentajeTotal >= 60)
                                        <span style="color: #d97706;">B2</span>
                                    @elseif($porcentajeTotal >= 50)
                                        <span style="color: #dc2626;">C1</span>
                                    @else
                                        <span style="color: #dc2626;">C2</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
            @endforeach
        @endif


    </div>
    @endif
</div>

<script>
    function verReporteFacultad(facultadId, facultadNombre) {
        // Cerrar el modal actual
        if (window.parent && window.parent.closeModal) {
            window.parent.closeModal();
        }
        
        // Esperar un momento y luego abrir el reporte de facultad
        setTimeout(() => {
            if (window.parent && window.parent.openFacultadReport) {
                window.parent.openFacultadReport(facultadId, facultadNombre);
            } else {
                // Fallback: redirigir a la página de reportes
                window.parent.location.href = `/admin/reports?tipo=facultad&facultad_id=${facultadId}`;
            }
        }, 300);
    }

    function verReportePrograma(programaId, programaNombre) {
        // Cerrar el modal actual
        if (window.parent && window.parent.closeModal) {
            window.parent.closeModal();
        }
        
        // Esperar un momento y luego abrir el reporte de programa
        setTimeout(() => {
            if (window.parent && window.parent.openProgramaReport) {
                window.parent.openProgramaReport(programaId, programaNombre);
            } else {
                // Fallback: redirigir a la página de reportes
                window.parent.location.href = `/admin/reports?tipo=programa&programa_id=${programaId}`;
            }
        }, 300);
    }
</script> 