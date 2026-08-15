<?php

namespace App\Services\Questionnaire;

use App\Enums\Questionnaire\AnswerReviewStatus;
use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class QuestionnaireFrontendService
{
    /**
     * @return Collection<int, QuestionnaireSection>
     */
    public function getVisibleMainSectionsTree(): Collection
    {
        $mainSections = QuestionnaireSection::query()
            ->mainSections()
            ->select(['id', 'name', 'description', 'sort_order'])
            ->with([
                'children' => fn ($query) => $query
                    ->select(['id', 'parent_id', 'name', 'description', 'sort_order'])
                    ->with([
                        'questions' => fn ($questionQuery) => $questionQuery
                            ->select([
                                'id',
                                'section_id',
                                'title',
                                'help_text',
                                'type',
                                'is_required',
                                'sort_order',
                                'depends_on_question_id',
                                'dependency_operator',
                                'dependency_value',
                            ])
                            ->with([
                                'options:id,question_id,label,value,sort_order',
                                'answer:id,question_id,value,notes,needs_review,review_status,reviewed_at',
                            ])
                            ->orderBy('sort_order')
                            ->orderBy('id'),
                    ])
                    ->orderBy('sort_order')
                    ->orderBy('id'),
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $mainSections->each(function (QuestionnaireSection $mainSection): void {
            $allChildren = $mainSection->children->values();

            $allChildren->each(function (QuestionnaireSection $subsection): void {
                $subsection->setAttribute('progress_summary', $this->buildSubsectionProgressSummary($subsection));
            });

            $visibleChildren = $this->filterVisibleSubsections($allChildren);

            $mainSection->setAttribute('progress_summary', $this->buildMainSectionProgressSummary($allChildren));
            $mainSection->setRelation('children', $visibleChildren);
            $mainSection->setAttribute('visible_subsections_count', $visibleChildren->count());
        });

        return $this->filterVisibleMainSections($mainSections);
    }

    public function getVisibleMainSection(int $mainSectionId): QuestionnaireSection
    {
        $mainSection = $this->getVisibleMainSectionsTree()->firstWhere('id', $mainSectionId);

        abort_unless($mainSection instanceof QuestionnaireSection, 404);

        return $mainSection;
    }

    /**
     * @return array{
     *   mainSection: QuestionnaireSection,
     *   subsection: QuestionnaireSection,
     *   currentQuestion: ?QuestionnaireQuestion,
     *   previousQuestion: ?QuestionnaireQuestion,
     *   nextQuestion: ?QuestionnaireQuestion,
     *   progressSummary: array<string, mixed>,
     *   sequencePosition: int,
     *   applicableCount: int
     * }
     */
    public function getSubsectionStepContext(int $mainSectionId, int $subsectionId, ?int $questionId = null): array
    {
        $mainSection = $this->getVisibleMainSection($mainSectionId);
        $subsection = $mainSection->children->firstWhere('id', $subsectionId);

        abort_unless($subsection instanceof QuestionnaireSection, 404);

        $applicableQuestions = $this->getApplicableQuestions($subsection);
        $currentQuestion = $questionId === null
            ? $applicableQuestions->first()
            : $applicableQuestions->firstWhere('id', $questionId);

        if ($questionId !== null && ! $currentQuestion instanceof QuestionnaireQuestion) {
            abort(404);
        }

        $currentIndex = $currentQuestion instanceof QuestionnaireQuestion
            ? $applicableQuestions->search(fn (QuestionnaireQuestion $question): bool => $question->is($currentQuestion))
            : false;

        return [
            'mainSection' => $mainSection,
            'subsection' => $subsection,
            'currentQuestion' => $currentQuestion,
            'previousQuestion' => is_int($currentIndex) && $currentIndex > 0 ? $applicableQuestions->get($currentIndex - 1) : null,
            'nextQuestion' => is_int($currentIndex) ? $applicableQuestions->get($currentIndex + 1) : null,
            'progressSummary' => $subsection->progress_summary,
            'sequencePosition' => is_int($currentIndex) ? $currentIndex + 1 : 0,
            'applicableCount' => $applicableQuestions->count(),
        ];
    }

    public function getNextVisibleSubsection(QuestionnaireSection $mainSection, QuestionnaireSection $currentSubsection): ?QuestionnaireSection
    {
        $visibleSubsections = $mainSection->children->values();
        $index = $visibleSubsections->search(fn (QuestionnaireSection $subsection): bool => $subsection->is($currentSubsection));

        if (! is_int($index)) {
            return null;
        }

        return $visibleSubsections->get($index + 1);
    }

    public function saveAnswer(QuestionnaireQuestion $question, mixed $value, ?string $notes = null): QuestionnaireAnswer
    {
        return QuestionnaireAnswer::query()->updateOrCreate(
            ['question_id' => $question->id],
            [
                'value' => $this->normalizeAnswerValue($question, $value),
                'notes' => $this->normalizeNotes($notes),
            ],
        );
    }

    public function isQuestionApplicable(QuestionnaireQuestion $question, array $answersByQuestionId): bool
    {
        if (! $question->depends_on_question_id || ! $question->dependency_operator || $question->dependency_value === null) {
            return true;
        }

        $dependencyAnswer = $answersByQuestionId[$question->depends_on_question_id] ?? null;
        $dependencyValue = $dependencyAnswer?->value;

        if ($dependencyValue === null || $dependencyValue === '') {
            return false;
        }

        return match ($question->dependency_operator) {
            QuestionDependencyOperator::EQUALS => $this->matchesEquals($dependencyValue, $question->dependency_value),
            QuestionDependencyOperator::CONTAINS => $this->matchesContains($dependencyValue, $question->dependency_value),
            default => false,
        };
    }

    public function hasMeaningfulAnswer(QuestionnaireQuestion $question, ?QuestionnaireAnswer $answer): bool
    {
        if (! $answer instanceof QuestionnaireAnswer) {
            return false;
        }

        return $this->hasMeaningfulValue($question, $answer->value);
    }

    public function isValidAnswerForContinuation(QuestionnaireQuestion $question, mixed $value): bool
    {
        if (! $question->is_required) {
            return true;
        }

        return $this->hasMeaningfulValue($question, $this->normalizeAnswerValue($question, $value));
    }

    /**
     * @return Collection<int, QuestionnaireQuestion>
     */
    public function getApplicableQuestions(QuestionnaireSection $subsection): Collection
    {
        $questions = $subsection->questions
            ->sortBy([
                ['sort_order', 'asc'],
                ['id', 'asc'],
            ])
            ->values();

        $answersByQuestionId = $questions
            ->mapWithKeys(fn (QuestionnaireQuestion $question): array => [$question->id => $question->answer])
            ->all();

        return $questions
            ->filter(fn (QuestionnaireQuestion $question): bool => $this->isQuestionApplicable($question, $answersByQuestionId))
            ->values()
            ->map(function (QuestionnaireQuestion $question): QuestionnaireQuestion {
                $question->setAttribute('answer_payload', $this->buildAnswerPayload($question));
                $question->setAttribute('is_applicable', true);

                return $question;
            });
    }

    private function shouldShowZeroMainSections(): bool
    {
        return (bool) config('questionnaire.show_zero_main_sections', true);
    }

    private function shouldShowZeroSubsections(): bool
    {
        return (bool) config('questionnaire.show_zero_subsections', true);
    }

    /**
     * @param  Collection<int, QuestionnaireSection>  $mainSections
     * @return Collection<int, QuestionnaireSection>
     */
    private function filterVisibleMainSections(Collection $mainSections): Collection
    {
        if ($this->shouldShowZeroMainSections()) {
            return $mainSections->values();
        }

        return $mainSections
            ->filter(fn (QuestionnaireSection $mainSection): bool => $mainSection->children->isNotEmpty())
            ->values();
    }

    /**
     * @param  Collection<int, QuestionnaireSection>  $subsections
     * @return Collection<int, QuestionnaireSection>
     */
    private function filterVisibleSubsections(Collection $subsections): Collection
    {
        if ($this->shouldShowZeroSubsections()) {
            return $subsections->values();
        }

        return $subsections
            ->filter(fn (QuestionnaireSection $subsection): bool => $subsection->questions->isNotEmpty())
            ->values();
    }

    private function buildSubsectionProgressSummary(QuestionnaireSection $subsection): array
    {
        $questions = $subsection->questions
            ->sortBy([
                ['sort_order', 'asc'],
                ['id', 'asc'],
            ])
            ->values();

        $applicableQuestions = $this->getApplicableQuestions($subsection);

        $answered = $applicableQuestions
            ->filter(fn (QuestionnaireQuestion $question): bool => $this->hasMeaningfulAnswer($question, $question->answer))
            ->count();

        $total = $applicableQuestions->count();
        $needsReview = $applicableQuestions->contains(
            fn (QuestionnaireQuestion $question): bool => $question->answer?->needs_review === true
                && $question->answer?->review_status === AnswerReviewStatus::PENDING
        );

        return [
            'answered' => $answered,
            'total' => $total,
            'question_count' => $questions->count(),
            'percentage' => $total > 0 ? (int) round(($answered / $total) * 100) : null,
            'status' => $this->resolveProgressStatus($answered, $total),
            'needs_review' => $needsReview,
            'has_questions' => $questions->isNotEmpty(),
        ];
    }

    /**
     * @param  Collection<int, QuestionnaireSection>  $subsections
     */
    private function buildMainSectionProgressSummary(Collection $subsections): array
    {
        $summaries = $subsections->map(
            fn (QuestionnaireSection $subsection): array => $subsection->progress_summary ?? $this->buildSubsectionProgressSummary($subsection)
        );

        $answered = $summaries->sum('answered');
        $total = $summaries->sum('question_count');
        $needsReview = $summaries->contains(fn (array $summary): bool => $summary['needs_review'] === true);
        $hasStarted = $summaries->contains(fn (array $summary): bool => ($summary['answered'] ?? 0) > 0);
        $allNonEmptyComplete = $summaries
            ->filter(fn (array $summary): bool => ($summary['question_count'] ?? 0) > 0)
            ->every(fn (array $summary): bool => ($summary['answered'] ?? 0) === ($summary['question_count'] ?? 0));

        $status = $total === 0
            ? 'empty'
            : ($allNonEmptyComplete ? 'completed' : ($hasStarted ? 'in_progress' : 'empty'));

        return [
            'answered' => $answered,
            'total' => $total,
            'percentage' => $total > 0 ? (int) round(($answered / $total) * 100) : null,
            'status' => $status,
            'needs_review' => $needsReview,
        ];
    }

    private function resolveProgressStatus(int $answered, int $total): string
    {
        if ($total === 0 || $answered === 0) {
            return 'empty';
        }

        if ($answered >= $total) {
            return 'completed';
        }

        return 'in_progress';
    }

    private function buildAnswerPayload(QuestionnaireQuestion $question): array
    {
        $value = $question->answer?->value;

        return [
            'value' => $value,
            'text' => is_string($value) ? $value : '',
            'textarea' => is_string($value) ? $value : '',
            'number' => is_scalar($value) && ! is_bool($value) ? (string) $value : '',
            'date' => is_string($value) ? $value : '',
            'yes_no' => is_scalar($value) || is_bool($value)
                ? ($this->normalizeBooleanString($value) ? '1' : '0')
                : '',
            'option' => is_scalar($value) ? (string) $value : '',
            'options' => is_array($value) ? array_map('strval', $value) : [],
            'notes' => (string) ($question->answer?->notes ?? ''),
        ];
    }

    private function normalizeAnswerValue(QuestionnaireQuestion $question, mixed $value): mixed
    {
        return match ($question->type) {
            QuestionType::YES_NO => $this->normalizeYesNoValue($value),
            QuestionType::MULTI_CHOICE => $this->normalizeMultiChoiceValue($value),
            QuestionType::NUMBER => $this->normalizeNumberValue($value),
            QuestionType::DATE => $this->normalizeStringValue($value),
            QuestionType::TEXT, QuestionType::TEXTAREA, QuestionType::SINGLE_CHOICE, QuestionType::SELECT => $this->normalizeStringValue($value),
        };
    }

    private function normalizeYesNoValue(mixed $value): ?bool
    {
        if ($value === null || $value === '') {
            return null;
        }

        return $this->normalizeBooleanString($value);
    }

    /**
     * @return array<int, string>
     */
    private function normalizeMultiChoiceValue(mixed $value): array
    {
        return collect(Arr::wrap($value))
            ->map(fn (mixed $item): string => trim((string) $item))
            ->filter(fn (string $item): bool => $item !== '')
            ->values()
            ->all();
    }

    private function normalizeNumberValue(mixed $value): string|int|float|null
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return str_contains((string) $value, '.') ? (float) $value : (int) $value;
        }

        return trim((string) $value);
    }

    private function normalizeStringValue(mixed $value): ?string
    {
        $normalized = trim((string) ($value ?? ''));

        return $normalized === '' ? null : $normalized;
    }

    private function normalizeNotes(?string $notes): ?string
    {
        $normalized = trim((string) $notes);

        return $normalized === '' ? null : $normalized;
    }

    private function normalizeBooleanString(mixed $value): bool
    {
        return in_array(Str::lower((string) $value), ['1', 'true', 'yes', 'on'], true);
    }

    private function hasMeaningfulValue(QuestionnaireQuestion $question, mixed $value): bool
    {
        return match ($question->type) {
            QuestionType::MULTI_CHOICE => is_array($value) && count($value) > 0,
            QuestionType::YES_NO => $value !== null && $value !== '',
            default => filled($value),
        };
    }

    private function matchesEquals(mixed $dependencyValue, string $expected): bool
    {
        if (is_array($dependencyValue)) {
            return count($dependencyValue) === 1 && (string) reset($dependencyValue) === $expected;
        }

        return (string) $dependencyValue === $expected;
    }

    private function matchesContains(mixed $dependencyValue, string $expected): bool
    {
        if (is_array($dependencyValue)) {
            return collect($dependencyValue)
                ->map(fn (mixed $item): string => (string) $item)
                ->contains($expected);
        }

        return str_contains((string) $dependencyValue, $expected);
    }
}
