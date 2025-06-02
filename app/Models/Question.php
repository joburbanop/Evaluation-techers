<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'test_id',
        'factor_id',
        'area_id',
        'question',
    ];

    protected $casts = [
        'factor_id' => 'integer',
        'area_id' => 'integer',
    ];

    public function setAreaIdAttribute($value)
    {
        $this->attributes['area_id'] = is_array($value) ? $value['id'] : $value;
    }

    public function setFactorIdAttribute($value)
    {
        $this->attributes['factor_id'] = is_array($value) ? $value['id'] : $value;
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function factor(): BelongsTo
    {
        return $this->belongsTo(Factor::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(TestResponse::class);
    }

    public function getScore(): float
    {
        return $this->score ?? 4.0;
    }
}