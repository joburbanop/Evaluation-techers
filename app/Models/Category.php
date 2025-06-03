<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'factor_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Obtiene el factor al que pertenece esta área
     */
    public function factor(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'factor_id');
    }

    /**
     * Obtiene las áreas que pertenecen a este factor
     */
    public function areas(): HasMany
    {
        return $this->hasMany(Category::class, 'factor_id');
    }

    /**
     * Scope para obtener solo factores (registros sin factor_id)
     */
    public function scopeFactors($query)
    {
        return $query->whereNull('factor_id');
    }

    /**
     * Scope para obtener solo áreas (registros con factor_id)
     */
    public function scopeAreas($query)
    {
        return $query->whereNotNull('factor_id');
    }

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }
}
