<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteDetalle extends Model
{
    protected $table = 'si cvw_reporte_detalle'; // Cambia el nombre si tu vista tiene otro nombre
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = null;
    // Si quieres proteger contra escritura:
    public function save(array $options = []) { return false; }
    public function delete() { return false; }
} 