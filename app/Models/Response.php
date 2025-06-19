<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = ['question_id', 'option_id', 'test_assignment_id', 'user_id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function testAssignment()
    {
        return $this->belongsTo(TestAssignment::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
