<div class="mb-8">
    <div class="flex justify-between items-center mb-3">
        <span class="text-sm font-semibold text-gray-700 tracking-wide">
            Pregunta <span class="text-indigo-700">{{ $currentRange }}</span> de <span class="text-indigo-700">{{ $totalQuestions }}</span>
        </span>
        <span class="text-sm font-semibold text-indigo-600 tracking-wide">
            {{ number_format($progress, 0) }}% completado
        </span>
    </div>
    <div class="relative w-full h-6">
        {{-- Fondo degradado con glassmorphism --}}
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-3 bg-gradient-to-r from-gray-100 via-indigo-100 to-purple-100 rounded-full shadow-inner border border-indigo-100"></div>
        {{-- Barra de progreso real --}}
        <div class="absolute left-0 top-1/2 -translate-y-1/2 h-3 rounded-full shadow-lg transition-all duration-700 ease-in-out"
             style="
                width: {{ $progress }}%;
                background: linear-gradient(90deg, #6366f1 0%, #4f46e5 100%);
                box-shadow: 0 2px 10px 0 rgba(99,102,241,0.10);
             ">
        </div>
        {{-- Indicador flotante circular --}}
        <div
            class="absolute top-1/2 z-10"
            style="left: calc({{ $progress }}% - 20px); transition: left 700ms cubic-bezier(.6,.05,.25,1);"
        >
            <div class="w-8 h-8 bg-indigo-500 border-4 border-white rounded-full flex items-center justify-center shadow-lg animate-bounce-short">
                <span class="text-white font-bold text-sm">{{ number_format($progress, 0) }}%</span>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes bounce-short {
    0%, 100% { transform: translateY(-15%);}
    30% { transform: translateY(-40%);}
    60% { transform: translateY(-20%);}
}
.animate-bounce-short {
    animation: bounce-short 0.9s;
}
</style>