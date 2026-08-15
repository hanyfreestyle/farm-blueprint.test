<?php

namespace App\Models;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuestionnaireQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'title',
        'help_text',
        'type',
        'is_required',
        'sort_order',
        'depends_on_question_id',
        'dependency_operator',
        'dependency_value',
        'report_category',
        'target_entity',
    ];

    protected $attributes = [
        'is_required' => false,
        'sort_order' => 0,
    ];

    protected function casts(): array
    {
        return [
            'type' => QuestionType::class,
            'is_required' => 'boolean',
            'dependency_operator' => QuestionDependencyOperator::class,
        ];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireSection::class, 'section_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuestionnaireQuestionOption::class, 'question_id')->orderBy('sort_order')->orderBy('id');
    }

    public function answer(): HasOne
    {
        return $this->hasOne(QuestionnaireAnswer::class, 'question_id');
    }

    public function dependencyQuestion(): BelongsTo
    {
        return $this->belongsTo(self::class, 'depends_on_question_id');
    }

    public function dependentQuestions(): HasMany
    {
        return $this->hasMany(self::class, 'depends_on_question_id')->orderBy('sort_order')->orderBy('id');
    }
}
