<?php

namespace Tests\Feature;

use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionnaireFinalRequirementsInputService;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class QuestionnaireFinalRequirementsInputTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        Artisan::call('migrate:fresh', ['--database' => 'sqlite']);
    }

    public function test_final_export_includes_question_key_and_excludes_study_only_questions(): void
    {
        $subsection = $this->createFarmSubsection();

        $finalQuestion = $this->createQuestion(
            $subsection,
            seedKey: 'farm.stopped_behavior',
            title: 'عندما تكون المزرعة متوقفة، ما التأثير المطلوب على التشغيل؟',
            type: QuestionType::YES_NO,
            reportCategory: 'rule',
            sortOrder: 1,
        );
        QuestionnaireAnswer::query()->create([
            'question_id' => $finalQuestion->id,
            'value' => true,
        ]);

        $studyOnlyQuestion = $this->createQuestion(
            $subsection,
            seedKey: 'farm.additional_requirements',
            title: 'هل توجد بيانات أو قواعد أخرى خاصة بملف المزرعة ترى ضرورة إضافتها؟',
            type: QuestionType::TEXTAREA,
            reportCategory: 'general',
            sortOrder: 2,
        );
        QuestionnaireAnswer::query()->create([
            'question_id' => $studyOnlyQuestion->id,
            'value' => 'ملاحظة تستخدم أثناء الدراسة فقط',
        ]);

        $markdown = app(QuestionnaireFinalRequirementsInputService::class)->buildReport();

        $this->assertStringContainsString('Question Key: `farm.stopped_behavior`', $markdown);
        $this->assertStringContainsString('جاهز لكتابة المتطلبات | نعم', $markdown);
        $this->assertStringNotContainsString('farm.additional_requirements', $markdown);
        $this->assertStringNotContainsString('ملاحظة تستخدم أثناء الدراسة فقط', $markdown);
    }

    public function test_unresolved_review_blocks_question_from_final_export(): void
    {
        $subsection = $this->createFarmSubsection();

        $question = $this->createQuestion(
            $subsection,
            seedKey: 'farm.stopped_behavior',
            title: 'عندما تكون المزرعة متوقفة، ما التأثير المطلوب على التشغيل؟',
            type: QuestionType::YES_NO,
            reportCategory: 'rule',
            sortOrder: 1,
        );
        QuestionnaireAnswer::query()->create([
            'question_id' => $question->id,
            'value' => true,
            'notes' => 'تحتاج هذه الإجابة إلى مراجعة قبل الاعتماد.',
        ]);

        $reportData = app(QuestionnaireFinalRequirementsInputService::class)->buildReportData();
        $markdown = app(QuestionnaireFinalRequirementsInputService::class)->renderMarkdown($reportData);

        $this->assertFalse($reportData['is_ready']);
        $this->assertSame(1, $reportData['stats']['unresolved_reviews']);
        $this->assertStringContainsString('جاهز لكتابة المتطلبات | لا', $markdown);
        $this->assertStringContainsString('farm.stopped_behavior', $markdown);
    }

    public function test_final_requirements_preview_and_download_routes_are_available(): void
    {
        $subsection = $this->createFarmSubsection();

        $question = $this->createQuestion(
            $subsection,
            seedKey: 'farm.stopped_behavior',
            title: 'عندما تكون المزرعة متوقفة، ما التأثير المطلوب على التشغيل؟',
            type: QuestionType::YES_NO,
            reportCategory: 'rule',
            sortOrder: 1,
        );
        QuestionnaireAnswer::query()->create([
            'question_id' => $question->id,
            'value' => true,
        ]);

        $previewResponse = $this->get(route('final-requirements-input.preview'));
        $downloadResponse = $this->get(route('final-requirements-input.download'));

        $previewResponse->assertOk();
        $previewResponse->assertSee('ملف الإدخال النهائي لكتابة المتطلبات');

        $downloadResponse->assertOk();
        $downloadResponse->assertHeader(
            'content-disposition',
            'attachment; filename=' . QuestionnaireFinalRequirementsInputService::REPORT_FILENAME,
        );
        $this->assertStringContainsString(
            'Question Key: `farm.stopped_behavior`',
            $downloadResponse->streamedContent(),
        );
    }

    private function createFarmSubsection(): QuestionnaireSection
    {
        $mainSection = QuestionnaireSection::query()->create([
            'name' => 'إدارة البيانات الأساسية',
            'description' => 'Master Data',
            'sort_order' => 1,
        ]);

        return QuestionnaireSection::query()->create([
            'parent_id' => $mainSection->id,
            'name' => 'بيانات المزرعة',
            'description' => 'Farm Data',
            'sort_order' => 1,
        ]);
    }

    private function createQuestion(
        QuestionnaireSection $section,
        string $seedKey,
        string $title,
        QuestionType $type,
        string $reportCategory,
        int $sortOrder,
    ): QuestionnaireQuestion {
        return QuestionnaireQuestion::query()->create([
            'section_id' => $section->id,
            'seed_key' => $seedKey,
            'title' => $title,
            'type' => $type,
            'is_required' => true,
            'sort_order' => $sortOrder,
            'report_category' => $reportCategory,
            'target_entity' => 'farm',
        ]);
    }
}
