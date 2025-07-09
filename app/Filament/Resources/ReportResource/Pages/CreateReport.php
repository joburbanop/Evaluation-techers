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

    protected function handleRecordCreation(array $data): Model
    {
        // Esta página no crea registros directamente, sino que genera reportes
        // El formulario se maneja de forma personalizada
        return new \App\Models\Report();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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
        
        if (isset($data['date_from'])) {
            $parameters['date_from'] = $data['date_from'];
        }
        
        if (isset($data['date_to'])) {
            $parameters['date_to'] = $data['date_to'];
        }
        
        return $parameters;
    }
} 