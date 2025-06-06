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

    // Datos personales
    'applicationDate' => '13 DE DICIEMBRE DE 2020',
    'publicationDate' => '27 DE MARZO DE 2021',
    'evaluatedName' => 'EK202030694190',
    'identification' => 'CC 1098782994',
    'institution' => 'UNIVERSIDAD INDUSTRIAL DE SANTANDER-BUCARAMANGA',
    'program' => 'LICENCIATURA EN ESPAÑOL Y LITERATURA',
])

<!-- Contenedor principal con layout de dos columnas -->
<div class="bg-white border-2 border-gray-800 rounded-none shadow-lg overflow-hidden">
    <div class="flex flex-col lg:flex-row">
        <!-- Columna izquierda - Datos personales -->
        <div class="lg:w-1/3 border-r-2 border-gray-800 p-6 bg-gray-50">
            <!-- Datos del examen -->
            <div class="space-y-4 text-sm">
                <div>
                    <span class="font-bold text-gray-800">Fecha de asignación:</span>
                    <div class="text-gray-700">{{ $applicationDate }}</div>
                </div>
                
                <div>
                    <span class="font-bold text-gray-800">Fecha de finalización:</span>
                    <div class="text-gray-700">{{ $publicationDate }}</div>
                </div>
                
                <div>
                    <span class="font-bold text-gray-800">Nombre:</span>
                    <div class="text-gray-700 font-mono">{{ $evaluatedName }}</div>
                </div>
            </div>
            
            <hr class="my-6 border-gray-400">
            
            <!-- Datos personales -->
            <div class="space-y-4 text-sm">
                <div>
                    <span class="font-bold text-gray-800">Identificación:</span>
                    <div class="text-gray-700">{{ $identification }}</div>
                </div>
                
                <div>
                    <span class="font-bold text-gray-800">Institución:</span>
                    <div class="text-gray-700 text-xs leading-tight">{{ $institution }}</div>
                </div>
                
                <div>
                    <span class="font-bold text-gray-800">Programa:</span>
                    <div class="text-gray-700 text-xs leading-tight">{{ $program }}</div>
                </div>
            </div>
        </div>
        
        <!-- Columna derecha - Resultados -->
        <div class="lg:w-2/3 flex flex-col">
            <div class="p-6 flex-1">
                <!-- Sección de puntaje global -->
                <div class="border-b-2 border-gray-800 pb-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <x-dynamic-component
                                :component="$icon"
                                class="w-12 h-12 text-gray-700"
                            />
                            <div>
                                <h3 class="text-base font-bold text-gray-900 uppercase">Puntaje Global</h3>
                                <p class="text-sm text-gray-600">De {{ $maxScore }} puntos posibles</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-4xl font-black text-gray-900">{{ $score }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Nivel alcanzado -->
                <div class="mb-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-gray-800 text-white rounded-full flex items-center justify-center text-xs font-bold">
                            ▲
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 mb-1">{{ $levelName }} {{ $levelCode }}</h4>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $levelDescription }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información de percentil si está disponible -->
                @if($percentileInfo)
                <div class="mt-6 space-y-6">
                    <!-- Comparación 1: Respecto a todos los docentes -->
                    <div>
                        <p class="text-sm text-gray-700 mb-3 font-semibold">
                            Respecto a todos los demás docentes, usted está aquí.
                        </p>
                        <div class="relative mb-2">
                            <div class="w-full bg-gray-200 h-4 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-600 transition-all duration-500 ease-out"
                                     style="width: {{ $averageScore }}%">
                                </div>
                            </div>
                            <div class="absolute -top-6 transform -translate-x-1/2"
                                 style="left: {{ $averageScore }}%">
                                <span class="text-xs font-bold text-gray-800">{{ $averageScore }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Comparación 2: Respecto a la institución -->
                    <div>
                        <p class="text-sm text-gray-700 mb-3 font-semibold">
                            Respecto a los docentes de <span class="italic font-semibold">{{ $institution }}</span>, usted está aquí.
                        </p>
                        <div class="relative mb-2">
                            <div class="w-full bg-gray-200 h-4 rounded-full overflow-hidden">
                                <div class="h-full bg-green-600 transition-all duration-500 ease-out"
                                     style="width: {{ $percentage }}%">
                                </div>
                            </div>
                            <div class="absolute -top-6 transform -translate-x-1/2"
                                 style="left: {{ $percentage }}%">
                                <span class="text-xs font-bold text-gray-800">{{ $percentage }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Comparación 3: Respecto al programa -->
                    <div>
                        <p class="text-sm text-gray-700 mb-3 font-semibold">
                            Respecto a los docentes del programa <span class="italic font-semibold">{{ $program }}</span>, usted está aquí.
                        </p>
                        <div class="relative mb-2">
                            <div class="w-full bg-gray-200 h-4 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-600 transition-all duration-500 ease-out"
                                     style="width: {{ $percentage }}%">
                                </div>
                            </div>
                            <div class="absolute -top-6 transform -translate-x-1/2"
                                 style="left: {{ $percentage }}%">
                                <span class="text-xs font-bold text-gray-800">{{ $percentage }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Barra de progreso final -->
                <div class="mt-8 pt-6 border-t border-gray-300">
                    <div class="flex justify-between mb-2 text-sm text-gray-700">
                        <span class="font-semibold">Porcentaje obtenido</span>
                        <span class="font-bold">{{ $percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 h-4 border border-gray-300 rounded-sm overflow-hidden">
                        <div
                            class="h-full bg-gray-800 transition-all duration-500 ease-out"
                            style="width: {{ $percentage }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($areaResults) && $areaResults->count())
<h1>{{$areaResults->count()}}</h1>
    <div class="mt-8 p-6">
        <h3 class="text-lg font-bold mb-4">Resultados por Área</h3>
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr>
                    <th class="border px-4 py-2 text-left">Área</th>
                    <th class="border px-4 py-2 text-left">Puntaje Alcanzado</th>
                    <th class="border px-4 py-2 text-left">Puntaje Posible</th>
                    <th class="border px-4 py-2 text-left">Código de Nivel</th>
                    <th class="border px-4 py-2 text-left">Descripción del Nivel</th>
                </tr>
            </thead>
            <tbody>
                @foreach($areaResults as $area)
                    <tr>
                        <td class="border px-4 py-2">{{ $area['area_name'] }}</td>
                        <td class="border px-4 py-2">{{ $area['obtained_score'] }}</td>
                        <td class="border px-4 py-2">{{ $area['max_possible'] }}</td>
                        <td class="border px-4 py-2">{{ $area['level_code'] }}</td>
                        <td class="border px-4 py-2">{{ $area['level_description'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif