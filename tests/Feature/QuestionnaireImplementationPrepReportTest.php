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

    public function test_structured_report_lists_questions_with_answers_and_dependency_context(): void
    {
        $this->seedPilotAnswers();

        $markdown = app(QuestionnaireImplementationPrepReportService::class)->buildReport();

        $this->assertStringContainsString('# تقرير الدراسة المنظم لإعداد التنفيذ', $markdown);
        $this->assertStringContainsString('### س1. ما البيانات التي يجب أن يحتوي عليها ملف المزرعة؟', $markdown);
        $this->assertStringContainsString('- الإجابة الحالية: اسم المزرعة، كود المزرعة', $markdown);
        $this->assertStringContainsString('يعتمد على السؤال رقم 8', $markdown);
    }

    public function test_structured_report_marks_unanswered_and_review_items(): void
    {
        $this->seedPilotAnswers(skipSortOrders: [15]);

        $markdown = app(QuestionnaireImplementationPrepReportService::class)->buildReport();

        $this->assertStringContainsString('# العناصر المفتوحة للمراجعة', $markdown);
        $this->assertStringContainsString('خارج الخدمة', $markdown);
        $this->assertStringContainsString('# الأسئلة غير المجاب عنها', $markdown);
        $this->assertStringContainsString('هل توجد بيانات أو متطلبات أخرى تخص المزرعة غير موجودة في التصور الحالي؟', $markdown);
    }

    public function test_preview_and_download_routes_work_without_touching_old_report(): void
    {
        $this->seedPilotAnswers();

        $previewResponse = $this->get(route('implementation-prep-report.preview'));
        $downloadResponse = $this->get(route('implementation-prep-report.download'));

        $previewResponse->assertOk();
        $previewResponse->assertSee('تقرير الدراسة المنظم لإعداد التنفيذ');
        $previewResponse->assertSee('تحميل التقرير MD');

        $downloadResponse->assertOk();
        $downloadResponse->assertHeader('content-disposition', 'attachment; filename=' . QuestionnaireImplementationPrepReportService::REPORT_FILENAME);
        $this->assertStringContainsString('تقرير الدراسة المنظم لإعداد التنفيذ', $downloadResponse->streamedContent());
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

    private function questionBySortOrder(int $sortOrder): QuestionnaireQuestion
    {
        return QuestionnaireQuestion::query()
            ->whereBelongsTo($this->farmDataSubsection(), 'section')
            ->where('sort_order', $sortOrder)
            ->with('options')
            ->firstOrFail();
    }

    private function seedPilotAnswers(array $overrides = [], array $skipSortOrders = []): void
    {
        $baseline = [
            1 => ['name', 'code'],
            2 => true,
            3 => 'automatic',
            4 => ['production', 'multiple'],
            5 => 'managed',
            6 => 'multiple_managers',
            7 => ['multiple_phones', 'email'],
            8 => ['governorate', 'city', 'geolocation'],
            9 => 'both',
            10 => 'managed',
            11 => ['active', 'other'],
            12 => 'fixed',
            13 => 'future_multi_farm',
            14 => true,
            15 => 'لا توجد',
        ];

        foreach (array_replace($baseline, $overrides) as $sortOrder => $value) {
            if (in_array($sortOrder, $skipSortOrders, true)) {
                continue;
            }

            $notes = $sortOrder === 11 ? 'خارج الخدمة' : null;

            $this->frontendService()->saveAnswer(
                $this->questionBySortOrder($sortOrder),
                $value,
                $notes,
            );
        }
    }
}
