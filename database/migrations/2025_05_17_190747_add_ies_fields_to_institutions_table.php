<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            // Eliminar campos que ya no se usarán si existen
            $columns = ['nit', 'address', 'city', 'phone', 'email'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('institutions', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Agregar nuevos campos solo si no existen
            if (!Schema::hasColumn('institutions', 'academic_character')) {
                $table->string('academic_character')->after('name');
            }
            if (!Schema::hasColumn('institutions', 'active_programs')) {
                $table->integer('active_programs')->after('academic_character');
            }
            if (!Schema::hasColumn('institutions', 'is_accredited')) {
                $table->boolean('is_accredited')->default(false)->after('active_programs');
            }
            if (!Schema::hasColumn('institutions', 'departamento_id')) {
                $table->foreignId('departamento_id')->nullable()->after('is_accredited')->constrained('departamentos')->nullOnDelete();
            }
            if (!Schema::hasColumn('institutions', 'ciudad_id')) {
                $table->foreignId('ciudad_id')->nullable()->after('departamento_id')->constrained('ciudades')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            // Restaurar campos eliminados
            $table->string('nit')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Eliminar nuevos campos
            $table->dropForeign(['departamento_id']);
            $table->dropForeign(['ciudad_id']);
            $table->dropColumn([
                'academic_character',
                'active_programs',
                'is_accredited',
                'departamento_id',
                'ciudad_id',
            ]);
        });
    }
};
