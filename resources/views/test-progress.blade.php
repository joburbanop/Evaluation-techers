<div class="mb-8">
    <div class="flex justify-between items-center mb-2">
        <span class="text-sm font-medium text-gray-600">Preguntas {{ $currentRange }} de {{ $totalQuestions }}</span>
        <span class="text-sm font-medium text-primary-600">{{ number_format($progress, 0) }}% completado</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2.5 rounded-full transition-all duration-500 ease-in-out" 
             style="width: {{ $progress }}%"></div>
    </div>
</div>