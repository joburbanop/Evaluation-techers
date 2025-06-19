@php
use App\Models\TestCompetencyLevel;
    // Calcular el nivel global y porcentaje
    $totalScore = $record->responses->sum(function ($response) {
        return $response->option->score ?? 0;
    });
    
    $maxPossibleScore = $record->responses->sum(function ($response) {
        return $response->question->options->max('score');
    });
    
    $globalPercentage = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;
    $globalLevel = TestCompetencyLevel::getLevelForScore($record->test_id, $globalPercentage);
    
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

<style>
    .glass {
        @apply backdrop-blur-md bg-white/60 dark:bg-slate-900/70 border border-white/30 shadow-lg;
    }
    .glow {
        text-shadow: 0 0 8px rgba(80,70,229,0.3);
    }
    .animate-bar {
        transition: width 1.2s cubic-bezier(.6,.05,.25,1);
    }
    .pulse-badge {
        animation: pulseBadge 1.5s infinite;
    }
    @keyframes pulseBadge {
        0%,100% { box-shadow: 0 0 0 0 rgba(255,203,5,0.7);}
        50% { box-shadow: 0 0 0 6px rgba(255,203,5,0.2);}
    }
</style>

<div class="space-y-10">
    {{-- Header Section --}}
    <div class="glass rounded-3xl p-8 border-2 border-indigo-200 shadow-xl relative overflow-hidden">
        {{-- Glow efecto en fondo --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-6 -right-10 w-60 h-60 bg-indigo-300/30 rounded-full blur-3xl animate-pulse"></div>
        </div>
        <div class="flex flex-col md:flex-row justify-between items-center relative z-10">
            <div class="mb-8 md:mb-0">
                
                <p class="text-indigo-700 mt-3 text-base font-medium tracking-wide">
                    Detalle completo del desempeño en la evaluación
                </p>
            </div>
            <div class="{{ $globalBgColor }} rounded-2xl px-8 py-5 border-2 {{ $globalBorderColor }} shadow-xl flex flex-col items-center min-w-[220px] relative overflow-hidden">
                <span class="absolute -right-4 -top-4 bg-white/20 rounded-full w-16 h-16 blur-lg pointer-events-none"></span>
                <p class="text-xs font-semibold {{ $globalTextColor }} uppercase tracking-wider glow">Nivel Global</p>
                <div class="flex items-center gap-2 mt-2">
                    <p class="text-4xl font-black {{ $globalTextColor }} glow transition-all duration-500">
                        {{ $globalLevel['name'] }} 
                        @if($globalLevel['code'])
                            <span class="pulse-badge px-2 py-0.5 rounded-full text-xs font-bold bg-white/70 {{ $globalTextColor }} border {{ $globalBorderColor }} ml-1">{{ $globalLevel['code'] }}</span>
                        @endif
                    </p>
                </div>
                <div class="w-full bg-white/60 rounded-full h-3 mt-4 shadow-inner border border-white/40">
                    <div class="h-3 rounded-full animate-bar" style="width: {{ $globalPercentage }}%; background: linear-gradient(90deg, {{ match($globalLevel['color']) {
                        'emerald' => '#10b981, #6ee7b7',
                        'blue' => '#3b82f6, #a5b4fc',
                        'amber' => '#f59e0b, #fef3c7',
                        'orange' => '#f97316, #fdba74',
                        'red' => '#ef4444, #fecaca',
                        default => '#3b82f6, #dbeafe'
                    } }})"></div>
                </div>
                <p class="text-lg {{ $globalTextColor }} mt-2 text-right font-semibold">{{ $globalPercentage }}%</p>
            </div>
        </div>
        {{-- Botón de descarga (opcional) --}}
        <div class="absolute bottom-5 right-6">
            <button
                class="bg-indigo-600 hover:bg-indigo-800 transition px-5 py-2 rounded-xl text-white font-bold shadow-lg focus:ring-4 ring-indigo-400/30 flex items-center gap-2 group"
                onclick="window.print()"
            >
                <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0 0V4m0 8l-3.5-3.5m3.5 3.5l3.5-3.5"/>
                </svg>
                Descargar PDF
            </button>
        </div>
    </div>

    {{-- Divider --}}
    <div class="flex items-center justify-center my-6">
        <div class="w-1/4 border-t-2 border-indigo-200"></div>
        <span class="mx-3 text-indigo-300 font-extrabold text-2xl">·</span>
        <div class="w-1/4 border-t-2 border-indigo-200"></div>
    </div>

    {{-- General Information Card --}}
    <div class="glass rounded-2xl border-2 border-purple-200 p-8 shadow-lg">
        <h2 class="text-xl font-bold text-purple-900 mb-7 flex items-center gap-2">
            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Información General
        </h2>
        <div class="grid md:grid-cols-3 gap-7">
            {{-- Usuario --}}
            <div class="bg-white/70 rounded-xl p-6 border border-purple-100 shadow flex flex-col items-center gap-2 transition hover:scale-[1.03]">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200 glow">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Usuario
                </span>
                <p class="text-lg font-bold text-purple-900 mt-1">{{ $record->user->name }}</p>
            </div>
            {{-- Test --}}
            <div class="bg-white/70 rounded-xl p-6 border border-purple-100 shadow flex flex-col items-center gap-2 transition hover:scale-[1.03]">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200 glow">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Test
                </span>
                <p class="text-lg font-bold text-purple-900 mt-1">{{ $record->test->name }}</p>
            </div>
            {{-- Fecha --}}
            <div class="bg-white/70 rounded-xl p-6 border border-purple-100 shadow flex flex-col items-center gap-2 transition hover:scale-[1.03]">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200 glow">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Fecha
                </span>
                <p class="text-lg font-bold text-purple-900 mt-1">{{ $record->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Divider --}}
    <div class="flex items-center justify-center my-6">
        <div class="w-1/4 border-t-2 border-pink-200"></div>
        <span class="mx-3 text-pink-300 font-extrabold text-2xl">·</span>
        <div class="w-1/4 border-t-2 border-pink-200"></div>
    </div>

    {{-- Area Scores --}}
    <div class="glass rounded-2xl border-2 border-pink-200 p-8 shadow-lg">
        <h2 class="text-xl font-bold text-pink-900 mb-7 flex items-center gap-2">
            <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Puntuación por Área
        </h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach($areaScores as $area)
            @php
                // Asignar color según área o nivel (elije una lógica, aquí ejemplo por nivel)
            $bgColor = match($area['level']['color'] ?? '') {
                'emerald' => 'bg-gradient-to-r from-emerald-100 to-emerald-200',
                'blue' => 'bg-gradient-to-r from-blue-100 to-blue-200',
                'amber' => 'bg-gradient-to-r from-amber-100 to-amber-200',
                'orange' => 'bg-gradient-to-r from-orange-100 to-orange-200',
                'red' => 'bg-gradient-to-r from-red-100 to-red-200',
                default => 'bg-gradient-to-r from-gray-100 to-gray-200'
            };
            $borderColor = match($area['level']['color'] ?? '') {
                'emerald' => 'border-emerald-300',
                'blue' => 'border-blue-300',
                'amber' => 'border-amber-300',
                'orange' => 'border-orange-300',
                'red' => 'border-red-300',
                default => 'border-gray-300'
            };
            $textColor = match($area['level']['color'] ?? '') {
                'emerald' => 'text-emerald-800',
                'blue' => 'text-blue-800',
                'amber' => 'text-amber-800',
                'orange' => 'text-orange-800',
                'red' => 'text-red-800',
                default => 'text-gray-800'
            };
            $badgeBgColor = match($area['level']['color'] ?? '') {
                'emerald' => 'bg-emerald-200',
                'blue' => 'bg-blue-200',
                'amber' => 'bg-amber-200',
                'orange' => 'bg-orange-200',
                'red' => 'bg-red-200',
                default => 'bg-gray-200'
            };
            $progressColor = match($area['level']['color'] ?? '') {
                'emerald' => 'bg-emerald-500',
                'blue' => 'bg-blue-500',
                'amber' => 'bg-amber-500',
                'orange' => 'bg-orange-500',
                'red' => 'bg-red-500',
                default => 'bg-gray-500'
            };
            @endphp
            <div class="{{ $bgColor }} glass p-6 rounded-2xl border-2 {{ $borderColor }} shadow-md hover:shadow-2xl hover:scale-[1.04] transition-all duration-300 transform">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold {{ $textColor }} text-lg">{{ $area['area'] }}</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badgeBgColor }} {{ $textColor }} border {{ $borderColor }} pulse-badge glow">
                        {{ $area['level']['name'] }} {{ $area['level']['code'] ? "({$area['level']['code']})" : '' }}
                    </span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-base font-semibold {{ $textColor }}">{{ $area['totalScore'] }}/{{ $area['maxScore'] }} pts</span>
                    <span class="text-base font-bold {{ $textColor }}">{{ $area['score'] }}%</span>
                </div>
                <div class="w-full bg-white/60 rounded-full h-4 mb-2 border border-white/50 shadow-inner">
                    <div class="h-4 rounded-full animate-bar {{ $progressColor }}" style="width: {{ $area['score'] }}%"></div>
                </div>
                <div class="text-xs text-gray-500 font-semibold mt-1">Progreso</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Divider --}}
    <div class="flex items-center justify-center my-6">
        <div class="w-1/4 border-t-2 border-cyan-200"></div>
        <span class="mx-3 text-cyan-300 font-extrabold text-2xl">·</span>
        <div class="w-1/4 border-t-2 border-cyan-200"></div>
    </div>

    {{-- Detailed Responses --}}
    <div class="glass rounded-2xl border-2 border-cyan-200 p-8 shadow-lg">
        <h2 class="text-xl font-bold text-cyan-900 mb-7 flex items-center gap-2">
            <svg class="w-6 h-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Respuestas Detalladas
        </h2>
        <div class="space-y-5">
            @foreach($responses as $response)
            @php
                $selectedOption = $response->option ?? null;
        $maxScoreOption = $response->question->options->sortByDesc('score')->first();
        $isMaxScore = $selectedOption && $maxScoreOption && $selectedOption->id === $maxScoreOption->id;
        $isReallyCorrect = ($response->is_correct ?? false) || $isMaxScore;
            @endphp
            <div class="rounded-xl p-6 border-l-8 {{ $isReallyCorrect ? 'border-emerald-400 bg-gradient-to-r from-emerald-50 to-emerald-100' : 'border-red-400 bg-gradient-to-r from-red-50 to-red-100' }} shadow hover:shadow-xl transition-transform duration-300 hover:scale-[1.03]">
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        @if($isReallyCorrect)
                        <svg class="h-7 w-7 text-emerald-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        @else
                        <svg class="h-7 w-7 text-red-500 animate-shake" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        @endif
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold {{ $isReallyCorrect ? 'text-emerald-900' : 'text-red-900' }}">{{ $response->question->question }}</h3>
                        </div>
                        <div class="mt-1 text-base {{ $isReallyCorrect ? 'text-emerald-700' : 'text-red-700' }}">
                            <span class="font-semibold">Respuesta:</span> {{ $response->option->option }}
                            @if(!$isReallyCorrect)
                                @php
                                    // Lo que ya haces para respuesta correcta...
                                @endphp
                                <br>
                                <span class="font-semibold">Respuesta correcta:</span> {{ $correctOption->option ?? 'No disponible' }}
                            @endif
                        </div>
                        @if($response->feedback)
                        <div class="mt-3 p-3 bg-white/70 rounded-lg border {{ $isReallyCorrect ? 'border-emerald-200' : 'border-red-200' }}">
                            <p class="text-sm font-bold {{ $isReallyCorrect ? 'text-emerald-700' : 'text-red-700' }}">Retroalimentación:</p>
                            <p class="text-sm {{ $isReallyCorrect ? 'text-emerald-600' : 'text-red-600' }} mt-1">{{ $response->feedback }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>