<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\Facultad;
use App\Models\Programa;
use App\Models\Institution;
use App\Models\User;
use App\Services\ReportService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Pre-llenar el formulario con parámetros de URL si existen
        $request = request();
        
        if ($request->has('tipo_reporte')) {
            $data['tipo_reporte'] = $request->get('tipo_reporte');
        }
        
        if ($request->has('facultad_id')) {
            $data['facultad_id'] = $request->get('facultad_id');
        }
        
        if ($request->has('programa_id')) {
            $data['programa_id'] = $request->get('programa_id');
        }
        
        if ($request->has('universidad_id')) {
            $data['universidad_id'] = $request->get('universidad_id');
        }
        
        if ($request->has('profesor_id')) {
            $data['profesor_id'] = $request->get('profesor_id');
        }
        
        return $data;
    }

    protected function getFormActions(): array
    {
        return [
                        Actions\Action::make('preview')
                ->label('Vista Previa')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->modalHeading('Vista Previa del Reporte')
                ->modalDescription('Revise cómo se verá su reporte antes de generarlo')
                ->modalWidth('95vw')
                ->modalContent(function () {
                    // Obtener los datos del formulario actual
                    $formData = $this->form->getState();
                    
                    // Validar que existan los datos necesarios
                    if (empty($formData['tipo_reporte']) || $formData['tipo_reporte'] === 'Seleccione una opción') {
                        return view('filament.modals.report-preview', [
                            'tipo_reporte' => '',
                            'data' => $formData,
                            'previewData' => null,
                            'error' => 'Por favor seleccione un tipo de reporte válido'
                        ]);
                    }

                    // Validar que se haya seleccionado la entidad correspondiente
                    $entitySelected = false;
                    $entityName = '';
                    
                    switch ($formData['tipo_reporte']) {
                        case 'universidad':
                            $entitySelected = !empty($formData['universidad_id']);
                            if ($entitySelected) {
                                $institution = Institution::find($formData['universidad_id']);
                                $entityName = $institution ? $institution->name : '';
                            }
                            break;
                        case 'facultad':
                            $entitySelected = !empty($formData['facultad_id']);
                            if ($entitySelected) {
                                $facultad = Facultad::find($formData['facultad_id']);
                                $entityName = $facultad ? $facultad->nombre : '';
                            }
                            break;
                        case 'programa':
                            $entitySelected = !empty($formData['programa_id']);
                            if ($entitySelected) {
                                $programa = Programa::find($formData['programa_id']);
                                $entityName = $programa ? $programa->nombre : '';
                            }
                            break;
                        case 'profesor':
                            $entitySelected = !empty($formData['profesor_id']);
                            if ($entitySelected) {
                                $profesor = User::find($formData['profesor_id']);
                                $entityName = $profesor ? $profesor->name . ' ' . $profesor->apellido1 : '';
                            }
                            break;

                    }

                    if (!$entitySelected) {
                        return view('filament.modals.report-preview', [
                            'tipo_reporte' => $formData['tipo_reporte'],
                            'data' => $formData,
                            'previewData' => null,
                            'error' => 'Por favor seleccione la entidad correspondiente al tipo de reporte: ' . ucfirst($formData['tipo_reporte'])
                        ]);
                    }

                    try {
                        // Obtener datos reales del sistema
                        $reportService = app(ReportService::class);
                        
                        // Preparar los parámetros correctos para el servicio
                        $parameters = [];
                        if (isset($formData['date_from'])) {
                            $parameters['date_from'] = $formData['date_from'];
                        }
                        if (isset($formData['date_to'])) {
                            $parameters['date_to'] = $formData['date_to'];
                        }
                        
                        // Agregar el ID de la entidad según el tipo de reporte
                        switch ($formData['tipo_reporte']) {
                            case 'universidad':
                                $parameters['institution_id'] = $formData['universidad_id'];
                                break;
                            case 'facultad':
                                $parameters['facultad_id'] = $formData['facultad_id'];
                                break;
                            case 'programa':
                                $parameters['programa_id'] = $formData['programa_id'];
                                break;
                                                    case 'profesor':
                            $parameters['profesor_id'] = $formData['profesor_id'];
                            break;

                        }
                        
                        $previewData = $reportService->getPreviewData($formData['tipo_reporte'], $parameters);
                        
                        // Usar directamente las vistas específicas según el tipo de reporte
                        switch ($formData['tipo_reporte']) {
                            case 'profesor':
                                return view('reports.profesor', ['previewData' => $previewData]);
                            case 'programa':
                                return view('reports.programa', ['previewData' => $previewData]);
                            case 'facultad':
                                return view('reports.facultad', ['previewData' => $previewData]);
                            case 'universidad':
                                return view('reports.universidad', ['previewData' => $previewData]);

                            default:
                                return view('filament.modals.report-preview', [
                                    'tipo_reporte' => $formData['tipo_reporte'],
                                    'data' => $formData,
                                    'previewData' => $previewData,
                                    'error' => null,
                                    'entityName' => $entityName
                                ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error en vista previa:', ['error' => $e->getMessage(), 'data' => $formData]);
                        
                        return view('filament.modals.report-preview', [
                            'tipo_reporte' => $formData['tipo_reporte'] ?? '',
                            'data' => $formData,
                            'previewData' => null,
                            'error' => 'Error al obtener los datos: ' . $e->getMessage()
                        ]);
                    }
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Cerrar')
                ->modalWidth('7xl')
                ->requiresConfirmation(false)
                ->disabled(fn () => !$this->isFormComplete()),
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Esta página no crea registros directamente, sino que genera reportes
        // El formulario se maneja de forma personalizada
        return new \App\Models\Report();
    }

    protected function getRedirectUrl(): string
    {
        // Obtener el panel actual
        $panel = \Filament\Facades\Filament::getCurrentPanel();
        $panelId = $panel ? $panel->getId() : 'admin';
        
        // Construir la URL correcta según el panel
        if ($panelId === 'coordinador') {
            return '/coordinador/reports';
        } elseif ($panelId === 'admin') {
            return '/admin/reports';
        } else {
            return $this->getResource()::getUrl('index');
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            $reportService = app(ReportService::class);
            
            if (isset($data['tipo_reporte']) && $data['tipo_reporte'] === 'universidad' && isset($data['universidad_id'])) {
                $institution = Institution::find($data['universidad_id']);
                $parameters = $this->buildParameters($data);
                
                $report = $reportService->generateUniversidadReport($institution, $parameters);
                
                Notification::make()
                    ->title('Reporte generado exitosamente')
                    ->body("El reporte de la universidad {$institution->name} ha sido generado y está listo para descargar.")
                    ->success()
                    ->send();
                    
            } elseif (isset($data['tipo_reporte']) && $data['tipo_reporte'] === 'facultad' && isset($data['facultad_id'])) {
                $facultad = Facultad::find($data['facultad_id']);
                $parameters = $this->buildParameters($data);
                
                $report = $reportService->generateFacultadReport($facultad, $parameters);
                
                Notification::make()
                    ->title('Reporte generado exitosamente')
                    ->body("El reporte de la facultad {$facultad->nombre} ha sido generado y está listo para descargar.")
                    ->success()
                    ->send();
                    
            } elseif (isset($data['tipo_reporte']) && $data['tipo_reporte'] === 'programa' && isset($data['programa_id'])) {
                $programa = Programa::find($data['programa_id']);
                $parameters = $this->buildParameters($data);
                
                $report = $reportService->generateProgramaReport($programa, $parameters);
                
                Notification::make()
                    ->title('Reporte generado exitosamente')
                    ->body("El reporte del programa {$programa->nombre} ha sido generado y está listo para descargar.")
                    ->success()
                    ->send();
                    
            } elseif (isset($data['tipo_reporte']) && $data['tipo_reporte'] === 'profesor' && isset($data['profesor_id'])) {
                $profesor = User::find($data['profesor_id']);
                $parameters = $this->buildParameters($data);
                
                $report = $reportService->generateProfesorReport($profesor, $parameters);
                
                Notification::make()
                    ->title('Reporte generado exitosamente')
                    ->body("El reporte del profesor {$profesor->name} {$profesor->apellido1} ha sido generado y está listo para descargar.")
                    ->success()
                    ->send();
            }
            
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error al generar el reporte')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
        
        return $data;
    }

    private function buildParameters(array $data): array
    {
        $parameters = [];
        
        if (isset($data['filtro_profesores'])) {
            $parameters['filtro'] = $data['filtro_profesores'];
        }
        
        return $parameters;
    }

    private function isFormComplete(): bool
    {
        try {
            $formData = $this->form->getState();
            
            // Solo verificar que se haya seleccionado un tipo de reporte válido
            if (empty($formData['tipo_reporte'] ?? '') || ($formData['tipo_reporte'] ?? '') === 'Seleccione una opción') {
                return false;
            }

            // Si hay un tipo de reporte seleccionado, el botón se habilita
            return true;
        } catch (\Exception $e) {
            \Log::error('Error en isFormComplete:', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function isEntitySelected(array $data): bool
    {
        switch ($data['tipo_reporte'] ?? '') {
            case 'universidad':
                return !empty($data['universidad_id'] ?? '');
            case 'facultad':
                return !empty($data['facultad_id'] ?? '');
            case 'programa':
                return !empty($data['programa_id'] ?? '');
            case 'profesor':
                return !empty($data['profesor_id'] ?? '');
            default:
                return false;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            // Agregar script para abrir automáticamente la vista previa si hay parámetros de URL
            \Filament\Actions\Action::make('autoPreview')
                ->label('')
                ->visible(false)
                ->action(function () {
                    // Esta acción no hace nada, solo se usa para el script
                })
                ->extraAttributes([
                    'x-data' => '{}',
                    'x-init' => '
                        if (window.location.search.includes("tipo_reporte=")) {
                            setTimeout(() => {
                                const previewButton = document.querySelector("[data-action="preview"]");
                                if (previewButton) {
                                    previewButton.click();
                                }
                            }, 1000);
                        }
                    '
                ])
        ];
    }
} 