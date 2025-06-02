@props([
    'text',
    'score',
    'isCorrect',
    'isCorrectAnswer' => false
])

@php
    $color = match(true) {
        $isCorrect => 'success',
        $isCorrectAnswer => 'info',
        default => 'danger'
    };
@endphp

<div class="p-4 rounded-lg bg-{{ $color }}-50 border border-{{ $color }}-200">
    <div class="flex items-start space-x-3">
        @if($isCorrect)
            <x-heroicon-s-check-circle class="w-5 h-5 text-{{ $color }}-500 flex-shrink-0 mt-0.5" />
        @elseif($isCorrectAnswer)
            <x-heroicon-s-information-circle class="w-5 h-5 text-{{ $color }}-500 flex-shrink-0 mt-0.5" />
        @else
            <x-heroicon-s-x-circle class="w-5 h-5 text-{{ $color }}-500 flex-shrink-0 mt-0.5" />
        @endif
        
        <div class="flex-1">
            <p class="text-sm font-medium text-{{ $color }}-700">
                {{ $text }}
            </p>
            <p class="mt-1 text-xs text-{{ $color }}-600">
                Puntuación: {{ $score }}/4
            </p>
        </div>
    </div>
</div> 