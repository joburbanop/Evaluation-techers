<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    protected $fillable = [
        'question_id',
        'option',
        'is_correct',
        'score',
        'feedback',
        'justification'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'score' => 'float'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function responses()
    {
        return $this->hasMany(TestResponse::class);
    }
}