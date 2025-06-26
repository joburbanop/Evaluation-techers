<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\TestCompetencyLevel;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'is_active',
        'time_limit',
        'passing_score'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'time_limit' => 'integer',
        'passing_score' => 'integer'
    ];

    // Eager loading por defecto
    protected $with = ['category'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function competencyLevels(): HasMany
    {
        return $this->hasMany(TestCompetencyLevel::class);
    }   

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class)
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'test_teacher_assignments')
            ->withPivot(['assigned_by', 'assigned_at'])
            ->withTimestamps();
    }

    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TestTeacherAssignment::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TestAssignment::class);
    }

    // Scopes para consultas comunes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // Métodos de ayuda
    public function getQuestionCount(): int
    {
        return $this->questions()->count();
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function globalCompetencyLevels(): HasMany
    {
        return $this->hasMany(TestCompetencyLevel::class);
    }


    public function areaCompetencyLevels(): HasMany
    {
        return $this->hasMany(TestAreaCompetencyLevel::class);
    }

    public function testAreaCompetencyLevels()
    {
        return $this->hasMany(\App\Models\TestAreaCompetencyLevel::class, 'test_id');
    }

    // Métodos de caché
    public function getCachedCompetencyLevels()
    {
        return cache()->remember("test_{$this->id}_competency_levels", 3600, function () {
            return $this->competencyLevels()->get();
        });
    }

    public function getCachedAreaCompetencyLevels()
    {
        return cache()->remember("test_{$this->id}_area_competency_levels", 3600, function () {
            return $this->testAreaCompetencyLevels()->get();
        });
    }

    public function clearCompetencyLevelsCache()
    {
        cache()->forget("test_{$this->id}_competency_levels");
        cache()->forget("test_{$this->id}_area_competency_levels");
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($test) {
            $test->clearCompetencyLevelsCache();
        });

        static::deleted(function ($test) {
            $test->clearCompetencyLevelsCache();
        });
    }
}
