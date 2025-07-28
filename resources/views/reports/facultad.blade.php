<style>
    .faculty-report {
        font-family: Arial, sans-serif;
        color: #333;
        line-height: 1.6;
    }
    .faculty-header {
        text-align: center;
        border-bottom: 3px solid #93c5fd;
        padding-bottom: 30px;
        margin-bottom: 40px;
    }
    .faculty-header h1 {
        color: #3b82f6;
        font-size: 2.5rem;
        margin: 0 0 10px 0;
    }
    .faculty-header h2 {
        color: #1d4ed8;
        font-size: 1.8rem;
        margin: 0 0 15px 0;
    }
    .faculty-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
    }
    .faculty-info h3 {
        color: #3b82f6;
        margin-top: 0;
    }
    .faculty-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    .faculty-stat {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 30px 25px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
    }
    .faculty-stat-title {
        color: #3b82f6;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 8px;
    }
    .faculty-stat-value {
        color: #1d4ed8;
        font-size: 1.8rem;
        font-weight: 700;
    }
    .faculty-table-container {
        background: white;
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 40px;
    }
    .faculty-section-title {
        color: #3b82f6;
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 30px;
        border-bottom: 2px solid #bae6fd;
        padding-bottom: 15px;
    }
    .faculty-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
    }
    .faculty-table th {
        background: #f0f9ff;
        color: #3b82f6;
        padding: 20px 15px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #bae6fd;
        font-size: 1.1rem;
    }
    .faculty-table td {
        padding: 18px 15px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
        font-size: 1rem;
    }
    .faculty-table tr:hover {
        background-color: #f8fafc;
    }
</style>

<div class="faculty-report">
    {{-- Botón de descarga --}}
    <div style="text-align: right; margin-bottom: 20px;">
        <a
            href="{{ auth()->user()->hasRole('Coordinador') ? route('coordinador.reports.pdf') : route('admin.reports.pdf') }}?tipo_reporte=facultad&entidad_id={{ $previewData['facultad']['id'] ?? '' }}&redirect=1"
            target="_blank"
            onclick="if(!confirm('¿Está seguro de que desea generar el reporte?')) return false; this.style.pointerEvents='none'; this.innerHTML='🔄 Generando...'; setTimeout(() => { @if(auth()->user()->hasRole('Coordinador')) window.location.href='{{ route('coordinador.reports.index') }}'; @else window.location.href='/admin/reports'; @endif }, 2000);"
            style="background-color: #3b82f6; color: white; padding: 12px 20px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 14px;"
        >
            ⬇️ Generar Reporte
        </a>
    </div>

    <div class="faculty-header">
        <h1>Reporte de Facultad</h1>
        <h2>{{ $previewData['facultad']['nombre'] ?? 'N/A' }}</h2>
        <div style="font-size: 1.1rem; margin-top: 0.5rem;">
            Fecha de Generación: {{ $previewData['fecha_generacion'] ?? now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="faculty-info">
        <h3>Información de la Facultad</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            <div>
                <p style="margin-bottom: 15px;"><strong>Nombre:</strong> {{ $previewData['facultad']['nombre'] ?? 'N/A' }}</p>
                @if(isset($previewData['institution']))
                    <p style="margin-bottom: 15px;"><strong>Institución:</strong> {{ $previewData['institution']['name'] ?? 'N/A' }}</p>
                @endif
            </div>
            <div>
                <p style="margin-bottom: 15px;"><strong>Total de Programas:</strong> {{ $previewData['total_programas'] ?? 0 }}</p>
                <p style="margin-bottom: 15px;"><strong>Total de Profesores:</strong> {{ $previewData['total_profesores'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="faculty-stats">
        <div class="faculty-stat">
            <div class="faculty-stat-title">Total de Profesores</div>
            <div class="faculty-stat-value">{{ $previewData['total_profesores'] ?? 0 }}</div>
        </div>
        <div class="faculty-stat">
            <div class="faculty-stat-title">Profesores Evaluados</div>
            <div class="faculty-stat-value" style="color: #059669;">{{ $previewData['profesores_evaluados'] ?? 0 }}</div>
        </div>
        <div class="faculty-stat">
            <div class="faculty-stat-title">Profesores Pendientes</div>
            <div class="faculty-stat-value" style="color: #dc2626;">{{ $previewData['profesores_pendientes'] ?? 0 }}</div>
        </div>
        <div class="faculty-stat">
            <div class="faculty-stat-title">Promedio General</div>
            <div class="faculty-stat-value">{{ number_format($previewData['promedio_general'] ?? 0, 2) }}</div>
        </div>
        <div class="faculty-stat">
            <div class="faculty-stat-title">Puntuación Máxima</div>
            <div class="faculty-stat-value" style="color: #059669;">{{ $previewData['puntuacion_maxima'] ?? 0 }}</div>
        </div>
        <div class="faculty-stat">
            <div class="faculty-stat-title">Puntuación Mínima</div>
            <div class="faculty-stat-value" style="color: #dc2626;">{{ $previewData['puntuacion_minima'] ?? 0 }}</div>
        </div>
    </div>

    @if(isset($previewData['programas']) && count($previewData['programas']) > 0)
    <div class="faculty-table-container">
        <h2 class="faculty-section-title">Programas de la Facultad</h2>
        <table class="faculty-table">
            <thead>
                <tr>
                    <th>Programa</th>
                    <th>Total Profesores</th>
                    <th>Profesores Evaluados</th>
                    <th>Profesores Pendientes</th>
                    <th>Promedio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($previewData['programas'] as $programa)
                <tr>
                    <td style="font-weight: 600; color: #1d4ed8;">{{ $programa['nombre'] ?? 'N/A' }}</td>
                    <td style="text-align: center;">{{ $programa['total_profesores'] ?? 0 }}</td>
                    <td style="text-align: center; color: #059669;">{{ $programa['profesores_evaluados'] ?? 0 }}</td>
                    <td style="text-align: center; color: #dc2626;">{{ $programa['profesores_pendientes'] ?? 0 }}</td>
                    <td style="text-align: center; font-weight: 700; color: #3b82f6;">{{ number_format($programa['promedio'] ?? 0, 2) }}</td>
                    <td style="text-align: center;">
                        @if(isset($programa['completado']) && $programa['completado'])
                            <span style="color: #059669; font-weight: 600;">✓ Completado</span>
                        @else
                            <span style="color: #dc2626; font-weight: 600;">⚠ Pendiente</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div> 