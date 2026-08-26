<?php

namespace App\Services\Questionnaire;

use App\Models\QuestionnaireQuestion;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class QuestionnaireTreeExportService
{
    public const EXPORT_ROOT = 'questionnaire-export';

    private const MAIN_SECTION_DIRECTORIES = [
        1 => '01-master-data',
        2 => '02-farm-structure',
        3 => '03-animal-herd',
        4 => '04-workflow',
        5 => '05-reports',
        6 => '06-settings',
    ];

    public function __construct(
        private readonly QuestionnaireImplementationPrepReportService $studyReportService,
    ) {
    }

    /**
     * @return array<string, int|string>
     */
    public function export(): array
    {
        $reportData = $this->studyReportService->buildReportData();
        $questionsBySeedKey = $this->loadQuestionsBySeedKey();

        $exportRoot = base_path(self::EXPORT_ROOT);
        $answersRoot = $exportRoot . DIRECTORY_SEPARATOR . 'answers';
        $guidesRoot = $exportRoot . DIRECTORY_SEPARATOR . 'guides';

        File::ensureDirectoryExists($exportRoot);
        File::ensureDirectoryExists($answersRoot);

        $indexSections = [];
        $answerFileCount = 0;
        $guideFileCount = 0;

        foreach ($reportData['main_sections'] as $mainIndex => $mainSection) {
            $mainNumber = $mainIndex + 1;
            $mainDirectory = self::MAIN_SECTION_DIRECTORIES[$mainNumber]
                ?? sprintf('%02d-section', $mainNumber);

            File::ensureDirectoryExists($answersRoot . DIRECTORY_SEPARATOR . $mainDirectory);

            $subsectionEntries = [];

            foreach ($mainSection['subsections'] as $subIndex => $subsection) {
                $subNumber = $subIndex + 1;
                $filename = $this->subsectionFilename($subsection, $subNumber);
                $answerRelativePath = 'answers/' . $mainDirectory . '/' . $filename;
                $guideRelativePath = 'guides/' . $mainDirectory . '/' . $filename;
                $answerAbsolutePath = $exportRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $answerRelativePath);
                $guideAbsolutePath = $guidesRoot . DIRECTORY_SEPARATOR . $mainDirectory . DIRECTORY_SEPARATOR . $filename;

                $answerExported = false;

                if ($subsection['question_count'] > 0) {
                    File::put(
                        $answerAbsolutePath,
                        $this->renderAnswerFile(
                            $mainSection,
                            $subsection,
                            $questionsBySeedKey,
                        ),
                        true,
                    );

                    $answerExported = File::isFile($answerAbsolutePath);

                    if ($answerExported) {
                        $answerFileCount++;
                    }
                }

                $guideExists = File::isFile($guideAbsolutePath);

                if ($guideExists) {
                    $guideFileCount++;
                }

                $subsectionEntries[] = [
                    'number' => $subsection['number'],
                    'name' => $subsection['name'],
                    'answer_path' => $answerRelativePath,
                    'answer_exported' => $answerExported,
                    'guide_path' => $guideRelativePath,
                    'guide_exists' => $guideExists,
                    'question_count' => $subsection['question_count'],
                    'applicable_question_count' => $subsection['applicable_question_count'],
                    'answered_count' => $subsection['answered_count'],
                    'unanswered_count' => count($subsection['unanswered_questions']),
                    'review_count' => count($subsection['review_questions']),
                ];
            }

            $indexSections[] = [
                'number' => $mainSection['number'],
                'name' => $mainSection['name'],
                'subsections' => $subsectionEntries,
            ];
        }

        $stats = $reportData['stats'];
        $stats['answer_files'] = $answerFileCount;
        $stats['guide_files'] = $guideFileCount;

        File::put(
            $exportRoot . DIRECTORY_SEPARATOR . 'INDEX.md',
            $this->renderIndex($indexSections, $stats),
            true,
        );

        return [
            'main_sections' => $stats['main_sections'],
            'subsections' => $stats['subsections'],
            'questions' => $stats['questions'],
            'applicable_questions' => $stats['applicable_questions'],
            'answered_questions' => $stats['answered_questions'],
            'answer_files' => $answerFileCount,
            'guide_files' => $guideFileCount,
            'index_path' => self::EXPORT_ROOT . '/INDEX.md',
        ];
    }

    /**
     * @return Collection<string, QuestionnaireQuestion>
     */
    private function loadQuestionsBySeedKey(): Collection
    {
        return QuestionnaireQuestion::query()
            ->select([
                'id',
                'seed_key',
                'depends_on_question_id',
                'dependency_operator',
                'dependency_value',
            ])
            ->whereNotNull('seed_key')
            ->with([
                'options:id,question_id,label,value,sort_order',
                'answer:id,question_id,value,notes,needs_review,review_status,reviewed_at',
                'dependencyQuestion:id,seed_key,title,sort_order',
            ])
            ->orderBy('id')
            ->get()
            ->keyBy('seed_key');
    }

    private function renderAnswerFile(array $mainSection, array $subsection, Collection $questionsBySeedKey): string
    {
        $lines = [
            '# ' . $subsection['number'] . ' ' . $subsection['name'],
            '',
            '> **ملف مولد آليًا.** يعكس حالة الأسئلة والإجابات في قاعدة البيانات وقت التصدير، ولا يحتوي على تفسير تقني مستنتج.',
            '',
            '## معلومات القسم',
            '',
            '- القسم الرئيسي: ' . $mainSection['name'],
            '- القسم الفرعي: ' . $subsection['name'],
            '- وقت التصدير: ' . now()->format('Y-m-d H:i:s'),
            '- إجمالي الأسئلة: ' . $subsection['question_count'],
            '- الأسئلة القابلة حاليًا: ' . $subsection['applicable_question_count'],
            '- المجاب عنها: ' . $subsection['answered_count'],
            '- غير المجاب عنها: ' . count($subsection['unanswered_questions']),
            '- المفتوحة للمراجعة: ' . count($subsection['review_questions']),
            '',
        ];

        if (filled($subsection['description'])) {
            $lines[] = '## وصف القسم';
            $lines[] = '';
            $lines[] = trim((string) $subsection['description']);
            $lines[] = '';
        }

        foreach ($subsection['questions'] as $question) {
            /** @var QuestionnaireQuestion|null $questionModel */
            $questionModel = filled($question['seed_key'])
                ? $questionsBySeedKey->get($question['seed_key'])
                : null;

            $lines[] = '---';
            $lines[] = '';
            $lines[] = '## س' . $question['sort_order'] . '. ' . $question['title'];
            $lines[] = '';
            $lines[] = '- **Question Key:** `' . ($question['seed_key'] ?: 'غير محدد') . '`';
            $lines[] = '- **النوع:** `' . $question['type_label'] . '`';
            $lines[] = '- **مطلوب:** ' . ($question['is_required'] ? 'نعم' : 'لا');
            $lines[] = '- **الحالة الحالية:** ' . $question['applicability_label'];
            $lines[] = '- **التصنيف التقريري:** `' . ($question['report_category'] ?: 'غير محدد') . '`';
            $lines[] = '- **الكيان المستهدف:** `' . ($question['target_entity'] ?: 'غير محدد') . '`';
            $lines[] = '';

            if (filled($question['help_text'])) {
                $lines[] = '### التوضيح';
                $lines[] = '';
                $lines[] = trim((string) $question['help_text']);
                $lines[] = '';
            }

            $this->appendOptions($lines, $question, $questionModel);
            $this->appendDependency($lines, $question, $questionModel);

            $lines[] = '### الإجابة';
            $lines[] = '';
            $lines[] = '**الإجابة الحالية:** ' . $question['answer_display'];
            $lines[] = '';

            if ($questionModel?->answer !== null) {
                $encoded = json_encode(
                    $questionModel->answer->value,
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT,
                );

                $lines[] = '**القيمة الخام المحفوظة:**';
                $lines[] = '';
                $lines[] = '```json';
                $lines[] = $encoded === false ? 'null' : $encoded;
                $lines[] = '```';
                $lines[] = '';
            }

            $lines[] = '### المراجعة';
            $lines[] = '';
            $lines[] = '- **الحالة:** ' . $question['review_label'];

            if (filled($question['notes'])) {
                $lines[] = '- **ملاحظات المختص:** ' . trim((string) $question['notes']);
            }

            $lines[] = '';
        }

        return implode("\n", $lines) . "\n";
    }

    private function appendOptions(array &$lines, array $question, ?QuestionnaireQuestion $questionModel): void
    {
        if ($question['type_label'] === 'yes_no') {
            $lines[] = '### الاختيارات';
            $lines[] = '';
            $lines[] = '- `1` — نعم';
            $lines[] = '- `0` — لا';
            $lines[] = '';

            return;
        }

        if ($questionModel === null || $questionModel->options->isEmpty()) {
            return;
        }

        $lines[] = '### الاختيارات';
        $lines[] = '';

        foreach ($questionModel->options as $option) {
            $lines[] = '- `' . $option->value . '` — ' . $option->label;
        }

        $lines[] = '';
    }

    private function appendDependency(array &$lines, array $question, ?QuestionnaireQuestion $questionModel): void
    {
        if ($questionModel?->dependencyQuestion !== null) {
            $lines[] = '### Dependency';
            $lines[] = '';
            $lines[] = '- **Parent Question Key:** `' . ($questionModel->dependencyQuestion->seed_key ?: 'غير محدد') . '`';
            $lines[] = '- **Parent Question:** ' . $questionModel->dependencyQuestion->title;
            $lines[] = '- **Operator:** `' . ($questionModel->dependency_operator?->value ?? 'غير محدد') . '`';
            $lines[] = '- **Dependency Value:** `' . ($questionModel->dependency_value ?? 'غير محددة') . '`';
            $lines[] = '';

            return;
        }

        if (filled($question['dependency_summary'])) {
            $lines[] = '### Dependency';
            $lines[] = '';
            $lines[] = $question['dependency_summary'];
            $lines[] = '';
        }
    }

    private function renderIndex(array $indexSections, array $stats): string
    {
        $lines = [
            '# Questionnaire Export Index',
            '',
            '> يتم تحديث هذا الملف تلقائيًا عند استخدام زر **تحديث شجرة الأسئلة والإجابات** من الصفحة الرئيسية.',
            '',
            '## حالة التصدير',
            '',
            '- وقت آخر تحديث: ' . now()->format('Y-m-d H:i:s'),
            '- الأقسام الرئيسية: ' . $stats['main_sections'],
            '- الأقسام الفرعية: ' . $stats['subsections'],
            '- إجمالي الأسئلة: ' . $stats['questions'],
            '- الأسئلة القابلة حاليًا: ' . $stats['applicable_questions'],
            '- الأسئلة المجاب عنها: ' . $stats['answered_questions'],
            '- ملفات الإجابات المصدرة: ' . $stats['answer_files'],
            '- ملفات الشرح الموجودة: ' . $stats['guide_files'],
            '',
            '### معنى الحالات',
            '',
            '- ✅ **تم التصدير:** ملف الإجابات موجود داخل `answers/`.',
            '- ❌ **لم يتم التصدير:** لا يوجد ملف إجابات للقسم حاليًا.',
            '- ✅ **تم إنشاء الشرح:** ملف الشرح المقابل موجود فعليًا داخل `guides/`.',
            '- ❌ **لم يتم إنشاء الشرح:** ملف الشرح المقابل غير موجود بعد.',
            '',
        ];

        foreach ($indexSections as $mainSection) {
            $lines[] = '## ' . $mainSection['number'] . '. ' . $mainSection['name'];
            $lines[] = '';
            $lines[] = '| # | القسم الفرعي | ملف الإجابات | حالة التصدير | التقدم | ملف الشرح | حالة الشرح |';
            $lines[] = '|---|---|---|---|---|---|---|';

            foreach ($mainSection['subsections'] as $subsection) {
                $answerCell = $subsection['answer_exported']
                    ? '[' . $subsection['answer_path'] . '](' . $subsection['answer_path'] . ')'
                    : '`' . $subsection['answer_path'] . '`';

                $guideCell = $subsection['guide_exists']
                    ? '[' . $subsection['guide_path'] . '](' . $subsection['guide_path'] . ')'
                    : '`' . $subsection['guide_path'] . '`';

                $progress = $subsection['answered_count'] . ' / ' . $subsection['applicable_question_count'];

                $lines[] = '| ' . $subsection['number']
                    . ' | ' . $subsection['name']
                    . ' | ' . $answerCell
                    . ' | ' . ($subsection['answer_exported'] ? '✅ تم التصدير' : '❌ لم يتم التصدير')
                    . ' | ' . $progress
                    . ' | ' . $guideCell
                    . ' | ' . ($subsection['guide_exists'] ? '✅ تم الإنشاء' : '❌ لم يتم الإنشاء')
                    . ' |';
            }

            $lines[] = '';
        }

        $lines[] = '---';
        $lines[] = '';
        $lines[] = '## قاعدة التحديث';
        $lines[] = '';
        $lines[] = '- ملفات `answers/` مولدة آليًا ويتم استبدال محتوى الملف عند كل تحديث جديد.';
        $lines[] = '- `INDEX.md` مولد آليًا ويعكس حالة الشجرة الحالية.';
        $lines[] = '- `guides/` لا يتم إنشاؤها أو تعديلها أو حذفها بواسطة زر التحديث.';
        $lines[] = '- `README.md` ملف توثيق يدوي ولا يقوم زر التحديث بتعديله.';
        $lines[] = '';

        return implode("\n", $lines) . "\n";
    }

    private function subsectionFilename(array $subsection, int $subNumber): string
    {
        $prefix = collect($subsection['questions'])
            ->pluck('seed_key')
            ->filter()
            ->map(fn (string $seedKey): string => Str::before($seedKey, '.'))
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->first();

        if (! is_string($prefix) || $prefix === '') {
            return sprintf('%02d-section.md', $subNumber);
        }

        $parts = array_values(array_filter(explode('_', strtolower($prefix))));

        if ($parts === []) {
            return sprintf('%02d-section.md', $subNumber);
        }

        $lastIndex = array_key_last($parts);
        $parts[$lastIndex] = Str::plural($parts[$lastIndex]);
        $slug = preg_replace('/[^a-z0-9-]+/', '-', implode('-', $parts));
        $slug = trim((string) $slug, '-');

        return sprintf('%02d-%s.md', $subNumber, $slug !== '' ? $slug : 'section');
    }
}
