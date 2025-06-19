<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'user_id',
        'institution_id',
        'status',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // Eager loading por defecto
    protected $with = ['test', 'user'];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class)->with(['questions.options']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(TestResponse::class);
    }

    // Scopes para consultas comunes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    // Métodos de ayuda
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }


    
}