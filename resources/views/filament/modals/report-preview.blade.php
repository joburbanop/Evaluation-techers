@if(isset($previewData['debug']))
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 shadow-sm">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="ml-3 text-lg font-semibold text-blue-900">Debug - Datos Recibidos</h3>
        </div>
        <div class="bg-white p-4 rounded-lg border border-blue-100 shadow-sm">
            <pre class="text-sm text-gray-800 overflow-auto">{{ $previewData['debug'] }}</pre>
        </div>
        <div class="mt-4">
            <p class="text-sm text-blue-700">Tipo de reporte: <strong>{{ $tipo_reporte }}</strong></p>
        </div>
    </div>
@elseif(isset($error) && $error)
    <div class="bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl p-8 text-center shadow-sm">
        <div class="flex items-center justify-center mb-6">
            <div class="flex-shrink-0">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>
        </div>
        <h3 class="text-xl font-semibold text-red-900 mb-3">Error en la Vista Previa</h3>
        <p class="text-red-700 text-lg mb-4">{{ $error }}</p>
        <div class="bg-white rounded-lg p-4 border border-red-100">
            <p class="text-sm text-red-600">Por favor, complete todos los campos requeridos antes de ver la vista previa.</p>
        </div>
    </div>
@elseif(!$previewData)
    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-8 text-center shadow-sm">
        <div class="flex items-center justify-center mb-6">
            <div class="flex-shrink-0">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>
        </div>
        <h3 class="text-xl font-semibold text-yellow-900 mb-3">Sin Datos Disponibles</h3>
        <p class="text-yellow-700 text-lg mb-4">No hay datos disponibles para generar la vista previa.</p>
        <div class="bg-white rounded-lg p-4 border border-yellow-100">
            <p class="text-sm text-yellow-600">Asegúrese de seleccionar todos los campos requeridos.</p>
        </div>
    </div>
@else
    {{-- Contenedor principal con scroll y mejor organización --}}
    <div class="max-h-[80vh] overflow-y-auto bg-gradient-to-br from-slate-50 to-blue-50 rounded-xl">
        {{-- Header mejorado con gradiente --}}
        <div class="sticky top-0 z-10 bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6 rounded-t-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">
                            @switch($tipo_reporte)
                                @case('universidad')
                                    Reporte de Universidad
                                    @break
                                @case('facultad')
                                    Reporte de Facultad
                                    @break
                                @case('programa')
                                    Reporte de Programa
                                    @break
                                @case('profesor')
                                    Reporte de Profesor
                                    @break
                                @default
                                    Reporte de Evaluación
                            @endswitch
                        </h2>
                        <p class="text-blue-100 text-sm">Vista previa del reporte</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    @if(!isset($isViewingExistingReport) || !$isViewingExistingReport)
                        <a
                            href="{{ auth()->user()->hasRole('Coordinador') ? route('coordinador.reports.pdf') : route('admin.reports.pdf') }}?tipo_reporte={{ $tipo_reporte }}&entidad_id={{ $tipo_reporte === 'universidad' ? ($data['universidad_id'] ?? '') : ($tipo_reporte === 'facultad' ? ($data['facultad_id'] ?? '') : ($tipo_reporte === 'programa' ? ($data['programa_id'] ?? '') : ($tipo_reporte === 'profesor' ? ($data['profesor_id'] ?? '') : ''))) }}{{ $tipo_reporte === 'profesores_completados' && isset($data['filtro_profesores']) ? '&filtro=' . $data['filtro_profesores'] : '' }}&redirect=1"
                            onclick="return confirm('¿Deseas generar el PDF y ser redirigido a la lista de reportes?')"
                            class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition-all duration-200 backdrop-blur-sm border border-white/30 hover:border-white/50 shadow-lg hover:shadow-xl"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Generar PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Contenido del reporte con mejor espaciado --}}
        <div class="p-6">
            @includeIf('reports.' . $tipo_reporte, [
                'previewData' => $previewData,
                'entityName' => $entityName ?? null,
                'data' => $data ?? [],
                'tipo_reporte' => $tipo_reporte,
            ])
        </div>

        {{-- Footer mejorado --}}
        <div class="bg-gradient-to-r from-slate-100 to-blue-100 border-t border-slate-200 p-4 rounded-b-xl">
            <div class="text-center text-slate-600 text-sm">
                <p class="font-medium">Sistema de Evaluación de Profesores</p>
                <p class="text-xs mt-1">Vista previa generada el {{ now()->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </div>
@endif 