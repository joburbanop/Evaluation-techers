<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        // Otros campos que necesites
    ];

    // Relación muchos a muchos con tests
    public function tests()
    {
        return $this->belongsToMany(Test::class, 'institution_test');
    }
}