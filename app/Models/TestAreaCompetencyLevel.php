<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestAreaCompetencyLevel extends Model
{
    protected $fillable = [
        'test_id',
        'area_id',
        'name',
        'code',
        'min_score',
        'max_score',
        'description',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
    public static function getLevelByScore(int $testId, int $areaId, int $score): ?self
    {
        return static::where('test_id', $testId)
            ->where('area_id', $areaId)
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();
    }


}