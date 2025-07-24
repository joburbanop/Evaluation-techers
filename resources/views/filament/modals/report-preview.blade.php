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
<!-- Header con botones de acción -->
<div style="position: sticky; top: 0; z-index: 10; background: white; border-bottom: 1px solid #e5e7eb; padding: 1rem 1.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="flex-shrink: 0;">
                <div style="width: 2.5rem; height: 2.5rem; background: linear-gradient(to right, #2563eb, #4f46e5); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 1.5rem; height: 1.5rem; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #111827; margin: 0;">
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
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Vista previa del reporte</p>
            </div>
        </div>
        
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <!-- Botón de Generar PDF - Solo mostrar si no es una visualización de reporte existente -->
            @if(!isset($isViewingExistingReport) || !$isViewingExistingReport)
                <a
                    href="{{ auth()->user()->hasRole('Coordinador') ? route('coordinador.reports.pdf') : route('admin.reports.pdf') }}?tipo_reporte={{ $tipo_reporte }}&entidad_id={{ $tipo_reporte === 'universidad' ? ($data['universidad_id'] ?? '') : ($tipo_reporte === 'facultad' ? ($data['facultad_id'] ?? '') : ($tipo_reporte === 'programa' ? ($data['programa_id'] ?? '') : ($tipo_reporte === 'profesor' ? ($data['profesor_id'] ?? '') : ''))) }}{{ isset($data['date_from']) ? '&date_from=' . $data['date_from'] : '' }}{{ isset($data['date_to']) ? '&date_to=' . $data['date_to'] : '' }}{{ $tipo_reporte === 'profesores_completados' && isset($data['filtro_profesores']) ? '&filtro=' . $data['filtro_profesores'] : '' }}&redirect=1"
                    onclick="return confirm('¿Deseas generar el PDF y ser redirigido a la lista de reportes?')"
                    style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: linear-gradient(to right, #2563eb, #4f46e5); color: white; font-size: 0.875rem; font-weight: 600; border-radius: 0.5rem; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); text-decoration: none;"
                >
                    <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.75rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Generar PDF
                </a>
                

            @endif
        </div>
    </div>
</div>

<!-- Contenido del Reporte -->
<div style="background: white;" id="report-content" 
     data-tipo-reporte="{{ $tipo_reporte }}"
     data-entidad-id="{{ $tipo_reporte === 'universidad' ? ($data['universidad_id'] ?? '') : ($tipo_reporte === 'facultad' ? ($data['facultad_id'] ?? '') : ($tipo_reporte === 'programa' ? ($data['programa_id'] ?? '') : ($tipo_reporte === 'profesor' ? ($data['profesor_id'] ?? '') : ''))) }}"
     data-fecha-desde="{{ $data['date_from'] ?? '' }}"
     data-fecha-hasta="{{ $data['date_to'] ?? '' }}"
     data-filtro-profesores="{{ $data['filtro_profesores'] ?? 'todos' }}">
    
    <!-- Encabezado del Reporte -->
    <div style="background: #2563eb; color: white; padding: 1.5rem; text-align: center;">
        <h1 style="font-size: 2rem; font-weight: bold; margin: 0; margin-bottom: 0.5rem;">
            @switch($tipo_reporte)
                @case('universidad')
                    Reporte de Evaluación por Universidad
                    @break
                @case('facultad')
                    Reporte de Evaluación por Facultad
                    @break
                @case('programa')
                    Reporte de Evaluación por Programa
                    @break
                @case('profesor')
                    Reporte de Profesor
                    @break
                @case('profesores_completados')
                    Reporte de Participación en Evaluación de Competencias
                    @break
                @default
                    Reporte de Evaluación
            @endswitch
        </h1>
        
        @if(isset($entityName) && $entityName)
            <h2 style="font-size: 1.5rem; font-weight: 600; margin: 0; margin-bottom: 1rem;">{{ $entityName }}</h2>
        @endif
        
        <div style="font-size: 1rem; opacity: 0.9;">
            @if(isset($data['date_from']) && isset($data['date_to']))
                Período: {{ \Carbon\Carbon::parse($data['date_from'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($data['date_to'])->format('d/m/Y') }}
            @elseif(isset($data['date_from']))
                Desde: {{ \Carbon\Carbon::parse($data['date_from'])->format('d/m/Y') }}
            @elseif(isset($data['date_to']))
                Hasta: {{ \Carbon\Carbon::parse($data['date_to'])->format('d/m/Y') }}
            @else
                Todos los períodos disponibles
            @endif
        </div>
        
        <div style="font-size: 0.9rem; opacity: 0.8; margin-top: 0.5rem;">
            Generado el {{ $previewData['fecha_generacion'] ?? now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <!-- Línea separadora -->
    <div style="height: 2px; background: #2563eb; margin: 0;"></div>
    
    <!-- Información de la Institución/Facultad -->
    <div style="padding: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem;">
            @if($tipo_reporte === 'facultad')
                Información de la Facultad
            @elseif($tipo_reporte === 'programa')
                Información del Programa
            @elseif($tipo_reporte === 'profesor')
                Información del Profesor
            @else
                Información de la Institución
            @endif
        </h2>
        
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            @if($tipo_reporte === 'facultad')
                <div style="margin-bottom: 0.75rem;">
                    <strong>Facultad:</strong> {{ $previewData['entidad']['nombre'] ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Institución:</strong> {{ $previewData['entidad']['institution']['name'] ?? 'N/A' }}
                </div>
            @elseif($tipo_reporte === 'programa')
                <div style="margin-bottom: 0.75rem;">
                    <strong>Programa:</strong> {{ $previewData['entidad']['nombre'] ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Facultad:</strong> {{ $previewData['facultad']['nombre'] ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Institución:</strong> {{ $previewData['facultad']['institution']['name'] ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Tipo:</strong> {{ $previewData['entidad']['tipo'] ?? 'N/A' }}
                </div>
            @elseif($tipo_reporte === 'profesor')
                <div style="margin-bottom: 0.75rem;">
                    <strong>Nombre:</strong> {{ $previewData['entidad']['name'] ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Email:</strong> {{ $previewData['entidad']['email'] ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Institución:</strong> {{ $previewData['entidad']['institution']['name'] ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Facultad:</strong> {{ $previewData['entidad']['facultad']['nombre'] ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Programa:</strong> {{ $previewData['entidad']['programa']['nombre'] ?? 'N/A' }}
                </div>
            @elseif($tipo_reporte === 'profesores_completados')
                <div style="margin-bottom: 0.75rem;">
                    <strong>Total Profesores:</strong> {{ $previewData['total_profesores'] ?? 0 }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Profesores que Participaron:</strong> {{ $previewData['profesores_completados'] ?? 0 }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Profesores Pendientes:</strong> {{ $previewData['profesores_pendientes'] ?? 0 }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Total Evaluaciones Completadas:</strong> {{ $previewData['total_tests_completados'] ?? 0 }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Filtro Aplicado:</strong> 
                    @switch($previewData['filtro_aplicado'] ?? 'completados')
                        @case('completados')
                            Solo profesores que participaron en la evaluación
                            @break
                        @case('pendientes')
                            Solo profesores pendientes de participar
                            @break
                        @case('todos')
                            Todos los profesores
                            @break
                        @default
                            {{ ucfirst($previewData['filtro_aplicado'] ?? 'completados') }}
                    @endswitch
                </div>
            @else
                <div style="margin-bottom: 0.75rem;">
                    <strong>Nombre:</strong> {{ $previewData['entidad']['name'] ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Carácter Académico:</strong> {{ $previewData['entidad']['academic_character'] ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Departamento:</strong> {{ $previewData['entidad']['departamento_domicilio'] ?? 'N/A' }}
                </div>
                <div style="margin-bottom: 0.75rem;">
                    <strong>Municipio:</strong> {{ $previewData['entidad']['municipio_domicilio'] ?? 'N/A' }}
                </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas Generales -->
    <div style="padding: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem;">Estadísticas Generales</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="font-size: 2.5rem; font-weight: bold; color: #2563eb; margin-bottom: 0.5rem;">{{ number_format($previewData['total_evaluaciones'] ?? 0) }}</div>
                <div style="font-size: 0.9rem; color: #6b7280; font-weight: 500;">Total Evaluaciones</div>
            </div>
            
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="font-size: 2.5rem; font-weight: bold; color: #2563eb; margin-bottom: 0.5rem;">{{ number_format($previewData['total_usuarios'] ?? 0) }}</div>
                <div style="font-size: 0.9rem; color: #6b7280; font-weight: 500;">Total Usuarios</div>
            </div>
            
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="font-size: 2.5rem; font-weight: bold; color: #2563eb; margin-bottom: 0.5rem;">{{ number_format($previewData['promedio_general'] ?? 0, 2) }}</div>
                <div style="font-size: 0.9rem; color: #6b7280; font-weight: 500;">Promedio General</div>
            </div>
            
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="font-size: 2.5rem; font-weight: bold; color: #2563eb; margin-bottom: 0.5rem;">{{ number_format($previewData['max_score'] ?? 0, 1) }}</div>
                <div style="font-size: 0.9rem; color: #6b7280; font-weight: 500;">Puntuación Máxima</div>
            </div>
            
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="font-size: 2.5rem; font-weight: bold; color: #2563eb; margin-bottom: 0.5rem;">{{ number_format($previewData['min_score'] ?? 0, 1) }}</div>
                <div style="font-size: 0.9rem; color: #6b7280; font-weight: 500;">Puntuación Mínima</div>
            </div>
        </div>
    </div>

    <!-- Rendimiento por Área (solo para profesor) -->
    @if($tipo_reporte === 'profesor')
        <div style="padding: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem;">Rendimiento por Área</h2>
            
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="margin-bottom: 1rem;">
                    <strong>Total Respuestas:</strong> {{ number_format($previewData['total_respuestas'] ?? 0) }}
                </div>
            </div>
            
            @if(isset($previewData['areas']) && $previewData['areas']->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <thead>
                            <tr style="background: #f8fafc;">
                                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Área</th>
                                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Evaluaciones</th>
                                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Promedio</th>
                                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Máximo</th>
                                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Mínimo</th>
                                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Total Respuestas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($previewData['areas'] as $area)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 1rem; font-weight: 500; color: #374151;">{{ is_array($area) ? $area['area_name'] : $area->area_name }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ is_array($area) ? $area['total_evaluaciones'] : $area->total_evaluaciones }}</td>
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #2563eb;">{{ number_format(is_array($area) ? $area['promedio_score'] : $area->promedio_score, 2) }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ (is_array($area) ? $area['max_score'] : $area->max_score) ? number_format(is_array($area) ? $area['max_score'] : $area->max_score, 1) : 'N/A' }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ (is_array($area) ? $area['min_score'] : $area->min_score) ? number_format(is_array($area) ? $area['min_score'] : $area->min_score, 1) : 'N/A' }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ is_array($area) ? $area['total_respuestas'] : $area->total_respuestas }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    <!-- Historial de Evaluaciones (solo para profesor) -->
    @if($tipo_reporte === 'profesor' && isset($previewData['historial']) && $previewData['historial']->count() > 0)
        <div style="padding: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem;">Historial de Evaluaciones</h2>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Fecha</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Área</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Puntuación</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Total Respuestas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previewData['historial'] as $evaluacion)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ is_array($evaluacion) ? $evaluacion['fecha'] : $evaluacion->fecha }}</td>
                                <td style="padding: 1rem; font-weight: 500; color: #374151;">{{ is_array($evaluacion) ? $evaluacion['area'] : $evaluacion->area }}</td>
                                <td style="padding: 1rem; text-align: center; font-weight: 600; color: #2563eb;">{{ number_format(is_array($evaluacion) ? $evaluacion['score'] : $evaluacion->score, 2) }}</td>
                                <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ is_array($evaluacion) ? $evaluacion['total_respuestas'] : $evaluacion->total_respuestas }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Comparación con el Programa (solo para profesor) -->
    @if($tipo_reporte === 'profesor' && isset($previewData['comparacion']))
        <div style="padding: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem;">Comparación con el Programa</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div style="font-size: 2.5rem; font-weight: bold; color: #2563eb; margin-bottom: 0.5rem;">{{ number_format(is_object($previewData['comparacion']) ? ($previewData['comparacion']->promedio_programa ?? 0) : ($previewData['comparacion']['promedio_programa'] ?? 0), 2) }}</div>
                    <div style="font-size: 0.9rem; color: #6b7280; font-weight: 500;">Promedio del Programa</div>
                </div>
                
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div style="font-size: 2.5rem; font-weight: bold; color: #2563eb; margin-bottom: 0.5rem;">{{ number_format(is_object($previewData['comparacion']) ? ($previewData['comparacion']->max_programa ?? 0) : ($previewData['comparacion']['max_programa'] ?? 0), 2) }}</div>
                    <div style="font-size: 0.9rem; color: #6b7280; font-weight: 500;">Máximo del Programa</div>
                </div>
                
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <div style="font-size: 2.5rem; font-weight: bold; color: #2563eb; margin-bottom: 0.5rem;">{{ number_format(is_object($previewData['comparacion']) ? ($previewData['comparacion']->min_programa ?? 0) : ($previewData['comparacion']['min_programa'] ?? 0), 2) }}</div>
                    <div style="font-size: 0.9rem; color: #6b7280; font-weight: 500;">Mínimo del Programa</div>
                </div>
            </div>
            
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <h3 style="font-size: 1.25rem; font-weight: bold; color: #374151; margin-bottom: 1rem;">Comparación de Rendimiento</h3>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <strong>Promedio del Profesor:</strong> {{ number_format($previewData['promedio_general'] ?? 0, 2) }}
                    </div>
                    <div>
                        <strong>Promedio del Programa:</strong> {{ number_format(is_object($previewData['comparacion']) ? ($previewData['comparacion']->promedio_programa ?? 0) : ($previewData['comparacion']['promedio_programa'] ?? 0), 2) }}
                    </div>
                    <div>
                        <strong>Diferencia:</strong> 
                        <span style="color: {{ ($previewData['promedio_general'] ?? 0) >= (is_object($previewData['comparacion']) ? ($previewData['comparacion']->promedio_programa ?? 0) : ($previewData['comparacion']['promedio_programa'] ?? 0)) ? '#10b981' : '#ef4444' }}; font-weight: 600;">
                            {{ (($previewData['promedio_general'] ?? 0) - (is_object($previewData['comparacion']) ? ($previewData['comparacion']->promedio_programa ?? 0) : ($previewData['comparacion']['promedio_programa'] ?? 0))) >= 0 ? '+' : '' }}{{ number_format(($previewData['promedio_general'] ?? 0) - (is_object($previewData['comparacion']) ? ($previewData['comparacion']->promedio_programa ?? 0) : ($previewData['comparacion']['promedio_programa'] ?? 0)), 2) }}
                        </span>
                    </div>
                    <div>
                        <strong>Porcentaje del promedio del programa:</strong> 
                        <span style="font-weight: 600; color: #2563eb;">
                            {{ (is_object($previewData['comparacion']) ? ($previewData['comparacion']->promedio_programa ?? 0) : ($previewData['comparacion']['promedio_programa'] ?? 0)) > 0 ? number_format((($previewData['promedio_general'] ?? 0) / (is_object($previewData['comparacion']) ? ($previewData['comparacion']->promedio_programa ?? 0) : ($previewData['comparacion']['promedio_programa'] ?? 0))) * 100, 1) : 0 }}%
                        </span>
                    </div>
                </div>
                
                <!-- Barra de progreso visual -->
                <div style="background: #e5e7eb; border-radius: 0.5rem; height: 1rem; overflow: hidden; margin-top: 1rem;">
                    <div style="background: linear-gradient(to right, #2563eb, #4f46e5); height: 100%; width: {{ (is_object($previewData['comparacion']) ? ($previewData['comparacion']->promedio_programa ?? 0) : ($previewData['comparacion']['promedio_programa'] ?? 0)) > 0 ? min(100, (($previewData['promedio_general'] ?? 0) / (is_object($previewData['comparacion']) ? ($previewData['comparacion']->promedio_programa ?? 0) : ($previewData['comparacion']['promedio_programa'] ?? 0))) * 100) : 0 }}%; transition: width 0.3s ease;"></div>
                </div>
            </div>
        </div>
    @endif

    <!-- Lista de Profesores (solo para profesores_completados) -->
    @if($tipo_reporte === 'profesores_completados' && isset($previewData['profesores']))
        <div style="padding: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem;">Lista de Participación Docente</h2>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">ID</th>
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Identificación</th>
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Nombres</th>
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Email</th>
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Programa</th>
                            <th style="padding: 0.75rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Facultad</th>
                            <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Evaluaciones Completadas</th>
                            <th style="padding: 0.75rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previewData['profesores'] as $profesor)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 0.75rem; color: #6b7280;">{{ $profesor['id'] }}</td>
                                <td style="padding: 0.75rem; font-weight: 500; color: #374151;">{{ $profesor['identificacion'] }}</td>
                                <td style="padding: 0.75rem; font-weight: 500; color: #374151;">{{ $profesor['nombres_completos'] }}</td>
                                <td style="padding: 0.75rem; color: #6b7280;">{{ $profesor['email'] }}</td>
                                <td style="padding: 0.75rem; color: #6b7280;">{{ $profesor['programa'] }}</td>
                                <td style="padding: 0.75rem; color: #6b7280;">{{ $profesor['facultad'] }}</td>
                                <td style="padding: 0.75rem; text-align: center; font-weight: 600; color: #2563eb;">{{ $profesor['tests_completados'] }}</td>
                                <td style="padding: 0.75rem; text-align: center;">
                                    @if($profesor['estado'] === 'Completado')
                                        <span style="background-color: #10b981; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">Participó</span>
                                    @else
                                        <span style="background-color: #f59e0b; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">Pendiente</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if(isset($previewData['es_vista_previa']) && $previewData['es_vista_previa'])
                    <div style="margin-top: 1rem; padding: 1rem; background-color: #fef3c7; border-radius: 6px; border-left: 4px solid #f59e0b;">
                        <p style="margin: 0; color: #92400e; font-size: 0.875rem;">
                            <strong>Vista Previa:</strong> Mostrando los primeros {{ $previewData['profesores']->count() }} registros de {{ $previewData['total_profesores'] }} totales.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Filtros Aplicados -->
    <div style="padding: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem;">Filtros Aplicados</h2>
        
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="margin-bottom: 0.75rem;">
                <strong>Período:</strong> 
                @if(isset($previewData['parametros']['date_from']) && isset($previewData['parametros']['date_to']))
                    Desde: {{ \Carbon\Carbon::parse($previewData['parametros']['date_from'])->format('d/m/Y') }} 
                    Hasta: {{ \Carbon\Carbon::parse($previewData['parametros']['date_to'])->format('d/m/Y') }}
                @else
                    Sin filtros de fecha aplicados
                @endif
            </div>
        </div>
    </div>

    <!-- Línea separadora -->
    <div style="height: 2px; background: #2563eb; margin: 0;"></div>
    
    <!-- Resultados por Área de Competencia -->
    <div style="padding: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem;">Resultados por Área de Competencia</h2>
        
        @if(isset($previewData['areas']) && $previewData['areas']->count() > 0 || isset($previewData['area_stats']) && $previewData['area_stats']->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Área</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Evaluaciones</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Promedio</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Máximo</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Mínimo</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Distribución de Niveles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($previewData['areas']))
                            @foreach($previewData['areas'] as $area)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 1rem; font-weight: 500; color: #374151;">{{ is_array($area) ? $area['area_name'] : $area->area_name }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ is_array($area) ? $area['total_evaluaciones'] : $area->total_evaluaciones }}</td>
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #2563eb;">{{ number_format(is_array($area) ? $area['promedio_score'] : $area->promedio_score, 1) }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ (is_array($area) ? $area['max_score'] : $area->max_score) ? number_format(is_array($area) ? $area['max_score'] : $area->max_score, 1) : 'N/A' }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ (is_array($area) ? $area['min_score'] : $area->min_score) ? number_format(is_array($area) ? $area['min_score'] : $area->min_score, 1) : 'N/A' }}</td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <span style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">C1:{{ is_array($area) ? $area['total_evaluaciones'] : $area->total_evaluaciones }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        @elseif(isset($previewData['area_stats']))
                            @foreach($previewData['area_stats'] as $area)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 1rem; font-weight: 500; color: #374151;">{{ is_array($area) ? $area['area_name'] : $area->area_name }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ is_array($area) ? $area['total_evaluaciones'] : $area->total_evaluaciones }}</td>
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #2563eb;">{{ number_format(is_array($area) ? $area['promedio_score'] : $area->promedio_score, 1) }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ (is_array($area) ? $area['max_score'] : $area->max_score) ? number_format(is_array($area) ? $area['max_score'] : $area->max_score, 1) : 'N/A' }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ (is_array($area) ? $area['min_score'] : $area->min_score) ? number_format(is_array($area) ? $area['min_score'] : $area->min_score, 1) : 'N/A' }}</td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <span style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">C1:{{ is_array($area) ? $area['total_evaluaciones'] : $area->total_evaluaciones }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 3rem; background: #f9fafb; border-radius: 8px;">
                <div style="width: 4rem; height: 4rem; background: #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg style="width: 2rem; height: 2rem; color: #9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p style="color: #6b7280; font-size: 1.1rem;">No hay datos de áreas disponibles para el período seleccionado</p>
            </div>
        @endif
    </div>

    <!-- Resultados por Programa (solo para facultad) -->
    @if($tipo_reporte === 'facultad' && isset($previewData['programa_stats']) && $previewData['programa_stats']->count() > 0)
        <div style="padding: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem;">Resultados por Programa</h2>
            
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Programa</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Evaluaciones</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Promedio</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Máximo</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Mínimo</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Distribución de Niveles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previewData['programa_stats'] as $programa)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 1rem; font-weight: 500; color: #374151;">{{ $programa->programa_nombre }}</td>
                                <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ $programa->total_evaluaciones }}</td>
                                <td style="padding: 1rem; text-align: center; font-weight: 600; color: #2563eb;">{{ number_format($programa->promedio_score, 1) }}</td>
                                <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ $programa->max_score ? number_format($programa->max_score, 1) : 'N/A' }}</td>
                                <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ $programa->min_score ? number_format($programa->min_score, 1) : 'N/A' }}</td>
                                <td style="padding: 1rem; text-align: center;">
                                    <span style="background: #10b981; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">C1:{{ $programa->total_evaluaciones }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Línea separadora -->
    <div style="height: 2px; background: #2563eb; margin: 0;"></div>
    
    <!-- Top 10 Mejores Evaluados -->
    <div style="padding: 2rem;">
        <h2 style="font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem;">Top 10 Mejores Evaluados</h2>
        
        @if(isset($previewData['top_profesores']) && $previewData['top_profesores']->count() > 0 || isset($previewData['top_evaluados']) && $previewData['top_evaluados']->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Posición</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Docente</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Promedio</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;">Evaluaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($previewData['top_profesores']))
                            @foreach($previewData['top_profesores'] as $index => $profesor)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #2563eb;">{{ $index + 1 }}</td>
                                    <td style="padding: 1rem; font-weight: 500; color: #374151;">
                                        {{ $profesor->user_name }} 
                                        @if($profesor->apellido1) {{ $profesor->apellido1 }} @endif
                                        @if($profesor->apellido2) {{ $profesor->apellido2 }} @endif
                                        <br>
                                        <small style="color: #6b7280;">{{ $profesor->facultad_nombre }} - {{ $profesor->programa_nombre }}</small>
                                    </td>
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #2563eb;">{{ number_format($profesor->promedio_general, 2) }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ $profesor->total_evaluaciones }} evaluaciones</td>
                                </tr>
                            @endforeach
                        @elseif(isset($previewData['top_evaluados']))
                            @foreach($previewData['top_evaluados'] as $index => $evaluado)
                                <tr style="border-bottom: 1px solid #f3f4f6;">
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #2563eb;">{{ $index + 1 }}</td>
                                    <td style="padding: 1rem; font-weight: 500; color: #374151;">
                                        {{ $evaluado->user->full_name }}
                                    </td>
                                    <td style="padding: 1rem; text-align: center; font-weight: 600; color: #2563eb;">{{ number_format($evaluado->score, 1) }}</td>
                                    <td style="padding: 1rem; text-align: center; color: #6b7280;">{{ $evaluado->fecha->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 3rem; background: #f9fafb; border-radius: 8px;">
                <div style="width: 4rem; height: 4rem; background: #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <svg style="width: 2rem; height: 2rem; color: #9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <p style="color: #6b7280; font-size: 1.1rem;">No hay datos de profesores disponibles para el período seleccionado</p>
            </div>
        @endif
    </div>

    <!-- Análisis de Rendimiento (solo para programa) -->
    @if($tipo_reporte === 'programa')
        <div style="padding: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem;">Análisis de Rendimiento</h2>
            
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                @if(($previewData['total_evaluaciones'] ?? 0) > 0)
                    <div style="margin-bottom: 1rem;">
                        <strong>Promedio General del Programa:</strong> {{ number_format($previewData['promedio_general'] ?? 0, 2) }}
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong>Total de Evaluaciones:</strong> {{ number_format($previewData['total_evaluaciones'] ?? 0) }}
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong>Usuarios Evaluados:</strong> {{ number_format($previewData['total_usuarios'] ?? 0) }}
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong>Áreas Evaluadas:</strong> {{ $previewData['areas_evaluadas'] ?? 0 }}
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <strong>Nivel de Satisfacción:</strong> 
                        <span style="color: {{ ($previewData['promedio_general'] ?? 0) >= 4.0 ? '#10b981' : (($previewData['promedio_general'] ?? 0) >= 3.0 ? '#f59e0b' : '#ef4444') }}; font-weight: 600;">
                            {{ ($previewData['promedio_general'] ?? 0) >= 4.0 ? 'Excelente' : (($previewData['promedio_general'] ?? 0) >= 3.0 ? 'Bueno' : 'Necesita Mejora') }}
                        </span>
                    </div>
                @else
                    <div style="text-align: center; color: #6b7280; font-style: italic;">
                        No hay datos suficientes para realizar el análisis de rendimiento.
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Pie de página -->
    <div style="background: #f8fafc; border-top: 1px solid #e5e7eb; padding: 1.5rem; text-align: center;">
        <div style="color: #6b7280; font-size: 0.875rem;">
            Este reporte fue generado automáticamente por el sistema de evaluación de profesores.<br>
            ©2025 Sistema de Evaluación de Profesores
        </div>
    </div>
</div>

<!-- Nota informativa -->
<div style="margin-top: 1.5rem; background: linear-gradient(to right, #dbeafe, #e0e7ff); border: 1px solid #bfdbfe; border-radius: 0.75rem; padding: 1.5rem;">
    <div style="display: flex; align-items: start;">
        <div style="flex-shrink: 0;">
            <div style="width: 2rem; height: 2rem; background: #2563eb; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                <svg style="width: 1.25rem; height: 1.25rem; color: white;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div style="margin-left: 1rem;">
            <h4 style="font-size: 1.125rem; font-weight: 600; color: #1e40af; margin-bottom: 0.5rem;">Información Importante</h4>
            <p style="color: #1e40af;">
                <strong>Datos reales:</strong> Esta vista previa muestra los datos actuales del sistema basados en las evaluaciones existentes. El reporte final se generará con estos mismos datos en formato PDF profesional.
            </p>
        </div>
    </div>
</div>

@endif 