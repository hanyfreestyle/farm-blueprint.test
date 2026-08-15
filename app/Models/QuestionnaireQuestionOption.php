<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireQuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'label',
        'value',
        'sort_order',
    ];

    protected $attributes = [
        'sort_order' => 0,
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireQuestion::class, 'question_id');
    }
}
