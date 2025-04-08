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

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con Test
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}