<?php

namespace Tests\Feature;

use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionnaireFrontendService;
use App\Services\Questionnaire\QuestionnaireTechnicalReportService;
use Database\Seeders\QuestionnaireFarmQuestionsSeeder;
use Database\Seeders\QuestionnaireSectionSeeder;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class QuestionnaireTechnicalReportPilotTest extends TestCase
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

    public function test_master_report_includes_all_main_sections_and_subsections(): void
    {
        $this->seedPilotAnswers();

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('# 1. إدخال البيانات الأساسية للمزرعة', $markdown);
        $this->assertStringContainsString('# 5. التقارير والإشعارات ومؤشرات الأداء', $markdown);
        $this->assertStringContainsString('## 1.2 بيانات العنبر', $markdown);
        $this->assertStringContainsString('## 5.21 مبدأ التقرير → التنبيه → القرار → الإجراء', $markdown);
    }

    public function test_zero_question_subsection_still_appears(): void
    {
        $this->seedPilotAnswers();

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('## 1.2 بيانات العنبر', $markdown);
        $this->assertStringContainsString('لم تتم إضافة أسئلة المراجعة لهذا القسم بعد.', $markdown);
    }

    public function test_section_numbering_follows_sort_order(): void
    {
        $barn = QuestionnaireSection::query()
            ->where('name', 'بيانات العنبر')
            ->whereNotNull('parent_id')
            ->firstOrFail();

        $barn->update(['sort_order' => 1]);
        $this->farmDataSubsection()->update(['sort_order' => 2]);
        $this->seedPilotAnswers();

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('## 1.1 بيانات العنبر', $markdown);
        $this->assertStringContainsString('## 1.2 بيانات المزرعة', $markdown);
    }

    public function test_farm_data_answers_generate_technical_interpretation(): void
    {
        $this->seedPilotAnswers();

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('#### اسم المزرعة', $markdown);
        $this->assertStringContainsString('يوصى باستخدام Enum ثابت لقيم حالة المزرعة.', $markdown);
        $this->assertStringContainsString('واجهة المزرعة يجب أن تدعم اختيار الموقع على الخريطة وإدخال الإحداثيات يدويًا.', $markdown);
    }

    public function test_unanswered_applicable_question_appears_as_unresolved(): void
    {
        $this->seedPilotAnswers(skipSortOrders: [15]);

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('### أسئلة غير محسومة', $markdown);
        $this->assertStringContainsString('هل توجد بيانات أو متطلبات أخرى تخص المزرعة غير موجودة في التصور الحالي؟', $markdown);
    }

    public function test_non_applicable_question_is_not_treated_as_unresolved(): void
    {
        $this->seedPilotAnswers(overrides: [
            8 => ['governorate', 'city'],
        ], skipSortOrders: [9]);

        $markdown = $this->reportService()->buildReport();

        $this->assertStringNotContainsString('إذا احتجنا تسجيل الموقع الجغرافي للمزرعة، كيف تريد إدخاله؟', $markdown);
    }

    public function test_yes_no_false_is_interpreted_as_valid_answer(): void
    {
        $this->seedPilotAnswers(overrides: [
            2 => false,
        ]);

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('| `name` | string | لا | بيانات المزرعة |', $markdown);
        $this->assertStringContainsString('اسم المزرعة غير إلزامي حاليًا.', $markdown);
    }

    public function test_fixed_farm_status_produces_enum(): void
    {
        $this->seedPilotAnswers();

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('## FarmStatus', $markdown);
        $this->assertStringContainsString('- `active` — نشطة', $markdown);
    }

    public function test_managed_farm_status_produces_lookup_recommendation(): void
    {
        $this->seedPilotAnswers(overrides: [
            12 => 'managed',
        ]);

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('## `farm_statuses`', $markdown);
        $this->assertStringContainsString('# Enums المقترحة', $markdown);
        $this->assertStringContainsString('لا توجد Enums معتمدة حاليًا.', $markdown);
    }

    public function test_fixed_activity_produces_enum(): void
    {
        $this->seedPilotAnswers(overrides: [
            5 => 'fixed',
        ]);

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('## FarmActivity', $markdown);
        $this->assertStringContainsString('- `production` — إنتاج', $markdown);
    }

    public function test_managed_activity_produces_lookup_recommendation(): void
    {
        $this->seedPilotAnswers();

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('## `farm_activities`', $markdown);
    }

    public function test_notes_and_free_text_appear_in_needs_review(): void
    {
        $this->seedPilotAnswers();

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('خارج الخدمة', $markdown);
        $this->assertStringContainsString('متطلب إضافي من المختص يحتاج مراجعة إدارية قبل تحويله إلى قرار تقني.', $markdown);
    }

    public function test_contradictions_appear_in_needs_review(): void
    {
        $this->seedPilotAnswers(overrides: [
            1 => ['name', 'phone'],
            3 => 'automatic',
        ]);

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('تم تحديد سلوك لكود المزرعة رغم أن حقول ملف المزرعة لا تتضمن كود المزرعة.', $markdown);
    }

    public function test_consolidated_sections_are_generated(): void
    {
        $this->seedPilotAnswers();

        $markdown = $this->reportService()->buildReport();

        $this->assertStringContainsString('# الكيانات المقترحة', $markdown);
        $this->assertStringContainsString('# الحقول المقترحة', $markdown);
        $this->assertStringContainsString('# Enums المقترحة', $markdown);
        $this->assertStringContainsString('# Lookup Tables المقترحة', $markdown);
        $this->assertStringContainsString('# قواعد العمل', $markdown);
        $this->assertStringContainsString('# متطلبات الواجهة', $markdown);
    }

    public function test_download_returns_markdown_filename_and_arabic_content(): void
    {
        $this->seedPilotAnswers();

        $response = $this->get(route('technical-report.download'));
        $content = $response->streamedContent();

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename=' . QuestionnaireTechnicalReportService::REPORT_FILENAME);
        $this->assertStringContainsString('المواصفات الفنية لنظام إدارة مزرعة الأرانب', $content);
        $this->assertStringContainsString('بيانات المزرعة', $content);
    }

    public function test_preview_route_uses_same_report_source(): void
    {
        $this->seedPilotAnswers();

        $response = $this->get(route('technical-report.preview'));

        $response->assertOk();
        $response->assertSee('التقرير الفني الرئيسي');
        $response->assertSee('تحميل التقرير MD');
        $response->assertSee('## 1.1 بيانات المزرعة');
    }

    private function reportService(): QuestionnaireTechnicalReportService
    {
        return app(QuestionnaireTechnicalReportService::class);
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
            1 => ['name', 'code', 'phone', 'governorate', 'area', 'address', 'started_at', 'status', 'notes'],
            2 => true,
            3 => 'automatic',
            4 => ['production', 'fattening', 'breeding', 'multiple'],
            5 => 'managed',
            6 => 'multiple_managers',
            7 => ['multiple_phones', 'email', 'whatsapp'],
            8 => ['governorate', 'city', 'area', 'address', 'geolocation'],
            9 => 'both',
            10 => 'managed',
            11 => ['active', 'stopped', 'maintenance', 'other'],
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
