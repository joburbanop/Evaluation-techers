@props([
    'score',
    'percentage',
    'icon',
    'levelName' => 'Sin nivel',
    'levelDescription' => 'No ha alcanzado este nivel aún.',
    'maxScore' => 300,
    'percentileInfo' => null,
    'percentileRankGlobal',
    'percentileRankFacultad' => 0,
    'percentileRankPrograma' => 0,
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
    'facultad' => 'FACULTAD DE CIENCIAS',
    'program' => 'LICENCIATURA EN ESPAÑOL Y LITERATURA',

    // ID de asignación para generar PDF
    'assignmentId',

    // Nombre dinámico del test
    'testName' => 'Informe de Evaluación',
])

@php
    \Log::info('Valores recibidos en score-display:', [
        'percentage' => $percentage,
        'score' => $score,
        'maxScore' => $maxScore
    ]);
@endphp

@if(app()->runningInConsole() || Route::currentRouteName() === 'realizar-test.pdf')
    <div style="background: #fff; color: #000; font-family: Arial, sans-serif; padding: 24px;">
        <h1 style="text-align: center; font-size: 2rem; font-weight: bold; margin-bottom: 24px;">
            {{ $testName }}
        </h1>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
            <tr>
                <td style="width: 40%; vertical-align: top; padding-right: 16px;">
                    <strong>Fecha de asignación:</strong> {{ $applicationDate }}<br>
                    <strong>Fecha de finalización:</strong> {{ $publicationDate }}<br>
                    <strong>Nombre:</strong> {{ $evaluatedName }}<br>
                    <strong>Identificación:</strong> {{ $identification }}<br>
                    <strong>Institución:</strong> {{ $institution }}<br>
                    <strong>Facultad:</strong> {{ $facultad }}<br>
                    <strong>Programa:</strong> {{ $program }}
                </td>
                <td style="width: 60%; vertical-align: top; padding-left: 16px;">
                    <strong>Puntaje Global:</strong> {{ $score }} / {{ $maxScore }}<br>
                    <strong>Nivel:</strong> {{ $levelName }} ({{ $levelCode }})<br>
                    <strong>Descripción:</strong> {{ $levelDescription }}<br>
                    <strong>Porcentaje obtenido:</strong> {{ $percentage }}%<br>
                    <strong>Resultados globales:</strong> {{ $percentileRankGlobal }}%<br>
                    <strong>Resultados por facultad:</strong> {{ $percentileRankFacultad }}%<br>
                    <strong>Resultados por programa:</strong> {{ $percentileRankPrograma }}%
                </td>
            </tr>
        </table>
        @if(isset($areaResults) && $areaResults->count())
            <h2 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 12px;">Resultados por Área</h2>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                <thead>
                    <tr style="background: #eee;">
                        <th style="border: 1px solid #ccc; padding: 8px; text-align: left; width: 25%;">Área</th>
                        <th style="border: 1px solid #ccc; padding: 8px; text-align: center; width: 15%;">Puntaje alcanzado</th>
                        <th style="border: 1px solid #ccc; padding: 8px; text-align: center; width: 15%;">Puntaje posible</th>
                        <th style="border: 1px solid #ccc; padding: 8px; text-align: center; width: 15%;">Código de nivel</th>
                        <th style="border: 1px solid #ccc; padding: 8px; text-align: left; width: 30%;">Descripción del nivel</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($areaResults as $area)
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 8px; word-wrap: break-word;">{{ $area['area_name'] }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px; text-align: center;">{{ $area['obtained_score'] }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px; text-align: center;">{{ $area['max_possible'] }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px; text-align: center;">{{ $area['level_code'] }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px; word-wrap: break-word;">{{ $area['level_description'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    @php return; @endphp
@endif

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
    <div class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl shadow-xl p-8 lg:p-12 w-full max-w-6xl">
        <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-100 mb-10">{{ $testName }}</h1>
        
        <div class="flex flex-col md:flex-row gap-10 lg:gap-12">
            <!-- Columna Izquierda: Datos del Evaluado -->
            <div class="md:w-1/3 w-full bg-gray-50 dark:bg-gray-800 rounded-xl p-6 lg:p-8 border border-gray-200 dark:border-gray-700 flex flex-col space-y-6">
                <div class="text-center pb-4 border-b border-gray-200 dark:border-gray-700">
                    <span class="inline-block p-3 bg-blue-100 dark:bg-blue-900 rounded-full mb-3">
                        <x-heroicon-o-user class="w-8 h-8 text-blue-600 dark:text-blue-400" />
                    </span>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Información del Docente</h2>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Nombre</div>
                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 break-words leading-relaxed">{{ $evaluatedName }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Identificación</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100 break-words">{{ $identification }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Institución</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100 break-words">{{ $institution }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Facultad</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100 break-words">{{ $facultad }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Programa</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100 break-words">{{ $program }}</div>
                </div>
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Fecha de asignación</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100 break-words">{{ $applicationDate }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Fecha de finalización</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100 break-words">{{ $publicationDate }}</div>
                </div>
            </div>

            <!-- Columna Derecha: Resultados -->
            <div class="md:w-2/3 w-full flex flex-col gap-8">
                <!-- Tarjeta de Puntaje y Nivel -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <div class="flex-shrink-0">
                            <span class="inline-block p-4 bg-primary-100 rounded-full">
                                <x-heroicon-o-academic-cap class="w-10 h-10 text-primary-600" />
                            </span>
                        </div>
                        <div class="flex-grow text-center sm:text-left">
                            <div class="text-lg font-bold uppercase text-gray-900 dark:text-gray-100">Puntaje Global</div>
                            <div class="text-sm text-gray-500 dark:text-gray-300">De {{ $maxScore }} puntos posibles</div>
                        </div>
                        <div class="flex-shrink-0 text-center sm:text-right">
                            <div class="text-5xl font-extrabold text-gray-900 dark:text-gray-100">{{ $score }}</div>
                            <div class="text-base font-semibold text-gray-700 dark:text-gray-200 uppercase">{{ $levelName }} {{ $levelCode }}</div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Descripción del Nivel -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-2">{{ $levelName }} {{ $levelCode }}</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-200 leading-relaxed">{{ $levelDescription }}</p>
                </div>

                <!-- Tarjetas de Resultados y Porcentaje -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    @if($percentileInfo)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">
                            Resultados globales
                        </div>
                         <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Respecto a todos los docentes</p>
                        <div class="relative w-full h-6 rounded-full border border-gray-300 dark:border-gray-600 overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <div class="absolute left-0 top-0 h-full rounded-full transition-all duration-500 bg-gradient-to-r from-indigo-500 to-purple-500 dark:from-indigo-400 dark:to-purple-400" style="width: {{ $percentileRankGlobal }}%"></div>
                            <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white tracking-wider">{{ $percentileRankGlobal }}%</span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">
                            Resultados por facultad
                        </div>
                         <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Respecto a la facultad {{ $facultad }}</p>
                        <div class="relative w-full h-6 rounded-full border border-gray-300 dark:border-gray-600 overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <div class="absolute left-0 top-0 h-full rounded-full transition-all duration-500 bg-gradient-to-r from-blue-500 to-cyan-500 dark:from-blue-400 dark:to-cyan-400" style="width: {{ $percentileRankFacultad }}%"></div>
                            <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white tracking-wider">{{ $percentileRankFacultad }}%</span>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">
                            Resultados por programa
                        </div>
                         <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Respecto al programa {{ $program }}</p>
                        <div class="relative w-full h-6 rounded-full border border-gray-300 dark:border-gray-600 overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <div class="absolute left-0 top-0 h-full rounded-full transition-all duration-500 bg-gradient-to-r from-emerald-500 to-teal-500 dark:from-emerald-400 dark:to-teal-400" style="width: {{ $percentileRankPrograma }}%"></div>
                            <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white tracking-wider">{{ $percentileRankPrograma }}%</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center text-sm font-bold text-gray-700 dark:text-gray-200 mb-3">
                            <span>Porcentaje obtenido</span>
                            <span class="text-lg">{{ $percentage }}%</span>
                        </div>
                        <div class="relative w-full h-6 bg-gray-200 dark:bg-gray-700 rounded-full border border-gray-300 dark:border-gray-600 overflow-hidden">
                            <div class="absolute left-0 top-0 h-full rounded-full transition-all duration-500 bg-gradient-to-r from-green-400 to-emerald-500 dark:from-green-300 dark:to-emerald-400" style="width: {{ $percentage }}%"></div>
                            <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white tracking-wider">{{ $percentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($areaResults) && $areaResults->count())
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 pb-3 border-b-2 border-gray-200 dark:border-gray-700">Resultados por Área</h2>
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-sm align-middle">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th scope="col" class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Área</th>
                            <th scope="col" class="px-6 py-4 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Puntaje alcanzado</th>
                            <th scope="col" class="px-6 py-4 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Puntaje posible</th>
                            <th scope="col" class="px-6 py-4 text-center font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Código de nivel</th>
                            <th scope="col" class="px-6 py-4 text-left font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Descripción del nivel</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($areaResults as $area)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-100">{{ $area['area_name'] }}</td>
                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-300">{{ $area['obtained_score'] }}</td>
                                <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-300">{{ $area['max_possible'] }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-800 dark:text-gray-100">{{ $area['level_code'] }}</td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300 leading-relaxed">{{ $area['level_description'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

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

