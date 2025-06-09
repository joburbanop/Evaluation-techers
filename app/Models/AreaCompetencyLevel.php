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

    protected function afterSave(): void
    {
        $data = $this->form->getState();

        foreach ($data['questions'] ?? [] as $question) {
            $areaId = $question['area_id'] ?? null;
            $niveles = $question['editable_area_levels'] ?? [];

            if ($areaId && !empty($niveles)) {
                foreach ($niveles as $nivel) {
                    if (isset($nivel['id'])) {
                        // Actualizar nivel existente
                        \App\Models\AreaCompetencyLevel::where('id', $nivel['id'])->update([
                            'name' => $nivel['name'],
                            'code' => $nivel['code'],
                            'min_score' => $nivel['min_score'],
                            'max_score' => $nivel['max_score'],
                            'description' => $nivel['description'],
                        ]);
                    } else {
                        // Crear nuevo nivel si no tiene ID
                        \App\Models\AreaCompetencyLevel::create([
                            'area_id' => $areaId,
                            'name' => $nivel['name'],
                            'code' => $nivel['code'],
                            'min_score' => $nivel['min_score'],
                            'max_score' => $nivel['max_score'],
                            'description' => $nivel['description'],
                        ]);
                    }
                }
            }
        }
    }
}