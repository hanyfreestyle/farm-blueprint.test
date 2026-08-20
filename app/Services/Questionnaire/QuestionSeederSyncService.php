<?php

namespace App\Services\Questionnaire;

use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class QuestionSeederSyncService
{
    /**
     * Synchronize one subsection with the question definitions declared by its Seeder.
     *
     * During the current Blueprint design phase use:
     *   prune: true
     *   preserveAnswers: false
     *
     * This makes the Seeder the source of truth: removed questions are deleted and
     * their answers/options are removed automatically by database cascade rules.
     *
     * Later, once answers become production/reference data, use preserveAnswers: true
     * to block destructive structural changes that would invalidate stored answers.
     *
     * @param array<int, array<string, mixed>> $questions
     */
    public function sync(
        string $mainSectionName,
        string $sectionName,
        array $questions,
        bool $prune = true,
        bool $preserveAnswers = false,
    ): void {
        DB::transaction(function () use (
            $mainSectionName,
            $sectionName,
            $questions,
            $prune,
            $preserveAnswers,
        ): void {
            $section = $this->resolveSection($mainSectionName, $sectionName);
            $this->validateDefinitions($questions);

            $desiredSeedKeys = collect($questions)
                ->pluck('seed_key')
                ->map(fn (mixed $key): string => (string) $key)
                ->values()
                ->all();

            $existingQuestions = QuestionnaireQuestion::query()
                ->with(['options', 'answer'])
                ->where('section_id', $section->id)
                ->get();

            if ($prune) {
                foreach ($existingQuestions as $existingQuestion) {
                    if (
                        $existingQuestion->seed_key !== null
                        && in_array($existingQuestion->seed_key, $desiredSeedKeys, true)
                    ) {
                        continue;
                    }

                    if ($preserveAnswers && $existingQuestion->answer) {
                        throw new RuntimeException(
                            "Question sync stopped safely: '{$existingQuestion->title}' would be removed but already has an answer."
                        );
                    }

                    // questionnaire_answers and questionnaire_question_options cascade on question delete.
                    $existingQuestion->delete();
                }
            }

            foreach ($questions as $definition) {
                $this->syncQuestion(
                    sectionId: $section->id,
                    definition: $definition,
                    prune: $prune,
                    preserveAnswers: $preserveAnswers,
                );
            }
        });
    }

    private function resolveSection(string $mainSectionName, string $sectionName): QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('name', $mainSectionName)
            ->first();

        if (! $mainSection) {
            throw new RuntimeException(
                "Main section '{$mainSectionName}' was not found. Run QuestionnaireSectionSeeder first."
            );
        }

        $section = QuestionnaireSection::query()
            ->where('parent_id', $mainSection->id)
            ->where('name', $sectionName)
            ->first();

        if (! $section) {
            throw new RuntimeException(
                "Subsection '{$sectionName}' was not found. Run QuestionnaireSectionSeeder first."
            );
        }

        return $section;
    }

    /**
     * @param array<int, array<string, mixed>> $questions
     */
    private function validateDefinitions(array $questions): void
    {
        $seedKeys = [];
        $sortOrders = [];

        foreach ($questions as $index => $definition) {
            $seedKey = trim((string) ($definition['seed_key'] ?? ''));

            if ($seedKey === '') {
                throw new RuntimeException("Question definition at index {$index} is missing seed_key.");
            }

            if (isset($seedKeys[$seedKey])) {
                throw new RuntimeException("Duplicate question seed_key '{$seedKey}' in Seeder definitions.");
            }

            $seedKeys[$seedKey] = true;

            $sortOrder = (int) ($definition['sort_order'] ?? 0);

            if ($sortOrder < 1) {
                throw new RuntimeException("Question '{$seedKey}' must have sort_order >= 1.");
            }

            if (isset($sortOrders[$sortOrder])) {
                throw new RuntimeException("Duplicate sort_order '{$sortOrder}' in Seeder definitions.");
            }

            $sortOrders[$sortOrder] = true;
        }
    }

    /**
     * @param array<string, mixed> $definition
     */
    private function syncQuestion(
        int $sectionId,
        array $definition,
        bool $prune,
        bool $preserveAnswers,
    ): void {
        $options = $definition['options'] ?? [];
        unset($definition['options']);

        $seedKey = (string) $definition['seed_key'];

        /** @var QuestionnaireQuestion|null $existingQuestion */
        $existingQuestion = QuestionnaireQuestion::query()
            ->with(['options', 'answer'])
            ->where('section_id', $sectionId)
            ->where('seed_key', $seedKey)
            ->first();

        if ($existingQuestion?->answer) {
            $this->guardOrClearInvalidAnswer(
                question: $existingQuestion,
                definition: $definition,
                options: $options,
                preserveAnswers: $preserveAnswers,
            );
        }

        $definition['section_id'] = $sectionId;
        $definition['depends_on_question_id'] = $definition['depends_on_question_id'] ?? null;
        $definition['dependency_operator'] = $definition['dependency_operator'] ?? null;
        $definition['dependency_value'] = $definition['dependency_value'] ?? null;

        $question = QuestionnaireQuestion::query()->updateOrCreate(
            [
                'section_id' => $sectionId,
                'seed_key' => $seedKey,
            ],
            $definition,
        );

        $this->syncOptions($question, $options, $prune);
    }

    /**
     * @param array<string, mixed> $definition
     * @param array<int, array<string, mixed>> $options
     */
    private function guardOrClearInvalidAnswer(
        QuestionnaireQuestion $question,
        array $definition,
        array $options,
        bool $preserveAnswers,
    ): void {
        $desiredType = $definition['type'] ?? null;
        $typeChanged = $desiredType instanceof QuestionType
            ? $question->type !== $desiredType
            : $question->type->value !== (string) $desiredType;

        $answerInvalid = $typeChanged;

        if (! $answerInvalid && $question->isOptionBasedType()) {
            $desiredOptionValues = collect($options)
                ->pluck('value')
                ->map(fn (mixed $value): string => (string) $value)
                ->all();

            $answerValue = $question->answer?->value;
            $selectedValues = is_array($answerValue) ? $answerValue : [$answerValue];

            foreach ($selectedValues as $selectedValue) {
                if ($selectedValue === null || $selectedValue === '') {
                    continue;
                }

                if (! in_array((string) $selectedValue, $desiredOptionValues, true)) {
                    $answerInvalid = true;
                    break;
                }
            }
        }

        if (! $answerInvalid) {
            return;
        }

        if ($preserveAnswers) {
            throw new RuntimeException(
                "Question sync stopped safely: '{$question->title}' has an answer that would become invalid after this Seeder change."
            );
        }

        $question->answer()->delete();
    }

    /**
     * @param array<int, array<string, mixed>> $options
     */
    private function syncOptions(QuestionnaireQuestion $question, array $options, bool $prune): void
    {
        $desiredValues = collect($options)
            ->pluck('value')
            ->map(fn (mixed $value): string => (string) $value)
            ->all();

        if ($prune) {
            $obsoleteOptions = $question->options();

            if ($desiredValues !== []) {
                $obsoleteOptions->whereNotIn('value', $desiredValues);
            }

            $obsoleteOptions->delete();
        }

        foreach ($options as $index => $option) {
            $question->options()->updateOrCreate(
                ['value' => (string) $option['value']],
                [
                    'label' => (string) $option['label'],
                    'sort_order' => $index + 1,
                ],
            );
        }
    }
}
