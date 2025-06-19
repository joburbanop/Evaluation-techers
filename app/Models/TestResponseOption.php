<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestResponseOption extends Model
{
    protected $fillable = [
        'test_response_id',
        'option_id'
    ];

    public function testResponse(): BelongsTo
    {
        return $this->belongsTo(TestResponse::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
} 