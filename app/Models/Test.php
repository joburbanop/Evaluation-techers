<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = ['name','category','description'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function institutions()
    {
        return $this->belongsToMany(Institution::class, 'institution_test');
    }

    public function testAssignments()
    {
        return $this->hasMany(TestAssignment::class);
    }

    // En app/Models/Test.php
public function category()
{
    return $this->belongsTo(Category::class); // Ajusta según tu modelo de categoría
}
}