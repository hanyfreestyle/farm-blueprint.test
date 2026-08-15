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
        Artisan::call('migrate:fresh', ['--database' => 'sqlite']);

        $this->seed(QuestionnaireSectionSeeder::class);
        $this->seed(QuestionnaireFarmQuestionsSeeder::class);
    }

    public function test_home_route_renders_the_frontend_pilot_summary(): void
    {
        $response = $this->frontendGet($this->localizedRoute('home'));

        $response->assertOk();
        $response->assertSee('دراسة نظام إدارة مزرعة الأرانب');
        $response->assertSee('إدخال البيانات الأساسية للمزرعة');
    }

    public function test_sidebar_tree_renders_main_sections_and_subsections(): void
    {
        $subsection = $this->farmDataSubsection();

        $response = $this->frontendGet($this->localizedRoute('study.show', ['section' => $subsection]));

        $response->assertOk();
        $response->assertSee('إدخال البيانات الأساسية للمزرعة');
        $response->assertSee('بيانات المزرعة');
        $response->assertSee('بيانات العنبر');
    }

    public function test_zero_question_subsection_shows_a_safe_empty_state(): void
    {
        $emptySubsection = QuestionnaireSection::query()
            ->where('parent_id', $this->farmDataSubsection()->parent_id)
            ->where('sort_order', 2)
            ->firstOrFail();

        $response = $this->frontendGet($this->localizedRoute('study.show', ['section' => $emptySubsection]));

        $response->assertOk();
        $response->assertSee('لم تتم إضافة أسئلة لهذا القسم بعد.');
    }

    public function test_farm_data_page_contains_all_seeded_questions_on_one_page(): void
    {
        $response = $this->frontendGet($this->localizedRoute('study.show', ['section' => $this->farmDataSubsection()]));

        $response->assertOk();
        $response->assertSee('ما البيانات التي يجب أن يحتوي عليها ملف المزرعة؟');
        $response->assertSee('هل توجد بيانات أو متطلبات أخرى تخص المزرعة غير موجودة في التصور الحالي؟');
        $this->assertSame(15, substr_count($response->getContent(), 'data-question-block'));
    }

    public function test_answer_can_be_created_for_a_scalar_question(): void
    {
        $question = $this->questionBySortOrder(2);

        $response = $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $question]), [
            'value' => '1',
            'notes' => null,
        ]);

        $response->assertOk()->assertJson(['saved' => true, 'needs_review' => false]);

        $answer = QuestionnaireAnswer::query()->whereBelongsTo($question, 'question')->first();

        $this->assertNotNull($answer);
        $this->assertTrue($answer->value);
    }

    public function test_answer_update_keeps_one_answer_per_question(): void
    {
        $question = $this->questionBySortOrder(3);

        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $question]), [
            'value' => 'manual',
        ])->assertOk();

        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $question]), [
            'value' => 'automatic',
        ])->assertOk();

        $this->assertSame(1, QuestionnaireAnswer::query()->whereBelongsTo($question, 'question')->count());
        $this->assertSame(
            'automatic',
            QuestionnaireAnswer::query()->whereBelongsTo($question, 'question')->firstOrFail()->value,
        );
    }

    public function test_multi_choice_answer_persists_as_an_array(): void
    {
        $question = $this->questionBySortOrder(4);

        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $question]), [
            'value' => ['production', 'fattening'],
        ])->assertOk();

        $this->assertSame(
            ['production', 'fattening'],
            QuestionnaireAnswer::query()->whereBelongsTo($question, 'question')->firstOrFail()->value,
        );
    }

    public function test_notes_persist_and_trigger_needs_review(): void
    {
        $question = $this->questionBySortOrder(15);

        $response = $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $question]), [
            'value' => 'نحتاج حقول إضافية للتوثيق',
            'notes' => 'هذه النقطة تحتاج مراجعة',
        ]);

        $response->assertOk()->assertJson(['needs_review' => true]);

        $answer = QuestionnaireAnswer::query()->whereBelongsTo($question, 'question')->firstOrFail();

        $this->assertSame('هذه النقطة تحتاج مراجعة', $answer->notes);
        $this->assertTrue($answer->needs_review);
    }

    public function test_saved_values_are_restored_when_the_page_reloads(): void
    {
        $question = $this->questionBySortOrder(10);

        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $question]), [
            'value' => 'managed',
        ])->assertOk();

        $response = $this->frontendGet($this->localizedRoute('study.show', ['section' => $this->farmDataSubsection()]));

        $response->assertOk();
        $this->assertMatchesRegularExpression(
            '/name="question_10".*?value="managed".*?checked/s',
            $response->getContent(),
        );
    }

    public function test_conditional_question_is_hidden_until_dependency_contains_geolocation(): void
    {
        $subsection = $this->farmDataSubsection();

        $initialResponse = $this->frontendGet($this->localizedRoute('study.show', ['section' => $subsection]));
        $initialSubsection = app(QuestionnaireFrontendService::class)->getStudySubsection($subsection->id);
        $this->assertStringContainsString('data-question-id="'.$this->questionBySortOrder(9)->id.'"', $initialResponse->getContent());
        $this->assertFalse((bool) $initialSubsection->questions->firstWhere('sort_order', 9)?->is_applicable);

        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $this->questionBySortOrder(8)]), [
            'value' => ['governorate', 'city'],
        ])->assertOk();

        $hiddenResponse = $this->frontendGet($this->localizedRoute('study.show', ['section' => $subsection]));
        $hiddenSubsection = app(QuestionnaireFrontendService::class)->getStudySubsection($subsection->id);
        $this->assertStringContainsString('data-question-id="'.$this->questionBySortOrder(9)->id.'"', $hiddenResponse->getContent());
        $this->assertFalse((bool) $hiddenSubsection->questions->firstWhere('sort_order', 9)?->is_applicable);

        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $this->questionBySortOrder(8)]), [
            'value' => ['governorate', 'geolocation'],
        ])->assertOk();

        $visibleResponse = $this->frontendGet($this->localizedRoute('study.show', ['section' => $subsection]));
        $visibleSubsection = app(QuestionnaireFrontendService::class)->getStudySubsection($subsection->id);
        $this->assertStringContainsString('data-question-id="'.$this->questionBySortOrder(9)->id.'"', $visibleResponse->getContent());
        $this->assertTrue((bool) $visibleSubsection->questions->firstWhere('sort_order', 9)?->is_applicable);
    }

    public function test_subsection_progress_calculation_uses_only_applicable_questions(): void
    {
        $service = app(QuestionnaireFrontendService::class);

        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $this->questionBySortOrder(2)]), ['value' => '1'])->assertOk();
        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $this->questionBySortOrder(3)]), ['value' => 'manual'])->assertOk();
        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $this->questionBySortOrder(8)]), ['value' => ['governorate']])->assertOk();

        $summaryWithoutConditional = $service->getSubsectionProgressSummary($service->getStudySubsection($this->farmDataSubsection()->id));

        $this->assertSame(3, $summaryWithoutConditional['answered']);
        $this->assertSame(14, $summaryWithoutConditional['total']);

        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $this->questionBySortOrder(8)]), ['value' => ['governorate', 'geolocation']])->assertOk();
        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $this->questionBySortOrder(9)]), ['value' => 'map'])->assertOk();

        $summaryWithConditional = $service->getSubsectionProgressSummary($service->getStudySubsection($this->farmDataSubsection()->id));

        $this->assertSame(4, $summaryWithConditional['answered']);
        $this->assertSame(15, $summaryWithConditional['total']);
    }

    public function test_main_section_aggregated_progress_is_derived_from_child_subsections(): void
    {
        $service = app(QuestionnaireFrontendService::class);
        $mainSection = $this->farmDataSubsection()->parent()->firstOrFail();

        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $this->questionBySortOrder(2)]), ['value' => '1'])->assertOk();
        $this->postJson($this->localizedRoute('questionnaire.answers.store', ['question' => $this->questionBySortOrder(3)]), ['value' => 'manual'])->assertOk();

        $tree = $service->getMainSectionsTree();
        $mainSectionFromTree = $tree->firstWhere('id', $mainSection->id);

        $this->assertNotNull($mainSectionFromTree);
        $summary = $mainSectionFromTree->progress_summary;

        $this->assertSame(2, $summary['answered']);
        $this->assertSame(14, $summary['total']);
        $this->assertSame('in_progress', $summary['status']);
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

    private function frontendGet(string $url)
    {
        return $this->followingRedirects()->get($url);
    }

    private function localizedRoute(string $name, array $parameters = []): string
    {
        return route($name, array_merge(['locale' => 'ar'], $parameters));
    }
}
