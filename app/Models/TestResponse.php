<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_assignment_id',
        'question_id',
        'option_id',
        'user_id'
    ];

    public function assignment()
    {
        return $this->belongsTo(TestAssignment::class, 'test_assignment_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}