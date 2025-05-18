<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    // si tu tabla se llama "institutions":
    protected $table = 'institutions';

    // si tus columnas de nombre son 'name' deja esto, si usas 'nombre' cámbialo:
    protected $fillable = ['name', 'ciudad_id'];

    // Si tu tabla no tiene timestamps, puedes agregar:
    // public $timestamps = false;
}