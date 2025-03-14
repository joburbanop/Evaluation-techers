<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionTest extends Model
{
    use HasFactory;

    protected $table = 'institution_test'; // Especifica que el modelo debe utilizar la tabla 'institution_test'
    protected $fillable = [
        'institution_id', // Clave foránea para Institution
        'test_id',        // Clave foránea para Test
    ];

    // Relación con Institution
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    // Relación con Test
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
