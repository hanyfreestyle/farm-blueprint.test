<?php

namespace Tests\Feature;

use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireSection;
use App\Services\Questionnaire\QuestionnaireFrontendService;
use Database\Seeders\QuestionnaireFarmQuestionsSeeder;
use Database\Seeders\QuestionnaireSectionSeeder;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class QuestionnaireFrontendPilotTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        config()->set('questionnaire.show_zero_groups', false);
        Artisan::call('migrate:fresh', ['--database' => 'sqlite']);

        $this->seed(QuestionnaireSectionSeeder::class);
        $this->seed(QuestionnaireFarmQuestionsSeeder::class);
    }

    public function test_arabic_only_home_works_and_locale_switch_is_not_shown(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('دراسة نظام إدارة مزرعة الأرانب');
        $response->assertSee('lang="ar"', false);
        $response->assertDontSee('English');
        $response->assertDontSee('hreflang=');
    }

    public function test_respondent_routes_work_without_locale_prefix(): void
    {
        $response = $this->get(route('study.main-section', $this->farmMainSection()));

        $response->assertOk();
        $response->assertSee('إدخال البيانات الأساسية للمزرعة');
    }

    public function test_zero_question_groups_are_hidden_by_default(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('إدخال البيانات الأساسية للمزرعة');
        $response->assertDontSee('إعدادات التشغيل ودورة الإنتاج');
        $response->assertDontSee('بيانات العنبر');
    }

    public function test_show_zero_groups_true_restores_zero_question_visibility(): void
    {
        config()->set('questionnaire.show_zero_groups', true);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('إعدادات التشغيل ودورة الإنتاج');

        $mainSectionResponse = $this->get(route('study.main-section', $this->farmMainSection()));
        $mainSectionResponse->assertOk();
        $mainSectionResponse->assertSee('بيانات العنبر');
    }

    public function test_main_section_page_shows_subsections_only_and_not_questions(): void
    {
        $response = $this->get(route('study.main-section', $this->farmMainSection()));

        $response->assertOk();
        $response->assertSee('بيانات المزرعة');
        $response->assertDontSee('ما البيانات التي يجب أن يحتوي عليها ملف المزرعة؟');
    }

    public function test_subsection_route_opens_first_applicable_question(): void
    {
        $response = $this->get(route('study.subsection', [
            'mainSection' => $this->farmMainSection(),
            'subsection' => $this->farmDataSubsection(),
        ]));

        $response->assertRedirect(route('study.question', [
            'mainSection' => $this->farmMainSection(),
            'subsection' => $this->farmDataSubsection(),
            'question' => $this->questionBySortOrder(1),
        ]));
    }

    public function test_question_step_page_displays_one_question_only(): void
    {
        $response = $this->get($this->questionRoute(1));

        $response->assertOk();
        $response->assertSee('السؤال 1 من 14');
        $response->assertSee('ما البيانات التي يجب أن يحتوي عليها ملف المزرعة؟');
        $response->assertDontSee('هل اسم المزرعة يجب أن يكون من البيانات الإلزامية؟');
    }

    public function test_save_and_continue_saves_current_answer_and_redirects_to_next_question(): void
    {
        $question = $this->questionBySortOrder(2);

        $response = $this->post(route('questionnaire.answers.continue', $question), [
            'main_section_id' => $this->farmMainSection()->id,
            'subsection_id' => $this->farmDataSubsection()->id,
            'value' => '1',
            'notes' => '',
        ]);

        $response->assertRedirect($this->questionRoute(3));
        $this->assertTrue(QuestionnaireAnswer::query()->whereBelongsTo($question, 'question')->firstOrFail()->value);
    }

    public function test_required_validation_blocks_navigation(): void
    {
        $question = $this->questionBySortOrder(2);

        $response = $this->from($this->questionRoute(2))->post(route('questionnaire.answers.continue', $question), [
            'main_section_id' => $this->farmMainSection()->id,
            'subsection_id' => $this->farmDataSubsection()->id,
            'value' => '',
            'notes' => '',
        ]);

        $response->assertRedirect($this->questionRoute(2));
        $response->assertSessionHasErrors('value');
    }

    public function test_previous_navigation_is_rendered_for_non_first_question(): void
    {
        $response = $this->get($this->questionRoute(3));

        $response->assertOk();
        $response->assertSee('السابق');
        $response->assertSee(route('study.question', [
            'mainSection' => $this->farmMainSection(),
            'subsection' => $this->farmDataSubsection(),
            'question' => $this->questionBySortOrder(2),
        ]), false);
    }

    public function test_conditional_question_is_skipped_when_condition_is_false(): void
    {
        $this->postJson(route('questionnaire.answers.store', $this->questionBySortOrder(8)), [
            'value' => ['governorate', 'city'],
        ])->assertOk();

        $response = $this->post(route('questionnaire.answers.continue', $this->questionBySortOrder(8)), [
            'main_section_id' => $this->farmMainSection()->id,
            'subsection_id' => $this->farmDataSubsection()->id,
            'value' => ['governorate', 'city'],
            'notes' => '',
        ]);

        $response->assertRedirect($this->questionRoute(10));
    }

    public function test_conditional_question_is_included_when_condition_becomes_true(): void
    {
        $response = $this->post(route('questionnaire.answers.continue', $this->questionBySortOrder(8)), [
            'main_section_id' => $this->farmMainSection()->id,
            'subsection_id' => $this->farmDataSubsection()->id,
            'value' => ['governorate', 'geolocation'],
            'notes' => '',
        ]);

        $response->assertRedirect($this->questionRoute(9));
    }

    public function test_existing_answer_is_restored_when_revisiting_question(): void
    {
        $question = $this->questionBySortOrder(10);

        $this->postJson(route('questionnaire.answers.store', $question), [
            'value' => 'managed',
            'notes' => '',
        ])->assertOk();

        $response = $this->get($this->questionRoute(10));

        $response->assertOk();
        $this->assertMatchesRegularExpression('/name="value".*?value="managed".*?checked/s', $response->getContent());
    }

    public function test_notes_and_needs_review_remain_correct(): void
    {
        $question = $this->questionBySortOrder(15);

        $this->postJson(route('questionnaire.answers.store', $question), [
            'value' => 'تفصيل إضافي',
            'notes' => 'هذه النقطة تحتاج مراجعة',
        ])->assertOk();

        $answer = QuestionnaireAnswer::query()->whereBelongsTo($question, 'question')->firstOrFail();

        $this->assertSame('هذه النقطة تحتاج مراجعة', $answer->notes);
        $this->assertTrue($answer->needs_review);
    }

    public function test_subsection_progress_remains_derived_from_applicable_answers(): void
    {
        $service = app(QuestionnaireFrontendService::class);

        $this->postJson(route('questionnaire.answers.store', $this->questionBySortOrder(2)), ['value' => '1'])->assertOk();
        $this->postJson(route('questionnaire.answers.store', $this->questionBySortOrder(3)), ['value' => 'manual'])->assertOk();
        $this->postJson(route('questionnaire.answers.store', $this->questionBySortOrder(8)), ['value' => ['governorate']])->assertOk();

        $summary = $service->getSubsectionStepContext($this->farmMainSection()->id, $this->farmDataSubsection()->id)['progressSummary'];

        $this->assertSame(3, $summary['answered']);
        $this->assertSame(14, $summary['total']);
    }

    public function test_main_section_progress_aggregates_visible_subsections_only(): void
    {
        $service = app(QuestionnaireFrontendService::class);

        $this->postJson(route('questionnaire.answers.store', $this->questionBySortOrder(2)), ['value' => '1'])->assertOk();
        $this->postJson(route('questionnaire.answers.store', $this->questionBySortOrder(3)), ['value' => 'manual'])->assertOk();

        $mainSection = $service->getVisibleMainSection($this->farmMainSection()->id);
        $summary = $mainSection->progress_summary;

        $this->assertSame(2, $summary['answered']);
        $this->assertSame(14, $summary['total']);
        $this->assertSame('in_progress', $summary['status']);
    }

    public function test_completion_page_is_shown_after_final_applicable_question(): void
    {
        foreach ([1, 2, 3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14, 15] as $sortOrder) {
            $question = $this->questionBySortOrder($sortOrder);

            $this->postJson(route('questionnaire.answers.store', $question), [
                'value' => $this->defaultValueFor($question),
                'notes' => '',
            ])->assertOk();
        }

        $response = $this->post(route('questionnaire.answers.continue', $this->questionBySortOrder(15)), [
            'main_section_id' => $this->farmMainSection()->id,
            'subsection_id' => $this->farmDataSubsection()->id,
            'value' => 'تمت الإجابة',
            'notes' => '',
        ]);

        $response->assertRedirect(route('study.subsection.complete', [
            'mainSection' => $this->farmMainSection(),
            'subsection' => $this->farmDataSubsection(),
        ]));
    }

    private function farmMainSection(): QuestionnaireSection
    {
        return QuestionnaireSection::query()
            ->whereNull('parent_id')
            ->where('sort_order', 1)
            ->firstOrFail();
    }

    private function farmDataSubsection(): QuestionnaireSection
    {
        return QuestionnaireSection::query()
            ->whereNotNull('parent_id')
            ->where('name', 'بيانات المزرعة')
            ->firstOrFail();
    }

    private function questionBySortOrder(int $sortOrder): QuestionnaireQuestion
    {
        return QuestionnaireQuestion::query()
            ->whereBelongsTo($this->farmDataSubsection(), 'section')
            ->where('sort_order', $sortOrder)
            ->firstOrFail();
    }

    private function questionRoute(int $sortOrder): string
    {
        return route('study.question', [
            'mainSection' => $this->farmMainSection(),
            'subsection' => $this->farmDataSubsection(),
            'question' => $this->questionBySortOrder($sortOrder),
        ]);
    }

    private function defaultValueFor(QuestionnaireQuestion $question): mixed
    {
        return match ($question->type?->value) {
            'yes_no' => '1',
            'single_choice', 'select' => $question->options->first()?->value,
            'multi_choice' => [$question->options->first()?->value],
            'number' => '3',
            'date' => '2026-08-15',
            'textarea', 'text' => 'إجابة تجريبية',
            default => 'إجابة',
        };
    }
}
