<div class="space-y-2">
    <p class="text-sm">
        Estás en la página {{ $currentPage }} de {{ $totalPages }}.
    </p>
    
    <p class="text-sm">
        Preguntas en esta página: {{ count($questionIds) }}
    </p>
    
    <p class="text-sm">
        Respuestas proporcionadas: {{ count($providedAnswers) }}
    </p>

    <div class="mt-2 p-2 bg-gray-50 rounded text-xs">
        <p class="font-medium">Detalles técnicos:</p>
        <p>IDs de preguntas en esta página: {{ implode(', ', $questionIds) }}</p>
        <p>IDs de respuestas proporcionadas: {{ implode(', ', $providedAnswers) }}</p>
        <p>Respuestas actuales: {{ json_encode($answers) }}</p>
    </div>

    <p class="text-sm mt-2">
        Por favor, asegúrate de responder al menos una pregunta en esta página antes de enviar.
    </p>
</div> 