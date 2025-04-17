<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAssignment extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'test_id',
        'assigned_date',
        'assigned_time',
        'assigned_at',
        'due_date',
        'due_time',
        'due_at',
        'instructions',
        'completed_at',
        'status'
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