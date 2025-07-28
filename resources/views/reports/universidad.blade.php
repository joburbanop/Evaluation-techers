<style>
    .university-report {
        font-family: Arial, sans-serif;
        color: #333;
        line-height: 1.6;
    }
    .university-header {
        text-align: center;
        border-bottom: 3px solid #93c5fd;
        padding-bottom: 30px;
        margin-bottom: 40px;
    }
    .university-header h1 {
        color: #3b82f6;
        font-size: 2.5rem;
        margin: 0 0 10px 0;
    }
    .university-header h2 {
        color: #1d4ed8;
        font-size: 1.8rem;
        margin: 0 0 15px 0;
    }
    .university-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 1px 3px rgba(147, 197, 253, 0.2);
    }
    .university-info h3 {
        color: #3b82f6;
        margin-top: 0;
    }
    .university-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }
    .university-stat {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 30px 25px;
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
        padding: 35px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 40px;
    }
    .university-section-title {
        color: #3b82f6;
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 30px;
        border-bottom: 2px solid #bae6fd;
        padding-bottom: 15px;
    }
    .university-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
    }
    .university-table th {
        background: #f0f9ff;
        color: #3b82f6;
        padding: 20px 15px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #bae6fd;
        font-size: 1.1rem;
    }
    .university-table td {
        padding: 18px 15px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
        font-size: 1rem;
    }
    .university-table tr:hover {
        background-color: #f8fafc;
    }
</style>

<div class="university-report">
    {{-- Botón de descarga --}}
    <div style="text-align: right; margin-bottom: 20px;">
        <a
            href="{{ auth()->user()->hasRole('Coordinador') ? route('coordinador.reports.pdf') : route('admin.reports.pdf') }}?tipo_reporte=universidad&entidad_id={{ $previewData['institution']['id'] ?? '' }}&redirect=1"
            target="_blank"
            onclick="if(!confirm('¿Está seguro de que desea generar el reporte?')) return false; this.style.pointerEvents='none'; this.innerHTML='🔄 Generando...'; setTimeout(() => { @if(auth()->user()->hasRole('Coordinador')) window.location.href='{{ route('coordinador.reports.index') }}'; @else window.location.href='/admin/reports'; @endif }, 2000);"
            style="background-color: #3b82f6; color: white; padding: 12px 20px; border-radius: 8px; border: none; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 14px;"
        >
            ⬇️ Generar Reporte
        </a>
    </div>

    <div class="university-header">
        <h1>Reporte de Universidad</h1>
        <h2>{{ $previewData['institution']['name'] ?? 'N/A' }}</h2>
        <div style="font-size: 1.1rem; margin-top: 0.5rem;">
            Fecha de Generación: {{ $previewData['fecha_generacion'] ?? now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="university-info">
        <h3>Información de la Institución</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            <div>
                <p style="margin-bottom: 15px;"><strong>Nombre:</strong> {{ $previewData['institution']['name'] ?? 'N/A' }}</p>
                <p style="margin-bottom: 15px;"><strong>Carácter Académico:</strong> {{ $previewData['institution']['academic_character'] ?? 'No especificado' }}</p>
            </div>
            <div>
                <p style="margin-bottom: 15px;"><strong>Departamento:</strong> {{ $previewData['institution']['departamento_domicilio'] ?? 'No especificado' }}</p>
                <p style="margin-bottom: 15px;"><strong>Municipio:</strong> {{ $previewData['institution']['municipio_domicilio'] ?? 'No especificado' }}</p>
            </div>
        </div>
    </div>

    <div class="university-stats">
        <div class="university-stat">
            <div class="university-stat-title">Total de Facultades</div>
            <div class="university-stat-value">{{ $previewData['total_facultades'] ?? 0 }}</div>
        </div>
        <div class="university-stat">
            <div class="university-stat-title">Total de Programas</div>
            <div class="university-stat-value">{{ $previewData['total_programas'] ?? 0 }}</div>
        </div>
        <div class="university-stat">
            <div class="university-stat-title">Total de Profesores</div>
            <div class="university-stat-value">{{ $previewData['total_profesores'] ?? 0 }}</div>
        </div>
        <div class="university-stat">
            <div class="university-stat-title">Profesores Evaluados</div>
            <div class="university-stat-value" style="color: #059669;">{{ $previewData['profesores_evaluados'] ?? 0 }}</div>
        </div>
        <div class="university-stat">
            <div class="university-stat-title">Profesores Pendientes</div>
            <div class="university-stat-value" style="color: #dc2626;">{{ $previewData['profesores_pendientes'] ?? 0 }}</div>
        </div>
        <div class="university-stat">
            <div class="university-stat-title">Promedio General</div>
            <div class="university-stat-value">{{ number_format($previewData['promedio_general'] ?? 0, 2) }}</div>
        </div>
    </div>

    @if(isset($previewData['facultades']) && count($previewData['facultades']) > 0)
    <div class="university-table-container">
        <h2 class="university-section-title">Facultades de la Universidad</h2>
        <table class="university-table">
            <thead>
                <tr>
                    <th>Facultad</th>
                    <th>Total Programas</th>
                    <th>Total Profesores</th>
                    <th>Profesores Evaluados</th>
                    <th>Profesores Pendientes</th>
                    <th>Promedio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($previewData['facultades'] as $facultad)
                <tr>
                    <td style="font-weight: 600; color: #1d4ed8;">{{ $facultad['nombre'] ?? 'N/A' }}</td>
                    <td style="text-align: center;">{{ $facultad['total_programas'] ?? 0 }}</td>
                    <td style="text-align: center;">{{ $facultad['total_profesores'] ?? 0 }}</td>
                    <td style="text-align: center; color: #059669;">{{ $facultad['profesores_evaluados'] ?? 0 }}</td>
                    <td style="text-align: center; color: #dc2626;">{{ $facultad['profesores_pendientes'] ?? 0 }}</td>
                    <td style="text-align: center; font-weight: 700; color: #3b82f6;">{{ number_format($facultad['promedio'] ?? 0, 2) }}</td>
                    <td style="text-align: center;">
                        @if(isset($facultad['completado']) && $facultad['completado'])
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