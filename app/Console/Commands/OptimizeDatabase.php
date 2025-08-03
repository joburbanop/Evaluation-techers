<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize {--analyze : Analyze table statistics} {--optimize : Optimize table structure} {--cache : Clear query cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize database performance for report generation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 OPTIMIZACIÓN DE BASE DE DATOS');
        $this->info('================================');
        
        if ($this->option('analyze')) {
            $this->analyzeTables();
        }
        
        if ($this->option('optimize')) {
            $this->optimizeTables();
        }
        
        if ($this->option('cache')) {
            $this->clearQueryCache();
        }
        
        if (!$this->option('analyze') && !$this->option('optimize') && !$this->option('cache')) {
            $this->analyzeTables();
            $this->optimizeTables();
            $this->clearQueryCache();
        }
        
        $this->info('✅ Optimización completada');
    }
    
    private function analyzeTables()
    {
        $this->info('\n📊 ANALIZANDO ESTADÍSTICAS DE TABLAS...');
        
        $tables = [
            'test_assignments',
            'test_responses', 
            'users',
            'facultades',
            'programas',
            'questions',
            'options',
            'tests',
            'reports'
        ];
        
        $progressBar = $this->output->createProgressBar(count($tables));
        $progressBar->start();
        
        foreach ($tables as $table) {
            try {
                DB::statement("ANALYZE TABLE {$table}");
                $progressBar->advance();
            } catch (\Exception $e) {
                $this->warn("\n⚠️  Error analizando {$table}: " . $e->getMessage());
            }
        }
        
        $progressBar->finish();
        $this->info('\n✅ Análisis de tablas completado');
    }
    
    private function optimizeTables()
    {
        $this->info('\n⚡ OPTIMIZANDO ESTRUCTURA DE TABLAS...');
        
        $tables = [
            'test_assignments',
            'test_responses',
            'users', 
            'facultades',
            'programas',
            'questions',
            'options',
            'tests',
            'reports'
        ];
        
        $progressBar = $this->output->createProgressBar(count($tables));
        $progressBar->start();
        
        foreach ($tables as $table) {
            try {
                DB::statement("OPTIMIZE TABLE {$table}");
                $progressBar->advance();
            } catch (\Exception $e) {
                $this->warn("\n⚠️  Error optimizando {$table}: " . $e->getMessage());
            }
        }
        
        $progressBar->finish();
        $this->info('\n✅ Optimización de tablas completada');
    }
    
    private function clearQueryCache()
    {
        $this->info('\n🗑️  LIMPIANDO CACHE DE CONSULTAS...');
        
        try {
            // Limpiar cache de consultas MySQL
            DB::statement('FLUSH QUERY CACHE');
            $this->info('✅ Cache de consultas MySQL limpiado');
        } catch (\Exception $e) {
            $this->warn('⚠️  No se pudo limpiar cache MySQL: ' . $e->getMessage());
        }
        
        try {
            // Limpiar cache de Laravel
            \Artisan::call('cache:clear');
            $this->info('✅ Cache de Laravel limpiado');
        } catch (\Exception $e) {
            $this->warn('⚠️  Error limpiando cache Laravel: ' . $e->getMessage());
        }
        
        try {
            // Limpiar cache de configuración
            \Artisan::call('config:clear');
            $this->info('✅ Cache de configuración limpiado');
        } catch (\Exception $e) {
            $this->warn('⚠️  Error limpiando cache de configuración: ' . $e->getMessage());
        }
    }
}
