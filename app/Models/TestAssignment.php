<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAssignment extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'test_id', 
        'user_id', 
        'assigned_at',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function responses()
    {
        return $this->hasMany(TestResponse::class);
    }
}