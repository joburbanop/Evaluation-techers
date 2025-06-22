@php
use App\Models\TestCompetencyLevel;
    
// Calculation for global level and percentage
$totalScore = $record->responses->sum(fn($response) => $response->option->score ?? 0);
$maxPossibleScore = $record->responses->sum(fn($response) => $response->question->options->max('score'));
$globalPercentage = $maxPossibleScore > 0 ? round(($totalScore / $maxPossibleScore) * 100) : 0;
$globalLevel = TestCompetencyLevel::getLevelForScore($record->test_id, $globalPercentage);

$globalLevel = $globalLevel ?: ['name' => 'Sin nivel', 'code' => '', 'color' => 'gray'];

// Function to get level colors for badges
function getLevelBadgeColors($level) {
    return match ($level['color'] ?? 'gray') {
        'emerald' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800'],
        'blue'    => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'amber'   => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800'],
        'orange'  => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
        'red'     => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
        default   => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'],
    };
}

@endphp

<div class="p-4 sm:p-6 lg:p-8 space-y-8 bg-white dark:bg-gray-900">

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 p-6 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Resultados de {{ $record->user->name }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Detalle completo del desempeño en la evaluación
            </p>
        </div>
        <div class="flex-shrink-0 w-full sm:w-auto mt-4 sm:mt-0">
             @if ($globalLevel)
            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 text-center border border-gray-200 dark:border-gray-700">
                <div class="text-lg font-bold text-gray-800 dark:text-gray-100">
                    {{ $globalLevel['name'] }} {{ $globalLevel['code'] }}
                </div>
                <div class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">
                    {{ $globalPercentage }}%
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- General Information --}}
    <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-3">
            <x-heroicon-o-information-circle class="w-6 h-6 text-gray-400" />
            Información General
        </h3>
        <div class="grid sm:grid-cols-3 gap-6 mt-4">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">Usuario</span>
                <p class="font-semibold text-gray-900 dark:text-white text-lg mt-1">{{ $record->user->name }}</p>
            </div>
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">Test</span>
                <p class="font-semibold text-gray-900 dark:text-white text-lg mt-1">{{ $record->test->name }}</p>
            </div>
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">Fecha</span>
                <p class="font-semibold text-gray-900 dark:text-white text-lg mt-1">{{ $record->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    {{-- Area Scores --}}
    <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-3">
            <x-heroicon-o-clipboard-document-list class="w-6 h-6 text-gray-400" />
            Puntuación por Área
        </h3>
        <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-0 mt-4">
            @forelse($areaScores as $area)
                @php
                    $areaColors = getLevelBadgeColors($area['level']);
                @endphp
                <div class="py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-gray-800 dark:text-white">{{ $area['area'] }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $area['totalScore'] }}/{{ $area['maxScore'] }} pts</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        @if($area['level']['name'] !== 'Sin nivel')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $areaColors['bg'] }} {{ $areaColors['text'] }}">
                                {{ $area['level']['name'] }} ({{ $area['level']['code'] }})
                            </span>
                        @else
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                Sin Evaluar
                            </span>
                        @endif
                        <p class="font-bold text-lg text-gray-900 dark:text-white mt-1">{{ $area['score'] }}%</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Progreso</p>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">No hay puntuaciones por área para mostrar.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Detailed Responses --}}
    <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-3">
            <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 text-gray-400" />
            Respuestas Detalladas
        </h3>
        <div class="space-y-4 mt-4">
            @forelse($responses as $response)
                @php
                    $selectedOption = $response->option ?? null;
                    $correctOption = $response->question->options->firstWhere('is_correct', true);
                    $isCorrect = $selectedOption && $selectedOption->is_correct;
                @endphp
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $response->question->question }}</p>
                    <div class="mt-2 text-sm">
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="font-medium">Tu respuesta:</span>
                            <span @class([
                                'font-semibold',
                                'text-green-700 dark:text-green-400' => $isCorrect,
                                'text-red-700 dark:text-red-400' => !$isCorrect,
                            ])>
                                {{ $selectedOption->option ?? 'No respondida' }}
                            </span>
                        </p>
                        @if (!$isCorrect && $correctOption)
                            <p class="text-gray-600 dark:text-gray-300 mt-1">
                                <span class="font-medium">Respuesta correcta:</span>
                                <span class="font-semibold text-green-700 dark:text-green-400">{{ $correctOption->option }}</span>
                            </p>
                        @endif
                        @if ($response->feedback)
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-medium">Retroalimentación:</span> {{ $response->feedback }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">No hay respuestas para mostrar.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>