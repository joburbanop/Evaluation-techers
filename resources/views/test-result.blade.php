@foreach($options as $option)
    <div class="flex items-center mb-2 p-3 rounded-lg 
        @if($option->id == $selectedOptionId)
            @if($option->id == $correctOptionId)
                bg-green-50 border border-green-200
            @else
                bg-red-50 border border-red-200
            @endif
        @else
            @if($option->id == $correctOptionId)
                bg-green-50 border border-green-200
            @else
                bg-gray-50
            @endif
        @endif">
        <div class="flex items-center h-5">
            <input type="radio" 
                   @if($option->id == $selectedOptionId) checked @endif
                   disabled
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
        </div>
        <div class="ml-3 text-sm">
            <label class="font-medium text-gray-700">
                {{ $option->option }}
                @if($option->id == $correctOptionId && $option->id != $selectedOptionId)
                    <span class="text-green-600 ml-2">(Respuesta correcta)</span>
                @endif
                @if($option->id == $selectedOptionId)
                    <span class="ml-2">
                        @if($option->id == $correctOptionId)
                            <span class="text-green-600">✓ Correcta</span>
                        @else
                            <span class="text-red-600">✗ Tu respuesta</span>
                        @endif
                    </span>
                @endif
            </label>
        </div>
    </div>
@endforeach