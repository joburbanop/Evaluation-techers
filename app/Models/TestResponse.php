<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_assignment_id',
        'question_id',
        'option_id',
        'user_id',
        'score',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'score' => 'float'
    ];

    // Eager loading por defecto
    protected $with = ['question', 'option'];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(TestAssignment::class, 'test_assignment_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes para consultas comunes
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    public function scopeIncorrect($query)
    {
        return $query->where('is_correct', false);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAssignment($query, $assignmentId)
    {
        return $query->where('test_assignment_id', $assignmentId);
    }

    // Métodos de ayuda
    public function isCorrect(): bool
    {
        return $this->is_correct;
    }

    public function getScore(): float
    {
        return $this->score ?? 0;
    }

    public function markAsCorrect(): void
    {
        $this->update([
            'is_correct' => true,
            'score' => $this->question->score ?? 1
        ]);
    }

    public function markAsIncorrect(): void
    {
        $this->update([
            'is_correct' => false,
            'score' => 0
        ]);
    }

    // Eventos del modelo
    protected static function booted()
    {
        static::creating(function ($response) {
            // Verificar si la opción seleccionada es correcta
            $option = Option::find($response->option_id);
            if ($option) {
                $response->is_correct = $option->is_correct;
                $response->score = $option->is_correct ? ($response->question->score ?? 1) : 0;
            }
        });
    }
}