@php
    // Calcular el nivel global y porcentaje
    $totalScore = $record->responses->sum(function ($response) {
        return $response->option->score ?? 0;
    });
    
    $maxPossibleScore = $record->responses->sum(function ($response) {
        return $response->question->options->max('score');
    });
    
    $globalPercentage = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;
    $globalLevel = \App\Models\CompetencyLevel::getLevelByScore($globalPercentage);
    
    if (!$globalLevel) {
        $globalLevel = [
            'name' => 'Sin nivel',
            'color' => 'gray'
        ];
    }
@endphp

<div class="space-y-8">
    {{-- Header Section --}}
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Resultados del Test</h1>
                <p class="text-gray-600 mt-1">Detalle completo del desempeño en la evaluación</p>
            </div>
            <div class="bg-indigo-50 rounded-lg px-4 py-2">
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Nivel Global</p>
                <p class="text-2xl font-bold text-indigo-700 mt-1">{{ $globalLevel['name'] }}</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="h-2 rounded-full" style="width: {{ $globalPercentage }}%; background-color: {{ match($globalLevel['color']) {
                        'emerald' => '#10b981',
                        'blue' => '#3b82f6',
                        'amber' => '#f59e0b',
                        'orange' => '#f97316',
                        'red' => '#ef4444',
                        default => '#3b82f6'
                    } }}"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1 text-right">{{ $globalPercentage }}%</p>
            </div>
        </div>
    </div>

    {{-- General Information Card --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Información General
            </h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Usuario
                    </p>
                    <p class="text-lg font-medium text-gray-900">{{ $record->user->name }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Test
                    </p>
                    <p class="text-lg font-medium text-gray-900">{{ $record->test->name }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-500 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Fecha
                    </p>
                    <p class="text-lg font-medium text-gray-900">{{ $record->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    

    {{-- Area Scores --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Puntuación por Área
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($areaScores as $area)
                @php
                    $bgColor = match($area['level']['color']) {
                        'emerald' => 'bg-emerald-50',
                        'blue' => 'bg-blue-50',
                        'amber' => 'bg-amber-50',
                        'orange' => 'bg-orange-50',
                        'red' => 'bg-red-50',
                        default => 'bg-gray-50'
                    };
                    $borderColor = match($area['level']['color']) {
                        'emerald' => 'border-emerald-100',
                        'blue' => 'border-blue-100',
                        'amber' => 'border-amber-100',
                        'orange' => 'border-orange-100',
                        'red' => 'border-red-100',
                        default => 'border-gray-100'
                    };
                    $textColor = match($area['level']['color']) {
                        'emerald' => 'text-emerald-800',
                        'blue' => 'text-blue-800',
                        'amber' => 'text-amber-800',
                        'orange' => 'text-orange-800',
                        'red' => 'text-red-800',
                        default => 'text-gray-800'
                    };
                    $badgeBgColor = match($area['level']['color']) {
                        'emerald' => 'bg-emerald-100',
                        'blue' => 'bg-blue-100',
                        'amber' => 'bg-amber-100',
                        'orange' => 'bg-orange-100',
                        'red' => 'bg-red-100',
                        default => 'bg-gray-100'
                    };
                    $progressColor = match($area['level']['color']) {
                        'emerald' => 'bg-emerald-600',
                        'blue' => 'bg-blue-600',
                        'amber' => 'bg-amber-600',
                        'orange' => 'bg-orange-600',
                        'red' => 'bg-red-600',
                        default => 'bg-gray-600'
                    };
                @endphp
                <div class="{{ $bgColor }} p-5 rounded-lg border {{ $borderColor }} shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $area['area'] }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $area['totalScore'] }}/{{ $area['maxScore'] }} puntos</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeBgColor }} {{ $textColor }}">
                            {{ $area['level']['name'] }}
                        </span>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progreso</span>
                            <span>{{ $area['score'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full {{ $progressColor }}" style="width: {{ $area['score'] }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Detailed Responses --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Respuestas Detalladas
            </h2>
            <div class="space-y-4">
                @foreach($responses as $response)
                <div class="rounded-lg p-5 border-l-4 {{ $response->is_correct ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            @if($response->is_correct)
                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            @else
                            <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">{{ $response->question->question }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $response->is_correct ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $response->is_correct ? 'Correcta' : 'Incorrecta' }}
                                </span>
                            </div>
                            <div class="mt-2 text-sm text-gray-700">
                                <p><span class="font-semibold">Tu respuesta:</span> {{ $response->option->option }}</p>
                                @if(!$response->is_correct)
                                    @php
                                        $correctOption = $response->question->options->firstWhere('is_correct', true);
                                    @endphp
                                    <p class="mt-1"><span class="font-semibold">Respuesta correcta:</span> {{ $correctOption->option ?? 'No disponible' }}</p>
                                @endif
                            </div>
                            @if($response->feedback)
                            <div class="mt-3 p-3 bg-white rounded-md border border-gray-200">
                                <p class="text-sm font-medium text-gray-700">Retroalimentación:</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $response->feedback }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Radar Chart
        const radarCtx = document.getElementById('radarChart');
        if (radarCtx) {
            const data = JSON.parse(radarCtx.dataset.chart);
            
            if (data.length > 0) {
                new Chart(radarCtx, {
                    type: 'radar',
                    data: {
                        labels: data.map(item => item.area),
                        datasets: [{
                            label: 'Nivel de Competencia (%)',
                            data: data.map(item => item.score),
                            backgroundColor: 'rgba(79, 70, 229, 0.2)',
                            borderColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                            pointBorderColor: '#fff',
                            pointHoverRadius: 6,
                            pointRadius: 4,
                            pointHitRadius: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                angleLines: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    backdropColor: 'transparent',
                                    stepSize: 20,
                                    min: 0,
                                    max: 100
                                },
                                pointLabels: {
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    },
                                    padding: 20
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.raw + '%';
                                    }
                                }
                            }
                        },
                        elements: {
                            line: {
                                tension: 0.1
                            }
                        }
                    }
                });
            } else {
                radarCtx.parentElement.innerHTML = `
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay datos suficientes</h3>
                        <p class="mt-1 text-sm text-gray-500">No se encontraron resultados para mostrar el gráfico.</p>
                    </div>
                `;
            }
        }
    });
</script>
@endpush