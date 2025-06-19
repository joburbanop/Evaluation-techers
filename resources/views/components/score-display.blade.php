@props([
    'score',
    'percentage',
    'icon',
    'levelName' => 'Sin nivel',
    'levelDescription' => 'No ha alcanzado este nivel aún.',
    'maxScore' => 300,
    'percentileInfo' => null,
    'percentileRankGlobal',
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

@php
    \Log::info('Valores recibidos en score-display:', [
        'percentage' => $percentage,
        'score' => $score,
        'maxScore' => $maxScore
    ]);
@endphp

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

<div class="flex justify-center items-center w-full">
    <div class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl shadow-xl p-8 w-full max-w-4xl">
        <h1 class="text-2xl font-bold text-center text-gray-900 dark:text-gray-100 mb-8">Informe de Evaluación de Competencias Digitales</h1>
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Datos personales -->
            <div class="md:w-1/3 w-full flex flex-col gap-4 justify-start bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Fecha de asignación</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $applicationDate }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Fecha de finalización</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $publicationDate }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Nombre</div>
                    <div class="text-sm font-mono text-gray-900 dark:text-gray-100">{{ $evaluatedName }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Identificación</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $identification }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Institución</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $institution }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400 mb-1">Programa</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $program }}</div>
                </div>
            </div>
            <!-- Resultados globales -->
            <div class="md:w-2/3 w-full flex flex-col gap-6 justify-center">
                <div class="flex items-center gap-4">
                    <span class="inline-block w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                        <x-heroicon-o-academic-cap class="w-10 h-10 text-primary-600" />
                    </span>
                    <div>
                        <div class="text-lg font-bold uppercase text-gray-900 dark:text-gray-100">Puntaje Global</div>
                        <div class="text-sm text-gray-500 dark:text-gray-300">De {{ $maxScore }} puntos posibles</div>
                    </div>
                    <div class="ml-auto text-right">
                        <div class="text-5xl font-extrabold text-gray-900 dark:text-gray-100">{{ $score }}</div>
                        <div class="text-base font-semibold text-gray-700 dark:text-gray-200 uppercase">{{ $levelName }}</div>
                        <div class="text-xl font-extrabold text-primary-700 dark:text-primary-400">{{ $levelCode }}</div>
                    </div>
                </div>
                <div>
                    <div class="font-bold text-base text-gray-900 dark:text-gray-100">{{ $levelName }} {{ $levelCode }}</div>
                    <div class="text-sm text-gray-700 dark:text-gray-200 mt-1">{{ $levelDescription }}</div>
                </div>
                @if($percentileInfo)
                <div>
                    <div class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-1">
                        Respecto a todos los demás docentes, usted está en el percentil {{ $percentileRankGlobal }}%
                    </div>
                    <div class="relative w-full h-6 rounded-full border border-gray-300 dark:border-gray-600 overflow-hidden mt-2 bg-gray-200 dark:bg-gray-700">
                        <div class="absolute left-0 top-0 h-full rounded-full transition-all duration-500
                                    bg-gradient-to-r from-indigo-500 to-purple-500 dark:from-indigo-400 dark:to-purple-400"
                             style="width: {{ $percentileRankGlobal }}%">
                        </div>
                        <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">
                            {{ $percentileRankGlobal }}%
                        </span>
                    </div>
                </div>
                @endif
                <div>
                    <div class="flex justify-between text-sm text-gray-700 dark:text-gray-200 mb-1 mt-4">
                        <strong>Porcentaje obtenido</strong>
                        <strong>{{ $percentage }}%</strong>
                    </div>
                    <div class="relative w-full h-6 bg-gray-200 dark:bg-gray-700 rounded-full border border-gray-300 dark:border-gray-600 overflow-hidden mt-2">
                        <div class="absolute left-0 top-0 h-full rounded-full transition-all duration-500
                                    bg-gradient-to-r from-green-400 to-emerald-500 dark:from-green-300 dark:to-emerald-400"
                             style="width: {{ $percentage }}%">
                        </div>
                        <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">
                            {{ $percentage }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($areaResults) && $areaResults->count())
<div class="mt-8 mx-6 mb-8">
    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-400 dark:border-gray-600 pb-2">Resultados por Área</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm">
            <thead>
                <tr>
                    <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-left font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-100">Área</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-100">Puntaje alcanzado</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-100">Puntaje posible</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-100">Código de nivel</th>
                    <th class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-left font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-100">Descripción del nivel</th>
                </tr>
            </thead>
            <tbody>
                @foreach($areaResults as $index => $area)
                    <tr class="{{ $index % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }}">
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-900 dark:text-gray-100">{{ $area['area_name'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center text-gray-900 dark:text-gray-100">{{ $area['obtained_score'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center text-gray-900 dark:text-gray-100">{{ $area['max_possible'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-center text-gray-900 dark:text-gray-100">{{ $area['level_code'] }}</td>
                        <td class="border border-gray-300 dark:border-gray-600 px-3 py-2 text-gray-900 dark:text-gray-100">{{ $area['level_description'] }}</td>
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

