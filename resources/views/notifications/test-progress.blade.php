<div class="space-y-2">
    <div class="flex items-center justify-between">
        <span class="text-sm font-medium text-gray-700">Progreso del test:</span>
        <span class="text-sm font-semibold text-primary-600">{{ $progress }}%</span>
    </div>
    
    <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div class="bg-primary-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
    </div>
    
    <div class="text-sm text-gray-600">
        <p>Has respondido {{ $answeredQuestions }} de {{ $totalQuestions }} preguntas</p>
        <p>Te faltan {{ $remainingQuestions }} preguntas por responder</p>
    </div>
</div> 