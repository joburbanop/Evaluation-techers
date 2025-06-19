<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole; // Asegúrate de importar el Role de Spatie

class Role extends SpatieRole
{
    protected $table = 'roles';

    // Definir los atributos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'guard_name',
        // Aquí puedes agregar otros campos si es necesario
    ];

    
}