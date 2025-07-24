<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    Información Técnica del Sistema
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Datos técnicos y estadísticas del sistema de evaluaciones
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Tests Disponibles -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Tests Disponibles
                                </dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $totalTests }}
                                </dd>
                                <dd class="text-sm text-gray-500">
                                    {{ $testsActivos }} activos
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluaciones Recientes -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Evaluaciones Recientes
                                </dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $evaluacionesRecientes }}
                                </dd>
                                <dd class="text-sm text-gray-500">
                                    Últimos 7 días
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluaciones Completadas -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Evaluaciones Completadas
                                </dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $evaluacionesCompletadas }}
                                </dd>
                                <dd class="text-sm text-gray-500">
                                    En mi facultad
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Institucional -->
        <div class="mt-6 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                Información de Contexto
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Institución:</strong> {{ $institution->nombre ?? 'No asignada' }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Facultad:</strong> {{ $facultad->nombre ?? 'No asignada' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Área de Responsabilidad:</strong> Coordinación de Evaluaciones
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Estado del Sistema:</strong> 
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Operativo
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget> 