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
    public const QUESTION_FILTER_ALL = 'all';

    public const QUESTION_FILTER_ANSWERED = 'answered';

    public const QUESTION_FILTER_UNANSWERED = 'unanswered';

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

            $subsectionsWithStats = $this->buildMainSectionSubsections($allChildren);
            $visibleChildren = $this->filterVisibleSubsections($subsectionsWithStats);

            $mainSection->setAttribute('progress_summary', $this->buildMainSectionProgressSummary($subsectionsWithStats));
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
     *   applicableCount: int,
     *   activeFilter: string,
     *   filteredCount: int
     * }
     */
    public function getSubsectionStepContext(
        int $mainSectionId,
        int $subsectionId,
        ?int $questionId = null,
        ?string $filter = null,
    ): array
    {
        $mainSection = $this->getVisibleMainSection($mainSectionId);
        $subsection = $mainSection->children->firstWhere('id', $subsectionId);

        abort_unless($subsection instanceof QuestionnaireSection, 404);

        $applicableQuestions = $this->getApplicableQuestions($subsection);
        $activeFilter = $this->normalizeQuestionFilter($filter);
        $filteredQuestions = $this->applyQuestionFilter($applicableQuestions, $activeFilter);
        $requestedQuestion = $questionId === null
            ? $filteredQuestions->first()
            : $applicableQuestions->firstWhere('id', $questionId);

        if ($questionId !== null && ! $requestedQuestion instanceof QuestionnaireQuestion) {
            abort(404);
        }

        $currentQuestion = $questionId === null
            ? $requestedQuestion
            : $filteredQuestions->firstWhere('id', $questionId);

        $requestedIndex = $requestedQuestion instanceof QuestionnaireQuestion
            ? $applicableQuestions->search(fn (QuestionnaireQuestion $question): bool => $question->is($requestedQuestion))
            : false;

        $filteredIndex = $currentQuestion instanceof QuestionnaireQuestion
            ? $filteredQuestions->search(fn (QuestionnaireQuestion $question): bool => $question->is($currentQuestion))
            : false;

        $previousQuestion = null;
        $nextQuestion = null;

        if (is_int($filteredIndex)) {
            $previousQuestion = $filteredIndex > 0 ? $filteredQuestions->get($filteredIndex - 1) : null;
            $nextQuestion = $filteredQuestions->get($filteredIndex + 1);
        } elseif (is_int($requestedIndex)) {
            $previousQuestion = $filteredQuestions
                ->filter(function (QuestionnaireQuestion $question) use ($applicableQuestions, $requestedIndex): bool {
                    $index = $applicableQuestions->search(fn (QuestionnaireQuestion $candidate): bool => $candidate->is($question));

                    return is_int($index) && $index < $requestedIndex;
                })
                ->last();

            $nextQuestion = $filteredQuestions->first(function (QuestionnaireQuestion $question) use ($applicableQuestions, $requestedIndex): bool {
                $index = $applicableQuestions->search(fn (QuestionnaireQuestion $candidate): bool => $candidate->is($question));

                return is_int($index) && $index > $requestedIndex;
            });
        }

        return [
            'mainSection' => $mainSection,
            'subsection' => $subsection,
            'currentQuestion' => $currentQuestion,
            'previousQuestion' => $previousQuestion,
            'nextQuestion' => $nextQuestion,
            'progressSummary' => $subsection->progress_summary,
            'sequencePosition' => is_int($filteredIndex) ? $filteredIndex + 1 : 0,
            'applicableCount' => $applicableQuestions->count(),
            'activeFilter' => $activeFilter,
            'filteredCount' => $filteredQuestions->count(),
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

    /**
     * @return array{
     *   mainSection: QuestionnaireSection,
     *   subsection: QuestionnaireSection,
      *   progressSummary: array<string, mixed>,
     *   applicableCount: int,
     *   activeFilter: string,
     *   filteredCount: int
     * }
     */
    public function getSubsectionCompletionContext(int $mainSectionId, int $subsectionId, ?string $filter = null): array
    {
        $mainSection = $this->getVisibleMainSection($mainSectionId);
        $subsection = $mainSection->children->firstWhere('id', $subsectionId);

        abort_unless($subsection instanceof QuestionnaireSection, 404);

        $applicableQuestions = $this->getApplicableQuestions($subsection);
        $activeFilter = $this->normalizeQuestionFilter($filter);
        $filteredQuestions = $this->applyQuestionFilter($applicableQuestions, $activeFilter);

        return [
            'mainSection' => $mainSection,
            'subsection' => $subsection,
            'progressSummary' => $subsection->progress_summary,
            'applicableCount' => $applicableQuestions->count(),
            'activeFilter' => $activeFilter,
            'filteredCount' => $filteredQuestions->count(),
        ];
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

    public function deleteAnswer(QuestionnaireQuestion $question): void
    {
        $question->answer()?->delete();
    }

    public function deleteAllAnswers(): void
    {
        QuestionnaireAnswer::query()->delete();
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

    public function normalizeQuestionFilter(?string $filter): string
    {
        return match ($filter) {
            self::QUESTION_FILTER_ANSWERED => self::QUESTION_FILTER_ANSWERED,
            self::QUESTION_FILTER_UNANSWERED => self::QUESTION_FILTER_UNANSWERED,
            default => self::QUESTION_FILTER_ALL,
        };
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

    /**
     * @param  Collection<int, QuestionnaireQuestion>  $questions
     * @return Collection<int, QuestionnaireQuestion>
     */
    public function applyQuestionFilter(Collection $questions, string $filter): Collection
    {
        return match ($this->normalizeQuestionFilter($filter)) {
            self::QUESTION_FILTER_ANSWERED => $questions
                ->filter(fn (QuestionnaireQuestion $question): bool => $this->hasMeaningfulAnswer($question, $question->answer))
                ->values(),
            self::QUESTION_FILTER_UNANSWERED => $questions
                ->reject(fn (QuestionnaireQuestion $question): bool => $this->hasMeaningfulAnswer($question, $question->answer))
                ->values(),
            default => $questions->values(),
        };
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
            ->filter(fn (QuestionnaireSection $mainSection): bool => (($mainSection->progress_summary['question_count'] ?? 0) > 0))
            ->values();
    }

    /**
     * @param  Collection<int, QuestionnaireSection>  $subsections
     * @return Collection<int, QuestionnaireSection>
     */
    private function filterVisibleSubsections(Collection $subsections): Collection
    {
        if ($this->shouldShowZeroSubsections()) {
            return $subsections
                ->each(fn (QuestionnaireSection $subsection): QuestionnaireSection => $subsection->setAttribute('is_visible', true))
                ->values();
        }

        return $subsections
            ->map(function (QuestionnaireSection $subsection): QuestionnaireSection {
                $subsection->setAttribute('is_visible', (($subsection->progress_summary['question_count'] ?? 0) > 0));

                return $subsection;
            })
            ->filter(fn (QuestionnaireSection $subsection): bool => $subsection->is_visible === true)
            ->values();
    }

    /**
     * @param  Collection<int, QuestionnaireSection>  $subsections
     * @return Collection<int, QuestionnaireSection>
     */
    private function buildMainSectionSubsections(Collection $subsections): Collection
    {
        return $subsections
            ->map(function (QuestionnaireSection $subsection): QuestionnaireSection {
                $progressSummary = $this->buildSubsectionProgressSummary($subsection);

                $subsection->setAttribute('progress_summary', $progressSummary);
                $subsection->setAttribute('question_count', $progressSummary['question_count']);
                $subsection->setAttribute('answered_count', $progressSummary['answered']);
                $subsection->setAttribute('progress_percentage', $progressSummary['percentage']);
                $subsection->setAttribute('needs_review', $progressSummary['needs_review']);
                $subsection->setAttribute('is_visible', true);

                return $subsection;
            })
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
            'question_count' => $total,
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
        $normalizedYesNo = $this->normalizeYesNoValue($value);

        return [
            'value' => $value,
            'text' => is_string($value) ? $value : '',
            'textarea' => is_string($value) ? $value : '',
            'number' => is_scalar($value) && ! is_bool($value) ? (string) $value : '',
            'date' => is_string($value) ? $value : '',
            'yes_no' => $normalizedYesNo,
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

        if (is_bool($value)) {
            return $value;
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
        if (is_array($value)) {
            return false;
        }

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
