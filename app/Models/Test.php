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
}