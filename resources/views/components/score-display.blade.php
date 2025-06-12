@props([
    'score',
    'percentage',
    'icon',
    'levelName' => 'Sin nivel',
    'levelDescription' => 'No ha alcanzado este nivel aún.',
    'maxScore' => 300,
    'percentileInfo' => null,
    'averageScore',
    'levelCode',
    'areaResults',
    'percentileProgram',
    'percentileInstitution',

    // Datos personales
    'applicationDate' => '13 DE DICIEMBRE DE 2020',
    'publicationDate' => '27 DE MARZO DE 2021',
    'evaluatedName' => 'EK202030694190',
    'identification' => 'CC 1098782994',
    'institution' => 'UNIVERSIDAD INDUSTRIAL DE SANTANDER-BUCARAMANGA',
    'program' => 'LICENCIATURA EN ESPAÑOL Y LITERATURA',

    // ID de asignación para generar PDF
    'assignmentId',
])


@if(!app()->runningInConsole() && Route::currentRouteName() !== 'realizar-test.pdf')
<div style="text-align: center; margin-bottom: 1rem;">
    <!-- Botón para descargar PDF desde servidor con estilos inline -->
    <a
        href="{{ route('realizar-test.pdf', ['id' => $assignmentId]) }}"
        style="
          background-color: #16a34a;
          color: #ffffff;
          font-weight: bold;
          padding: 12px 24px;
          border-radius: 9999px;
          text-decoration: none;
          box-shadow: 0 4px 6px rgba(0,0,0,0.1);
          display: inline-block;
        "
    >
        📥 Descargar PDF
    </a>
</div>
@endif
<!-- Título del informe -->
<div class="text-center mb-4">
    <h1 class="text-2xl font-bold text-gray-900">Informe de Evaluación de Competencias Digitales</h1>
</div>



