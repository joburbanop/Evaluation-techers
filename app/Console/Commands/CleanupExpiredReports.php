<?php

namespace App\Console\Commands;

use App\Models\Report;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupExpiredReports extends Command
{
    protected $signature = 'reports:cleanup {--days=30 : Número de días después de los cuales expiran los reportes}';
    protected $description = 'Elimina reportes expirados y sus archivos asociados';

    public function handle()
    {
        $days = $this->option('days');
        $expiredDate = now()->subDays($days);

        $this->info("Limpiando reportes expirados (más de {$days} días)...");

        $expiredReports = Report::where('expires_at', '<', now())
            ->orWhere(function ($query) use ($expiredDate) {
                $query->whereNull('expires_at')
                      ->where('created_at', '<', $expiredDate);
            })
            ->get();

        $deletedCount = 0;
        $deletedSize = 0;

        foreach ($expiredReports as $report) {
            // Eliminar archivo físico si existe
            if ($report->file_path && Storage::exists($report->file_path)) {
                $deletedSize += Storage::size($report->file_path);
                Storage::delete($report->file_path);
            }

            // Eliminar registro de la base de datos
            $report->delete();
            $deletedCount++;
        }

        $this->info("Se eliminaron {$deletedCount} reportes expirados.");
        $this->info("Espacio liberado: " . $this->formatBytes($deletedSize));

        return Command::SUCCESS;
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