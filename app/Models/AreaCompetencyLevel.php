<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AreaCompetencyLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'name',
        'code',
        'min_score',
        'max_score',
        'description',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public static function getLevelByAreaAndScore(int $areaId, int $score): ?self
    {
        return static::where('area_id', $areaId)
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->first();
    }
}