<div class="bg-white border-2 border-gray-800 rounded-none shadow-lg overflow-hidden mx-6 my-4">
    <div class="p-6">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <!-- Columna izquierda - Datos personales -->
                <td style="width: 35%; border-right: 2px solid #1F2937; vertical-align: top; padding-right: 16px;">
                    <!-- Datos del examen -->
                    <div style="font-size: 0.875rem; line-height: 1.25rem;">
                        <div style="margin-bottom: 0.5rem;">
                            <strong>Fecha de asignación:</strong><br>
                            <span>{{ $applicationDate }}</span>
                        </div>
                        <div style="margin-bottom: 0.5rem;">
                            <strong>Fecha de finalización:</strong><br>
                            <span>{{ $publicationDate }}</span>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <strong>Nombre:</strong><br>
                            <span style="font-family: monospace;">{{ $evaluatedName }}</span>
                        </div>
                        <hr style="border-color: #9CA3AF; margin: 0.75rem 0;">
                        <div style="margin-bottom: 0.5rem;">
                            <strong>Identificación:</strong><br>
                            <span>{{ $identification }}</span>
                        </div>
                        <div style="margin-bottom: 0.5rem;">
                            <strong>Institución:</strong><br>
                            <span>{{ $institution }}</span>
                        </div>
                        <div>
                            <strong>Programa:</strong><br>
                            <span>{{ $program }}</span>
                        </div>
                    </div>
                </td>
                <!-- Columna derecha - Resultados globales -->
                <td style="width: 65%; padding-left: 16px; vertical-align: top;">
                    <!-- Sección de puntaje global -->
                    <div style="border-bottom: 2px solid #1F2937; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        <div style="display: table; width: 100%;">
                            <div style="display: table-cell; vertical-align: middle; width: 80%;">
                                <!-- Icono de puntaje global (puedes reemplazar por SVG inline si lo deseas) -->
                                <img src="data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjNEI1NTYzIiB2aWV3Qm94PSIwIDAgMjQgMjQiIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTIgM2MtMy45IDAtNyA0LjA5LTcgOSAwIDMuODMgMi43IDYuODMgNiA2Ljgzcy02IDMuMDEtNiA2LjgzYzAgNC45MSAzLjEgOSA3IDkgMy45IDAgNy00LjA5IDctOSAwLTMuODMtMi43LTYuODMtNi02LjgzczYtMy4wMSA2LTYuODNjMC00LjktMy4xLTktNy05em0wIDE2Yy0yLjIxIDAtNC0yLjQ5LTQtNS41cyAxLjc5LTUuNSA0LTUuNSA0IDIuNDkgNCA1LjUtMS43OSA1LjUtNCA1LjV6Ii8+PC9zdmc+" alt="" style="width: 48px; height: 48px; vertical-align: middle;">
                                <span style="font-size: 1rem; font-weight: bold; text-transform: uppercase; margin-left: 0.5rem;">Puntaje Global</span><br>
                                <span style="font-size: 0.875rem; color: #4B5563;">De {{ $maxScore }} puntos posibles</span>
                            </div>
                            <div style="display: table-cell; vertical-align: middle; width: 20%; text-align: right;">
                                <span style="font-size: 2rem; font-weight: 900;">{{ $score }}</span>
                                <div style="margin-top: 0.25rem; text-align: right;">
                                    <span style="font-size: 0.9rem; color: #374151; text-transform: uppercase; font-weight: 600; letter-spacing: 1px;">{{ $levelName }}</span><br>
                                    <span style="font-size: 1.2rem; font-weight: 800; color: #1F2937;">{{ $levelCode }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Nivel alcanzado -->
                    <div style="margin-bottom: 1rem; display: table; width: 100%;">
                        <div style="display: table-cell; vertical-align: top; width: 95%; padding-left: 0.5rem;">
                            <span style="font-weight: bold; font-size: 1rem;">{{ $levelName }} {{ $levelCode }}</span><br>
                            <span style="font-size: 0.875rem; color: #4B5563;">{{ $levelDescription }}</span>
                        </div>
                    </div>
                    <!-- Sección de percentiles -->
                    @if($percentileInfo)
                    <div style="margin-bottom: 1rem;">
                        <!-- Percentil Global -->
                        <div style="margin-bottom: 0.75rem;">
                            <p style="font-size: 0.875rem; color: #4B5563; font-weight: bold; margin: 0 0 0.25rem 0;">
                                Respecto a todos los demás docentes, usted está en el percentil {{ $averageScore }}%
                            </p>
                            <div style="width: 100%; background-color: #E5E7EB; height: 0.75rem; border-radius: 0.125rem; overflow: hidden;">
                                <div style="width: {{ $averageScore }}%; background-color: #4F46E5; height: 100%;"></div>
                            </div>
                        </div>
                        <!-- Percentil Institucional -->
                        <div style="margin-bottom: 0.75rem;">
                            <p style="font-size: 0.875rem; color: #4B5563; font-weight: bold; margin: 0 0 0.25rem 0;">
                                Respecto a los docentes de <span style="font-style: italic;">{{ $institution }}</span>, usted está en el percentil {{ $percentileInstitution }}%
                            </p>
                            <div style="width: 100%; background-color: #E5E7EB; height: 0.75rem; border-radius: 0.125rem; overflow: hidden;">
                                <div style="width: {{ $percentileInstitution }}%; background-color: #059669; height: 100%;"></div>
                            </div>
                        </div>
                        <!-- Percentil de Programa -->
                        <div>
                            <p style="font-size: 0.875rem; color: #4B5563; font-weight: bold; margin: 0 0 0.25rem 0;">
                                Respecto a los docentes del programa <span style="font-style: italic;">{{ $program }}</span>, usted está en el percentil {{ $percentileProgram }}%
                            </p>
                            <div style="width: 100%; background-color: #E5E7EB; height: 0.75rem; border-radius: 0.125rem; overflow: hidden;">
                                <div style="width: {{ $percentileProgram }}%; background-color: #D97706; height: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Barra de progreso final -->
                    <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px solid #D1D5DB; margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.875rem; color: #4B5563; margin-bottom: 0.5rem;">
                            <strong>Porcentaje obtenido</strong>
                            <strong>{{ $percentage }}%</strong>
                        </div>
                        <div style="width: 100%; background-color: #E5E7EB; height: 1rem; border: 1px solid #D1D5DB; border-radius: 0.125rem; overflow: hidden;">
                            <div style="width: {{ $percentage }}%; background-color: #1F2937; height: 100%;"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

@if(isset($areaResults) && $areaResults->count())
<div style="page-break-before: always;"></div>
<div class="mt-8 mx-6 mb-8">
    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-400 pb-2">Resultados por Área</h2>
    <div class="overflow-x-auto">
        <table style="width:100%; border-collapse: collapse; margin-top: 0.5rem;">
            <thead style="background-color: #E5E7EB;"> {{-- bg-gray-200 --}}
                <tr>
                    <th style="border: 1px solid #1F2937; padding: 0.5rem; text-align: left; font-weight: bold;">Área</th>
                    <th style="border: 1px solid #1F2937; padding: 0.5rem; text-align: center; font-weight: bold;">Puntaje alcanzado</th>
                    <th style="border: 1px solid #1F2937; padding: 0.5rem; text-align: center; font-weight: bold;">Puntaje posible</th>
                    <th style="border: 1px solid #1F2937; padding: 0.5rem; text-align: center; font-weight: bold;">Código de nivel</th>
                    <th style="border: 1px solid #1F2937; padding: 0.5rem; text-align: left; font-weight: bold;">Descripción del nivel</th>
                </tr>
            </thead>
            <tbody>
                @foreach($areaResults as $index => $area)
                    <tr style="{{ $index % 2 === 0 ? 'background-color: #FFFFFF;' : 'background-color: #F9FAFB;' }}"> {{-- filas alternas --}}
                        <td style="border: 1px solid #1F2937; padding: 0.5rem;">{{ $area['area_name'] }}</td>
                        <td style="border: 1px solid #1F2937; padding: 0.5rem; text-align: center;">{{ $area['obtained_score'] }}</td>
                        <td style="border: 1px solid #1F2937; padding: 0.5rem; text-align: center;">{{ $area['max_possible'] }}</td>
                        <td style="border: 1px solid #1F2937; padding: 0.5rem; text-align: center;">{{ $area['level_code'] }}</td>
                        <td style="border: 1px solid #1F2937; padding: 0.5rem;">{{ $area['level_description'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<style>
/* Evitar que DomPDF muestre URL de los enlaces en PDF */
a {
    text-decoration: none;
    color: inherit;
}
a[href]:after {
    content: "";
}
@media print {
    body, html {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
    }

    .print\:hidden,
    .no-print,
    .filament-sidebar,
    .filament-main-sidebar,
    .filament-header,
    .filament-navigation,
    header,
    footer,
    [class*="sidebar"],
    [class*="navigation"],
    [class*="navbar"] {
        display: none !important;
    }

    .shadow, .shadow-lg, .shadow-md,
    .rounded, .rounded-md, .rounded-lg,
    .border, .border-2, .border-r-2, .border-l-2, .border-t-2, .border-b-2 {
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
    }

    .bg-gray-50,
    .bg-white {
        background: white !important;
    }

    .text-gray-700,
    .text-gray-800,
    .text-gray-900 {
        color: black !important;
    }

    @page {
        size: A4 portrait;
        margin: 1.5cm;
    }

    h1, h2, h3, h4 {
        page-break-after: avoid;
    }

    table, tr, td, th {
        page-break-inside: avoid !important;
    }

    .page-break {
        page-break-after: always;
    }
}
body {
    margin: 0;
    padding: 0;
}
.mx-6 {
    margin-left: 1.5rem;
    margin-right: 1.5rem;
}
.mb-8 {
    margin-bottom: 2rem;
}
</style>
