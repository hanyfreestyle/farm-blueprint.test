<?php

namespace App\Services\Questionnaire;

use App\Enums\Questionnaire\AnswerReviewStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class QuestionnaireFinalRequirementsInputService
{
    public const REPORT_FILENAME = 'rabbit-farm-final-requirements-input.md';

    public function __construct(
        private readonly QuestionnaireImplementationPrepReportService $studyReportService,
    ) {
    }

    public function buildReport(): string
    {
        return $this->renderMarkdown($this->buildReportData());
    }

    public function buildReportData(): array
    {
        $studyData = $this->studyReportService->buildReportData();

        $mainSections = [];
        $includedQuestionCount = 0;
        $studyOnlyQuestionCount = 0;
        $unresolvedReviewItems = [];
        $unansweredItems = [];
        $missingQuestionKeyItems = [];

        foreach ($studyData['main_sections'] as $mainSection) {
            $subsections = [];

            foreach ($mainSection['subsections'] as $subsection) {
                $includedQuestions = [];

                foreach ($subsection['questions'] as $question) {
                    if ($this->isStudyOnlyQuestion($question)) {
                        $studyOnlyQuestionCount++;
                        continue;
                    }

                    if (! filled($question['seed_key'] ?? null)) {
                        $missingQuestionKeyItems[] = $this->makeBlockingItem($mainSection, $subsection, $question);
                        continue;
                    }

                    if (! $question['is_applicable']) {
                        continue;
                    }

                    if (! $question['is_answered']) {
                        $unansweredItems[] = $this->makeBlockingItem($mainSection, $subsection, $question);
                        continue;
                    }

                    if ($this->hasUnresolvedReview($question)) {
                        $unresolvedReviewItems[] = $this->makeBlockingItem($mainSection, $subsection, $question);
                        continue;
                    }

                    $includedQuestions[] = $question;
                    $includedQuestionCount++;
                }

                if ($includedQuestions === []) {
                    continue;
                }

                $subsections[] = [
                    'number' => $subsection['number'],
                    'name' => $subsection['name'],
                    'description' => $subsection['description'],
                    'questions' => $includedQuestions,
                ];
            }

            if ($subsections === []) {
                continue;
            }

            $mainSections[] = [
                'number' => $mainSection['number'],
                'name' => $mainSection['name'],
                'description' => $mainSection['description'],
                'subsections' => $subsections,
            ];
        }

        $blockingCount = count($unresolvedReviewItems)
            + count($unansweredItems)
            + count($missingQuestionKeyItems);

        return [
            'title' => 'ملف الإدخال النهائي لكتابة المتطلبات',
            'subtitle' => 'يحتوي فقط على الأسئلة النهائية القابلة للتحويل إلى Requirements، مع استبعاد أسئلة الدراسة المفتوحة والمراجعات غير المحسومة.',
            'generated_at' => Carbon::now(),
            'filename' => self::REPORT_FILENAME,
            'is_ready' => $blockingCount === 0,
            'stats' => [
                'included_questions' => $includedQuestionCount,
                'study_only_excluded' => $studyOnlyQuestionCount,
                'unresolved_reviews' => count($unresolvedReviewItems),
                'unanswered_questions' => count($unansweredItems),
                'missing_question_keys' => count($missingQuestionKeyItems),
                'blocking_items' => $blockingCount,
            ],
            'main_sections' => $mainSections,
            'unresolved_review_items' => $unresolvedReviewItems,
            'unanswered_items' => $unansweredItems,
            'missing_question_key_items' => $missingQuestionKeyItems,
        ];
    }

    public function renderMarkdown(array $reportData): string
    {
        $lines = [
            '# ' . $reportData['title'],
            '',
            $reportData['subtitle'],
            '',
            '## تعليمات إلزامية لوكيل كتابة الـRequirements',
            '',
            'استخدم هذا الملف مع المصادر التالية فقط:',
            '',
            '1. `docs/FARM_BLUEPRINT_PROJECT_CONTEXT.md`.',
            '2. ملف الدليل المطابق للقسم داخل `docs/questionnaire-guide/`.',
            '3. `docs/questionnaire-guide/REQUIREMENTS_CONFLICTS.md`.',
            '4. هذا الملف باعتباره مصدر الإجابات والقرارات النهائية.',
            '',
            '> لا تستخدم `تصور_مشروع_الارانب.md` لحسم أو تعديل الـRequirements في هذه المرحلة.',
            '',
            '> لا تخترع قرارًا عند وجود تعارض. سجله في `REQUIREMENTS_CONFLICTS.md` حسب البروتوكول المعتمد.',
            '',
            '## حالة الجاهزية',
            '',
            '| البند | القيمة |',
            '|---|---|',
            '| اسم الملف | `' . $reportData['filename'] . '` |',
            '| وقت التوليد | ' . $reportData['generated_at']->format('Y-m-d H:i:s') . ' |',
            '| جاهز لكتابة المتطلبات | ' . ($reportData['is_ready'] ? 'نعم' : 'لا') . ' |',
            '| الأسئلة النهائية المضمنة | ' . $reportData['stats']['included_questions'] . ' |',
            '| أسئلة دراسة مفتوحة مستبعدة | ' . $reportData['stats']['study_only_excluded'] . ' |',
            '| مراجعات غير محسومة | ' . $reportData['stats']['unresolved_reviews'] . ' |',
            '| أسئلة نهائية بلا إجابة | ' . $reportData['stats']['unanswered_questions'] . ' |',
            '| أسئلة نهائية بلا Question Key | ' . $reportData['stats']['missing_question_keys'] . ' |',
            '| إجمالي العناصر المانعة | ' . $reportData['stats']['blocking_items'] . ' |',
            '',
        ];

        if (! $reportData['is_ready']) {
            $lines[] = '> **تنبيه:** الملف غير جاهز للاعتماد النهائي حتى تتم معالجة العناصر المانعة الموضحة في نهاية الملف.';
            $lines[] = '';
        }

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

                foreach ($subsection['questions'] as $question) {
                    $lines[] = '### `' . $question['seed_key'] . '` — ' . $question['title'];
                    $lines[] = '';
                    $lines[] = '- Question Key: `' . $question['seed_key'] . '`';
                    $lines[] = '- التصنيف التقريرى: ' . ($question['report_category'] ?: 'غير محدد');
                    $lines[] = '- الكيان المستهدف: ' . ($question['target_entity'] ?: 'غير محدد');

                    if (filled($question['help_text'])) {
                        $lines[] = '- التوضيح: ' . $question['help_text'];
                    }

                    if (filled($question['dependency_summary'])) {
                        $lines[] = '- التبعية: ' . $question['dependency_summary'];
                    }

                    $lines[] = '- الإجابة النهائية: ' . $question['answer_display'];

                    if (
                        $question['review_status'] === AnswerReviewStatus::REVIEWED->value
                        && filled($question['notes'])
                    ) {
                        $lines[] = '- ملاحظات تمت مراجعتها: ' . $question['notes'];
                    }

                    $lines[] = '';
                }
            }
        }

        $this->appendBlockingSection(
            $lines,
            'مراجعات غير محسومة',
            $reportData['unresolved_review_items'],
            'يجب حسم هذه المراجعات قبل اعتبار الملف نهائيًا.',
        );

        $this->appendBlockingSection(
            $lines,
            'أسئلة نهائية بلا إجابة',
            $reportData['unanswered_items'],
            'يجب الإجابة على هذه الأسئلة أو إعادة تصنيفها قبل كتابة الـRequirements النهائية.',
        );

        $this->appendBlockingSection(
            $lines,
            'أسئلة نهائية بلا Question Key',
            $reportData['missing_question_key_items'],
            'يجب منح هذه الأسئلة Question Key ثابتًا قبل استخدامها في الربط مع أدلة التفسير.',
        );

        return implode("\n", $lines) . "\n";
    }

    private function isStudyOnlyQuestion(array $question): bool
    {
        $seedKey = (string) ($question['seed_key'] ?? '');

        return $question['report_category'] === 'manual_review'
            || Str::endsWith($seedKey, '.additional_requirements');
    }

    private function hasUnresolvedReview(array $question): bool
    {
        return $question['review_status'] === AnswerReviewStatus::PENDING->value
            && ($question['needs_review'] || filled($question['notes']));
    }

    private function makeBlockingItem(array $mainSection, array $subsection, array $question): array
    {
        return [
            'main_section' => $mainSection['name'],
            'subsection' => $subsection['name'],
            'seed_key' => $question['seed_key'] ?? null,
            'question' => $question['title'],
            'answer_display' => $question['answer_display'] ?? null,
            'notes' => $question['notes'] ?? null,
        ];
    }

    private function appendBlockingSection(array &$lines, string $title, array $items, string $description): void
    {
        $lines[] = '# ' . $title;
        $lines[] = '';
        $lines[] = $description;
        $lines[] = '';

        if ($items === []) {
            $lines[] = 'لا توجد عناصر.';
            $lines[] = '';
            return;
        }

        foreach ($items as $item) {
            $lines[] = '- **القسم الرئيسي:** ' . $item['main_section'];
            $lines[] = '  **القسم الفرعي:** ' . $item['subsection'];
            $lines[] = '  **Question Key:** `' . ($item['seed_key'] ?: 'غير محدد') . '`';
            $lines[] = '  **السؤال:** ' . $item['question'];

            if (filled($item['answer_display'])) {
                $lines[] = '  **الإجابة الحالية:** ' . $item['answer_display'];
            }

            if (filled($item['notes'])) {
                $lines[] = '  **الملاحظات:** ' . $item['notes'];
            }
        }

        $lines[] = '';
    }
}
