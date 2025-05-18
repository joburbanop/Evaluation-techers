<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function institutions()
    {
        return $this->belongsToMany(Institution::class);
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

    public function testAssignments()
    {
        return $this->hasMany(TestAssignment::class);
    }
}