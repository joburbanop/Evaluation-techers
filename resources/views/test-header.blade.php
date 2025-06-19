<div class="mb-8 text-center">
    <h1>pruebas desc</h1>
    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $testName }}</h1>
    <p class="text-gray-600">{{ $testDescription }}</p>
    @if($isCompleted)
        <div class="mt-4 inline-flex items-center px-4 py-2 bg-green-100 border border-green-200 rounded-full text-sm font-medium text-green-800">
            <svg class="-ml-1 mr-2 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Test Completado
        </div>
    @endif
</div>