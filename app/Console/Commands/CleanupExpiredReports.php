<?php

namespace App\Console\Commands;

use App\Models\Report;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupExpiredReports extends Command
{
    protected $signature = 'reports:cleanup {--days=30 : Días de antigüedad para considerar expirados}';
    protected $description = 'Limpiar reportes expirados y archivos huérfanos';

    public function handle()
    {
        // Verificar que el usuario que ejecuta el comando sea administrador
        if (!auth()->user() || !auth()->user()->hasRole('Administrador')) {
            $this->error('Solo los administradores pueden ejecutar este comando');
            return 1;
        }

        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $this->info("Limpiando reportes expirados (más de {$days} días)...");

        // Encontrar reportes expirados
        $expiredReports = Report::where('expires_at', '<', $cutoffDate)
            ->orWhere(function ($query) use ($cutoffDate) {
                $query->whereNull('expires_at')
                      ->where('created_at', '<', $cutoffDate);
            })
            ->get();

        $deletedCount = 0;
        $storageFreed = 0;

        foreach ($expiredReports as $report) {
            // Eliminar archivo físico si existe
            if ($report->file_path && Storage::exists($report->file_path)) {
                $fileSize = Storage::size($report->file_path);
                Storage::delete($report->file_path);
                $storageFreed += $fileSize;
                $this->line("Eliminado archivo: {$report->file_path} ({$this->formatBytes($fileSize)})");
            }

            // Eliminar registro de la base de datos
            $report->delete();
            $deletedCount++;
        }

        // Limpiar archivos huérfanos (archivos sin registro en BD)
        $this->cleanupOrphanFiles();

        $this->info("Limpieza completada:");
        $this->info("- Reportes eliminados: {$deletedCount}");
        $this->info("- Almacenamiento liberado: {$this->formatBytes($storageFreed)}");
    }

    private function cleanupOrphanFiles()
    {
        $this->info("Buscando archivos huérfanos...");

        $directories = ['reports/facultades', 'reports/programas', 'reports/universidades', 'reports/profesores'];
        $orphanCount = 0;
        $orphanSize = 0;

        foreach ($directories as $directory) {
            if (!Storage::exists($directory)) {
                continue;
            }

            $files = Storage::files($directory);
            
            foreach ($files as $file) {
                // Extraer ID del nombre del archivo
                if (preg_match('/reporte_\w+_(\d+)_/', $file, $matches)) {
                    $entityId = $matches[1];
                    
                    // Verificar si existe un registro en la BD
                    $report = Report::where('entity_id', $entityId)
                        ->where('file_path', $file)
                        ->first();

                    if (!$report) {
                        $fileSize = Storage::size($file);
                        Storage::delete($file);
                        $orphanCount++;
                        $orphanSize += $fileSize;
                        $this->line("Eliminado archivo huérfano: {$file} ({$this->formatBytes($fileSize)})");
                    }
                }
            }
        }

        if ($orphanCount > 0) {
            $this->info("Archivos huérfanos eliminados: {$orphanCount} ({$this->formatBytes($orphanSize)})");
        } else {
            $this->info("No se encontraron archivos huérfanos.");
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
} 