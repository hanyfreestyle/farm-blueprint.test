<?php

namespace Tests\Feature;

use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionnaireFrontendService;
use App\Services\Questionnaire\QuestionnaireImplementationPrepReportService;
use Database\Seeders\QuestionnaireFarmQuestionsSeeder;
use Database\Seeders\QuestionnaireSectionSeeder;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class QuestionnaireImplementationPrepReportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        Artisan::call('migrate:fresh', ['--database' => 'sqlite']);

        $this->seed(QuestionnaireSectionSeeder::class);
        $this->seed(QuestionnaireFarmQuestionsSeeder::class);
    }

    public function test_structured_report_lists_questions_with_answers_and_question_keys(): void
    {
        $this->seedFarmAnswers();

        $markdown = app(QuestionnaireImplementationPrepReportService::class)->buildReport();

        $this->assertStringContainsString('# تقرير الدراسة المنظم لإعداد التنفيذ', $markdown);
        $this->assertStringContainsString('### س1. ما البيانات التي يجب أن يحتوي عليها ملف المزرعة؟', $markdown);
        $this->assertStringContainsString('- Question Key: `farm.fields`', $markdown);
        $this->assertStringContainsString('- الإجابة الحالية: اسم المزرعة، كود المزرعة', $markdown);
        $this->assertStringContainsString('- Question Key: `farm.stopped_behavior`', $markdown);
    }

    public function test_structured_report_marks_unanswered_and_review_items(): void
    {
        $this->seedFarmAnswers(skipSeedKeys: ['farm.additional_requirements']);

        $stoppedBehavior = $this->questionBySeedKey('farm.stopped_behavior');
        $stoppedBehavior->answer?->update(['notes' => 'برجاء مراجعة قاعدة الإيقاف']);

        $markdown = app(QuestionnaireImplementationPrepReportService::class)->buildReport();

        $this->assertStringContainsString('# العناصر المفتوحة للمراجعة', $markdown);
        $this->assertStringContainsString('برجاء مراجعة قاعدة الإيقاف', $markdown);
        $this->assertStringContainsString('**Question Key:** `farm.stopped_behavior`', $markdown);
        $this->assertStringContainsString('# الأسئلة غير المجاب عنها', $markdown);
        $this->assertStringContainsString('**Question Key:** `farm.additional_requirements`', $markdown);
    }

    public function test_preview_and_download_routes_work_without_touching_old_report(): void
    {
        $this->seedFarmAnswers();

        $previewResponse = $this->get(route('implementation-prep-report.preview'));
        $downloadResponse = $this->get(route('implementation-prep-report.download'));

        $previewResponse->assertOk();
        $previewResponse->assertSee('تقرير الدراسة المنظم لإعداد التنفيذ');
        $previewResponse->assertSee('تحميل التقرير MD');

        $downloadResponse->assertOk();
        $downloadResponse->assertHeader('content-disposition', 'attachment; filename=' . QuestionnaireImplementationPrepReportService::REPORT_FILENAME);
        $this->assertStringContainsString('Question Key: `farm.fields`', $downloadResponse->streamedContent());
    }

    private function frontendService(): QuestionnaireFrontendService
    {
        return app(QuestionnaireFrontendService::class);
    }

    private function farmDataSubsection(): QuestionnaireSection
    {
        return QuestionnaireSection::query()
            ->where('name', 'بيانات المزرعة')
            ->whereNotNull('parent_id')
            ->firstOrFail();
    }

    private function questionBySeedKey(string $seedKey): QuestionnaireQuestion
    {
        return QuestionnaireQuestion::query()
            ->whereBelongsTo($this->farmDataSubsection(), 'section')
            ->where('seed_key', $seedKey)
            ->with(['options', 'answer'])
            ->firstOrFail();
    }

    private function seedFarmAnswers(array $overrides = [], array $skipSeedKeys = []): void
    {
        $baseline = [
            'farm.fields' => ['name', 'code'],
            'farm.required_fields' => ['name'],
            'farm.code_strategy' => 'automatic_editable',
            'farm.unique_rule' => 'code_only',
            'farm.statuses' => ['active', 'stopped'],
            'farm.stopped_behavior' => 'freeze_operations',
            'farm.children_on_stop' => 'preserve_status_block_usage',
            'farm.multi_farm_timing' => 'single_now_multi_ready',
            'farm.delete_policy' => 'disable_only',
            'farm.additional_requirements' => 'لا توجد',
        ];

        foreach (array_replace($baseline, $overrides) as $seedKey => $value) {
            if (in_array($seedKey, $skipSeedKeys, true)) {
                continue;
            }

            $this->frontendService()->saveAnswer(
                $this->questionBySeedKey($seedKey),
                $value,
            );
        }
    }
}
