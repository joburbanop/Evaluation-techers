@props([
    'score',
    'percentage',
    'icon'
])

<div class="flex items-center space-x-4">
    <div class="flex-shrink-0">
        <x-dynamic-component 
            :component="$icon"
            class="w-8 h-8 text-primary-500"
        />
    </div>
    
    <div class="flex-1">
        <div class="text-2xl font-bold text-gray-900">
            {{ $score }}
        </div>
        
            <div class="mt-1">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div 
                        class="h-2 rounded-full bg-primary-500" 
                        style="width: {{ $percentage }}%"
                    ></div>
                </div>
                <div class="mt-1 text-sm text-gray-600">
                    {{ $percentage }}%
                </div>
            </div>
    </div>
</div> 