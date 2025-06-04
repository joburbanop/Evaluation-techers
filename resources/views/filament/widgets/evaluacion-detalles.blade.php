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
            'code' => '',
            'color' => 'gray'
        ];
    }

    // Definir colores para el nivel global
    $globalBgColor = match($globalLevel['color']) {
        'emerald' => 'bg-gradient-to-r from-emerald-50 to-emerald-100',
        'blue' => 'bg-gradient-to-r from-blue-50 to-blue-100',
        'amber' => 'bg-gradient-to-r from-amber-50 to-amber-100',
        'orange' => 'bg-gradient-to-r from-orange-50 to-orange-100',
        'red' => 'bg-gradient-to-r from-red-50 to-red-100',
        default => 'bg-gradient-to-r from-gray-50 to-gray-100'
    };

    $globalTextColor = match($globalLevel['color']) {
        'emerald' => 'text-emerald-700',
        'blue' => 'text-blue-700',
        'amber' => 'text-amber-700',
        'orange' => 'text-orange-700',
        'red' => 'text-red-700',
        default => 'text-gray-700'
    };

    $globalBorderColor = match($globalLevel['color']) {
        'emerald' => 'border-emerald-200',
        'blue' => 'border-blue-200',
        'amber' => 'border-amber-200',
        'orange' => 'border-orange-200',
        'red' => 'border-red-200',
        default => 'border-gray-200'
    };
@endphp

