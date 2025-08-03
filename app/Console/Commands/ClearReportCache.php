<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearReportCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:clear-cache {--all : Clear all report cache} {--type= : Clear cache for specific report type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cached report data to force regeneration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            // Limpiar todo el cache de reportes
            $this->info('Clearing all report cache...');
            
            // Buscar y eliminar todas las claves de cache que empiecen con report_
            $keys = Cache::get('report_cache_keys', []);
            foreach ($keys as $key) {
                Cache::forget($key);
            }
            
            Cache::forget('report_cache_keys');
            $this->info('All report cache cleared successfully!');
            
        } elseif ($type = $this->option('type')) {
            // Limpiar cache de un tipo específico
            $this->info("Clearing cache for report type: {$type}");
            
            $pattern = "{$type}_report_*";
            $keys = Cache::get('report_cache_keys', []);
            $filteredKeys = array_filter($keys, function($key) use ($pattern) {
                return str_contains($key, $pattern);
            });
            
            foreach ($filteredKeys as $key) {
                Cache::forget($key);
            }
            
            $this->info("Cache cleared for {$type} reports!");
            
        } else {
            $this->error('Please specify --all or --type option');
            $this->info('Usage examples:');
            $this->info('  php artisan reports:clear-cache --all');
            $this->info('  php artisan reports:clear-cache --type=universidad');
            $this->info('  php artisan reports:clear-cache --type=facultad');
            $this->info('  php artisan reports:clear-cache --type=programa');
            $this->info('  php artisan reports:clear-cache --type=profesor');
        }
    }
}
