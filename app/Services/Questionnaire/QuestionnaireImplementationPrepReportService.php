<?php

namespace App\Services\Questionnaire;

use App\Enums\Questionnaire\AnswerReviewStatus;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class QuestionnaireImplementationPrepReportService
{
    public const REPORT_FILENAME = 'rabbit-farm-implementation-prep-report.md';

    public function __construct(
        private readonly QuestionnaireFrontendService $frontendService,
    ) {
    }

    public function buildReport(): string
    {
        return $this->renderMarkdown($this->buildReportData());
    }

    public function buildReportData(): array
    {
        $mainSections = $this->loadMainSections();

        $stats = [
            'main_sections' => $mainSections->count(),
            'subsections' => 0,
            'questions' => 0,
            'applicable_questions' => 0,
            'answered_questions' => 0,
            'unanswered_questions' => 0,
            'review_questions' => 0,
        ];

        $mainSectionEntries = [];
        $openReviewItems = [];
        $unansweredItems = [];

        foreach ($mainSections as $mainIndex => $mainSection) {
            $subsectionEntries = [];
            $stats['subsections'] += $mainSection->children->count();

            foreach ($mainSection->children as $subIndex => $subsection) {
                $subsectionEntry = $this->buildSubsectionEntry($mainSection, $subsection, $mainIndex + 1, $subIndex + 1);
                $subsectionEntries[] = $subsectionEntry;

                $stats['questions'] += $subsectionEntry['question_count'];
                $stats['applicable_questions'] += $subsectionEntry['applicable_question_count'];
                $stats['answered_questions'] += $subsectionEntry['answered_count'];
                $stats['unanswered_questions'] += count($subsectionEntry['unanswered_questions']);
                $stats['review_questions'] += count($subsectionEntry['review_questions']);

                $openReviewItems = [...$openReviewItems, ...$subsectionEntry['review_questions']];
                $unansweredItems = [...$unansweredItems, ...$subsectionEntry['unanswered_questions']];
            }

            $mainSectionEntries[] = [
                'number' => (string) ($mainIndex + 1),
                'name' => $mainSection->name,
                'description' => $mainSection->description,
                'subsections' => $subsectionEntries,
            ];
        }

        $stats['completion_percentage'] = $stats['applicable_questions'] > 0
            ? (int) round(($stats['answered_questions'] / $stats['applicable_questions']) * 100)
            : 0;

        return [
            'title' => 'تقرير الدراسة المنظم لإعداد التنفيذ',
            'subtitle' => 'صيغة منظمة من الأسئلة والإجابات الحالية تساعد على بناء التقرير النهائي وخطة التنفيذ.',
            'generated_at' => Carbon::now(),
            'filename' => self::REPORT_FILENAME,
            'stats' => $stats,
            'main_sections' => $mainSectionEntries,
            'open_review_items' => $openReviewItems,
            'unanswered_items' => $unansweredItems,
        ];
    }

    public function renderMarkdown(array $reportData): string
    {
        $lines = [
            '# ' . $reportData['title'],
            '',
            $reportData['subtitle'],
            '',
            '## معلومات التقرير',
            '',
            '| البند | القيمة |',
            '|---|---|',
            '| اسم الملف | `' . $reportData['filename'] . '` |',
            '| وقت التوليد | ' . $reportData['generated_at']->format('Y-m-d H:i:s') . ' |',
            '',
            '## مؤشرات عامة',
            '',
            '| المؤشر | القيمة |',
            '|---|---|',
            '| الأقسام الرئيسية | ' . $reportData['stats']['main_sections'] . ' |',
            '| الأقسام الفرعية | ' . $reportData['stats']['subsections'] . ' |',
            '| جميع الأسئلة | ' . $reportData['stats']['questions'] . ' |',
            '| الأسئلة القابلة حاليًا | ' . $reportData['stats']['applicable_questions'] . ' |',
            '| الأسئلة المجاب عنها | ' . $reportData['stats']['answered_questions'] . ' |',
            '| الأسئلة غير المجاب عنها | ' . $reportData['stats']['unanswered_questions'] . ' |',
            '| الأسئلة المفتوحة للمراجعة | ' . $reportData['stats']['review_questions'] . ' |',
            '| نسبة الإنجاز الحالية | ' . $reportData['stats']['completion_percentage'] . '% |',
            '',
            '## طريقة قراءة هذا التقرير',
            '',
            '- كل قسم فرعي يعرض الأسئلة بترتيبها الحالي.',
            '- كل سؤال يوضح إن كان قابلًا حاليًا أو مخفيًا بسبب شرط.',
            '- كل سؤال يوضح الإجابة الحالية بشكل يصلح للمراجعة البشرية أو التحليل الآلي.',
            '- هذا التقرير لا يحاول استنتاج التصميم النهائي، بل يرتب المعرفة الخام المنظمة تمهيدًا للتقرير النهائي.',
            '',
        ];

        foreach ($reportData['main_sections'] as $mainSection) {
            $lines[] = '# ' . $mainSection['number'] . '. ' . $mainSection['name'];
            $lines[] = '';

            if (filled($mainSection['description'])) {
                $lines[] = trim((string) $mainSection['description']);
                $lines[] = '';
            }

            foreach ($mainSection['subsections'] as $subsection) {
                $lines[] = '## ' . $subsection['number'] . ' ' . $subsection['name'];
                $lines[] = '';

                if (filled($subsection['description'])) {
                    $lines[] = trim((string) $subsection['description']);
                    $lines[] = '';
                }

                $lines[] = '| المؤشر | القيمة |';
                $lines[] = '|---|---|';
                $lines[] = '| إجمالي الأسئلة | ' . $subsection['question_count'] . ' |';
                $lines[] = '| الأسئلة القابلة حاليًا | ' . $subsection['applicable_question_count'] . ' |';
                $lines[] = '| الأسئلة المجاب عنها | ' . $subsection['answered_count'] . ' |';
                $lines[] = '| الأسئلة غير المجاب عنها | ' . count($subsection['unanswered_questions']) . ' |';
                $lines[] = '| الأسئلة المفتوحة للمراجعة | ' . count($subsection['review_questions']) . ' |';
                $lines[] = '';

                if (empty($subsection['questions'])) {
                    $lines[] = 'لا توجد أسئلة مضافة لهذا القسم الفرعي حتى الآن.';
                    $lines[] = '';
                    continue;
                }

                foreach ($subsection['questions'] as $question) {
                    $lines[] = '### س' . $question['sort_order'] . '. ' . $question['title'];
                    $lines[] = '';
                    $lines[] = '- النوع: ' . $question['type_label'];
                    $lines[] = '- مطلوب: ' . ($question['is_required'] ? 'نعم' : 'لا');
                    $lines[] = '- الحالة الحالية: ' . $question['applicability_label'];
                    $lines[] = '- تمت الإجابة: ' . ($question['is_answered'] ? 'نعم' : 'لا');
                    $lines[] = '- التصنيف التقريرى: ' . ($question['report_category'] ?: 'غير محدد');
                    $lines[] = '- الكيان المستهدف: ' . ($question['target_entity'] ?: 'غير محدد');

                    if (filled($question['help_text'])) {
                        $lines[] = '- التوضيح: ' . $question['help_text'];
                    }

                    if (filled($question['dependency_summary'])) {
                        $lines[] = '- التبعية: ' . $question['dependency_summary'];
                    }

                    $lines[] = '- الإجابة الحالية: ' . $question['answer_display'];
                    $lines[] = '- حالة المراجعة: ' . $question['review_label'];

                    if (filled($question['notes'])) {
                        $lines[] = '- ملاحظات المختص: ' . $question['notes'];
                    }

                    $lines[] = '';
                }
            }
        }

        $lines[] = '# العناصر المفتوحة للمراجعة';
        $lines[] = '';

        if (empty($reportData['open_review_items'])) {
            $lines[] = 'لا توجد عناصر مفتوحة للمراجعة حاليًا.';
            $lines[] = '';
        } else {
            foreach ($reportData['open_review_items'] as $item) {
                $lines[] = '- **القسم الرئيسي:** ' . $item['main_section'];
                $lines[] = '  **القسم الفرعي:** ' . $item['subsection'];
                $lines[] = '  **السؤال:** ' . $item['question'];
                $lines[] = '  **الإجابة الحالية:** ' . $item['answer_display'];
                $lines[] = '  **الملاحظات:** ' . ($item['notes'] ?: 'بدون ملاحظات نصية');
            }
            $lines[] = '';
        }

        $lines[] = '# الأسئلة غير المجاب عنها';
        $lines[] = '';

        if (empty($reportData['unanswered_items'])) {
            $lines[] = 'لا توجد أسئلة قابلة حاليًا بدون إجابة.';
            $lines[] = '';
        } else {
            foreach ($reportData['unanswered_items'] as $item) {
                $lines[] = '- **القسم الرئيسي:** ' . $item['main_section'];
                $lines[] = '  **القسم الفرعي:** ' . $item['subsection'];
                $lines[] = '  **السؤال:** ' . $item['question'];

                if (filled($item['help_text'])) {
                    $lines[] = '  **التوضيح:** ' . $item['help_text'];
                }
            }
            $lines[] = '';
        }

        return implode("\n", $lines) . "\n";
    }

    /**
     * @return Collection<int, QuestionnaireSection>
     */
    private function loadMainSections(): Collection
    {
        return QuestionnaireSection::query()
            ->mainSections()
            ->select(['id', 'parent_id', 'name', 'description', 'sort_order'])
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
                                'report_category',
                                'target_entity',
                            ])
                            ->with([
                                'options:id,question_id,label,value,sort_order',
                                'answer:id,question_id,value,notes,needs_review,review_status,reviewed_at',
                                'dependencyQuestion:id,title,sort_order',
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
    }

    private function buildSubsectionEntry(
        QuestionnaireSection $mainSection,
        QuestionnaireSection $subsection,
        int $mainNumber,
        int $subNumber,
    ): array {
        $questions = $subsection->questions->values();
        $applicableQuestions = $this->frontendService->getApplicableQuestions($subsection);
        $applicableQuestionIds = $applicableQuestions->pluck('id')->all();
        $answeredCount = $applicableQuestions
            ->filter(fn (QuestionnaireQuestion $question): bool => $this->frontendService->hasMeaningfulAnswer($question, $question->answer))
            ->count();

        $questionEntries = $questions
            ->map(fn (QuestionnaireQuestion $question): array => $this->buildQuestionEntry(
                $mainSection,
                $subsection,
                $question,
                in_array($question->id, $applicableQuestionIds, true),
            ))
            ->all();

        $reviewQuestions = array_values(array_filter(
            $questionEntries,
            fn (array $entry): bool => $entry['review_status'] === AnswerReviewStatus::PENDING->value
                && ($entry['needs_review'] || filled($entry['notes']))
        ));

        $unansweredQuestions = array_values(array_filter(
            $questionEntries,
            fn (array $entry): bool => $entry['is_applicable'] && ! $entry['is_answered']
        ));

        return [
            'number' => $mainNumber . '.' . $subNumber,
            'name' => $subsection->name,
            'description' => $subsection->description,
            'question_count' => count($questionEntries),
            'applicable_question_count' => count($applicableQuestionIds),
            'answered_count' => $answeredCount,
            'questions' => $questionEntries,
            'review_questions' => $reviewQuestions,
            'unanswered_questions' => $unansweredQuestions,
        ];
    }

    private function buildQuestionEntry(
        QuestionnaireSection $mainSection,
        QuestionnaireSection $subsection,
        QuestionnaireQuestion $question,
        bool $isApplicable,
    ): array {
        $isAnswered = $isApplicable
            && $this->frontendService->hasMeaningfulAnswer($question, $question->answer);

        $reviewStatus = $question->answer?->review_status;
        $reviewStatusValue = $reviewStatus?->value;

        return [
            'main_section' => $mainSection->name,
            'subsection' => $subsection->name,
            'question' => $question->title,
            'sort_order' => $question->sort_order,
            'title' => $question->title,
            'help_text' => $question->help_text,
            'type_label' => $question->type->value,
            'is_required' => $question->is_required,
            'is_applicable' => $isApplicable,
            'applicability_label' => $isApplicable ? 'ظاهر وقابل للإجابة' : 'مخفي حاليًا بسبب الشرط',
            'is_answered' => $isAnswered,
            'answer_display' => $isAnswered
                ? $question->formatAnswerValue($question->answer?->value)
                : ($isApplicable ? 'لم تتم الإجابة' : 'غير مطبق حاليًا'),
            'notes' => $question->answer?->notes,
            'needs_review' => (bool) $question->answer?->needs_review,
            'review_status' => $reviewStatusValue,
            'review_label' => match ($reviewStatusValue) {
                AnswerReviewStatus::REVIEWED->value => 'تمت المراجعة',
                AnswerReviewStatus::PENDING->value => ((bool) $question->answer?->needs_review || filled($question->answer?->notes))
                    ? 'مفتوح للمراجعة'
                    : 'لا توجد مراجعة معلقة',
                default => 'لا توجد مراجعة',
            },
            'dependency_summary' => $this->buildDependencySummary($question),
            'report_category' => $question->report_category,
            'target_entity' => $question->target_entity,
        ];
    }

    private function buildDependencySummary(QuestionnaireQuestion $question): ?string
    {
        if ($question->depends_on_question_id === null || $question->dependencyQuestion === null) {
            return null;
        }

        $operator = $question->dependency_operator?->value ?? 'unknown';
        $dependencyValue = $question->dependency_value;

        return 'يعتمد على السؤال رقم ' . $question->dependencyQuestion->sort_order
            . ' (' . $question->dependencyQuestion->title . ')'
            . ' — الشرط: ' . $operator
            . ' — القيمة: ' . ($dependencyValue !== null ? (string) $dependencyValue : 'غير محددة');
    }
}