<div class="space-y-8">
    {{-- Header Section --}}
    <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-xl shadow-lg p-6 border border-indigo-200">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-indigo-900">Resultados del Test</h1>
                <p class="text-indigo-600 mt-1">Detalle completo del desempeño en la evaluación</p>
            </div>
            <div class="{{ $globalBgColor }} rounded-lg px-6 py-4 border {{ $globalBorderColor }} shadow-md">
                <p class="text-xs font-semibold {{ $globalTextColor }} uppercase tracking-wider">Nivel Global</p>
                <p class="text-3xl font-bold {{ $globalTextColor }} mt-2">{{ $globalLevel['name'] }} {{ $globalLevel['code'] ? "({$globalLevel['code']})" : '' }}</p>
                <div class="w-full bg-white/50 rounded-full h-3 mt-3">
                    <div class="h-3 rounded-full" style="width: {{ $globalPercentage }}%; background-color: {{ match($globalLevel['color']) {
                        'emerald' => '#10b981',
                        'blue' => '#3b82f6',
                        'amber' => '#f59e0b',
                        'orange' => '#f97316',
                        'red' => '#ef4444',
                        default => '#3b82f6'
                    } }}"></div>
                </div>
                <p class="text-sm {{ $globalTextColor }} mt-2 text-right font-medium">{{ $globalPercentage }}%</p>
            </div>
        </div>
    </div>

    {{-- General Information Card --}}
    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl shadow-lg overflow-hidden border border-purple-200">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-purple-900 mb-6 flex items-center">
                <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Información General
            </h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white/50 rounded-lg p-4 border border-purple-100 shadow-sm">
                    <p class="text-sm font-medium text-purple-600 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Usuario
                    </p>
                    <p class="text-lg font-medium text-purple-900 mt-1">{{ $record->user->name }}</p>
                </div>
                <div class="bg-white/50 rounded-lg p-4 border border-purple-100 shadow-sm">
                    <p class="text-sm font-medium text-purple-600 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Test
                    </p>
                    <p class="text-lg font-medium text-purple-900 mt-1">{{ $record->test->name }}</p>
                </div>
                <div class="bg-white/50 rounded-lg p-4 border border-purple-100 shadow-sm">
                    <p class="text-sm font-medium text-purple-600 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Fecha
                    </p>
                    <p class="text-lg font-medium text-purple-900 mt-1">{{ $record->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Area Scores --}}
    <div class="bg-gradient-to-r from-pink-50 to-pink-100 rounded-xl shadow-lg overflow-hidden border border-pink-200">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-pink-900 mb-6 flex items-center">
                <svg class="w-5 h-5 text-pink-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Puntuación por Área
            </h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($areaScores as $area)
                @php
                    $bgColor = match($area['level']['color']) {
                        'emerald' => 'bg-gradient-to-r from-emerald-100 to-emerald-200',
                        'blue' => 'bg-gradient-to-r from-blue-100 to-blue-200',
                        'amber' => 'bg-gradient-to-r from-amber-100 to-amber-200',
                        'orange' => 'bg-gradient-to-r from-orange-100 to-orange-200',
                        'red' => 'bg-gradient-to-r from-red-100 to-red-200',
                        default => 'bg-gradient-to-r from-gray-100 to-gray-200'
                    };
                    $borderColor = match($area['level']['color']) {
                        'emerald' => 'border-emerald-300',
                        'blue' => 'border-blue-300',
                        'amber' => 'border-amber-300',
                        'orange' => 'border-orange-300',
                        'red' => 'border-red-300',
                        default => 'border-gray-300'
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
                        'emerald' => 'bg-emerald-200',
                        'blue' => 'bg-blue-200',
                        'amber' => 'bg-amber-200',
                        'orange' => 'bg-orange-200',
                        'red' => 'bg-red-200',
                        default => 'bg-gray-200'
                    };
                    $progressColor = match($area['level']['color']) {
                        'emerald' => 'bg-emerald-500',
                        'blue' => 'bg-blue-500',
                        'amber' => 'bg-amber-500',
                        'orange' => 'bg-orange-500',
                        'red' => 'bg-red-500',
                        default => 'bg-gray-500'
                    };
                @endphp
                <div class="{{ $bgColor }} p-6 rounded-xl border-2 {{ $borderColor }} shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-bold {{ $textColor }} text-lg">{{ $area['area'] }}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeBgColor }} {{ $textColor }} border {{ $borderColor }}">
                            {{ $area['level']['name'] }} {{ $area['level']['code'] ? "({$area['level']['code']})" : '' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-base font-medium {{ $textColor }}">{{ $area['totalScore'] }}/{{ $area['maxScore'] }} puntos</span>
                        <span class="text-base font-semibold {{ $textColor }}">{{ $area['score'] }}%</span>
                    </div>
                    <div class="w-full bg-white/70 rounded-full h-5 shadow-inner border border-white/60 mb-1">
                        <div class="h-5 rounded-full {{ $progressColor }} transition-all duration-500" style="width: {{ $area['score'] }}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-1 font-medium">Progreso</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Detailed Responses --}}
    <div class="bg-gradient-to-r from-cyan-50 to-cyan-100 rounded-xl shadow-lg overflow-hidden border border-cyan-200">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-cyan-900 mb-6 flex items-center">
                <svg class="w-5 h-5 text-cyan-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Respuestas Detalladas
            </h2>
            <div class="space-y-4">
                @foreach($responses as $response)
                @php
                    $selectedOption = $response->option;
                    $maxScoreOption = $response->question->options->sortByDesc('score')->first();
                    $isMaxScore = $selectedOption && $maxScoreOption && $selectedOption->id === $maxScoreOption->id;
                    $isReallyCorrect = $response->is_correct || $isMaxScore;
                @endphp
                <div class="rounded-lg p-5 border-l-4 {{ $isReallyCorrect ? 'border-green-500 bg-gradient-to-r from-green-50 to-green-100' : 'border-red-500 bg-gradient-to-r from-red-50 to-red-100' }} shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            @if($isReallyCorrect)
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
                                <h3 class="text-lg font-medium {{ $isReallyCorrect ? 'text-green-900' : 'text-red-900' }}">{{ $response->question->question }}</h3>
                                
                            </div>
                            <div class="mt-2 text-sm {{ $isReallyCorrect ? 'text-green-700' : 'text-red-700' }}">
                                <p><span class="font-semibold">Respuesta:</span> {{ $response->option->option }}</p>
                                @if(!$isReallyCorrect)
                                    @php
                                        $correctOption = $response->question->options->firstWhere('is_correct', true);
                                        if (!$correctOption) {
                                            $correctOption = $response->question->options->sortByDesc('score')->first();
                                        }
                                    @endphp
                                    <p class="mt-1"><span class="font-semibold">Respuesta correcta:</span> {{ $correctOption->option ?? 'No disponible' }}</p>
                                @endif
                            </div>
                            @if($response->feedback)
                            <div class="mt-3 p-3 bg-white/50 rounded-md border {{ $isReallyCorrect ? 'border-green-200' : 'border-red-200' }}">
                                <p class="text-sm font-medium {{ $isReallyCorrect ? 'text-green-700' : 'text-red-700' }}">Retroalimentación:</p>
                                <p class="text-sm {{ $isReallyCorrect ? 'text-green-600' : 'text-red-600' }} mt-1">{{ $response->feedback }}</p>
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