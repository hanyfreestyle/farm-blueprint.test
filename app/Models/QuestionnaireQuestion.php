<?php

namespace App\Models;

use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

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

    public function isOptionBasedType(): bool
    {
        return in_array($this->type, [
            QuestionType::SINGLE_CHOICE,
            QuestionType::MULTI_CHOICE,
            QuestionType::SELECT,
        ], true);
    }

    public function isYesNoType(): bool
    {
        return $this->type === QuestionType::YES_NO;
    }

    public function isTextLikeDependencyType(): bool
    {
        return in_array($this->type, [
            QuestionType::TEXT,
            QuestionType::TEXTAREA,
            QuestionType::NUMBER,
            QuestionType::DATE,
        ], true);
    }

    /**
     * @return array<string, string>
     */
    public function getDependencyValueOptions(): array
    {
        if ($this->isYesNoType()) {
            return [
                '1' => __('filament/resources/questionnaire_answers.values.yes'),
                '0' => __('filament/resources/questionnaire_answers.values.no'),
            ];
        }

        if (! $this->isOptionBasedType()) {
            return [];
        }

        return $this->options()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('label', 'value')
            ->all();
    }

    public function formatAnswerValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return __('filament/resources/questionnaire_answers.values.empty');
        }

        if ($this->isYesNoType()) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN)
                ? __('filament/resources/questionnaire_answers.values.yes')
                : __('filament/resources/questionnaire_answers.values.no');
        }

        if ($this->isOptionBasedType()) {
            $labels = $this->options()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->pluck('label', 'value');

            if (is_array($value)) {
                return collect($value)
                    ->map(fn (mixed $item): string => $labels->get((string) $item, (string) $item))
                    ->implode('، ');
            }

            return $labels->get((string) $value, (string) $value);
        }

        if ($this->type === QuestionType::DATE) {
            try {
                return Carbon::parse((string) $value)->toDateString();
            } catch (\Throwable) {
                return (string) $value;
            }
        }

        if ($value instanceof CarbonInterface) {
            return $value->toDateTimeString();
        }

        return is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) ?: '' : (string) $value;
    }
}
