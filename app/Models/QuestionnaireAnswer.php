<?php

namespace App\Models;

use App\Enums\Questionnaire\AnswerReviewStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'value',
        'notes',
        'needs_review',
        'review_status',
        'reviewed_at',
    ];

    protected $attributes = [
        'needs_review' => false,
        'review_status' => AnswerReviewStatus::PENDING->value,
    ];

    protected function casts(): array
    {
        return [
            'value' => 'json',
            'needs_review' => 'boolean',
            'review_status' => AnswerReviewStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $answer): void {
            $answer->needs_review = filled(trim((string) ($answer->notes ?? '')));

            $answer->reviewed_at = $answer->review_status === AnswerReviewStatus::REVIEWED
                ? ($answer->reviewed_at ?? now())
                : null;
        });
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireQuestion::class, 'question_id');
    }

    public function formatValueForDisplay(): string
    {
        $question = $this->question;

        if (! $question instanceof QuestionnaireQuestion) {
            return __('filament/resources/questionnaire_answers.values.empty');
        }

        return $question->formatAnswerValue($this->value);
    }
}
