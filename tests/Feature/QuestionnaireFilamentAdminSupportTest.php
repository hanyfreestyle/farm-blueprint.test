<?php

namespace Tests\Feature;

use App\Enums\Questionnaire\AnswerReviewStatus;
use App\Enums\Questionnaire\QuestionType;
use App\Filament\Resources\QuestionnaireQuestions\Schemas\QuestionnaireQuestionForm;
use App\Filament\Resources\QuestionnaireSections\Schemas\QuestionnaireSectionForm;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireQuestionOption;
use App\Models\QuestionnaireSection;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class QuestionnaireFilamentAdminSupportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        Artisan::call('migrate:fresh', ['--database' => 'sqlite']);
    }

    public function test_main_section_parent_choices_only_include_main_sections(): void
    {
        $main = QuestionnaireSection::create(['name' => 'Main']);
        $sub = QuestionnaireSection::create(['name' => 'Sub', 'parent_id' => $main->id]);

        $options = QuestionnaireSectionForm::getAvailableParentOptions();

        $this->assertArrayHasKey($main->id, $options);
        $this->assertArrayNotHasKey($sub->id, $options);
    }

    public function test_subsection_options_only_include_children_of_selected_main_section(): void
    {
        $mainA = QuestionnaireSection::create(['name' => 'Main A']);
        $mainB = QuestionnaireSection::create(['name' => 'Main B']);
        $subA = QuestionnaireSection::create(['name' => 'Sub A', 'parent_id' => $mainA->id]);
        $subB = QuestionnaireSection::create(['name' => 'Sub B', 'parent_id' => $mainB->id]);

        $options = QuestionnaireQuestionForm::getSubsectionOptions($mainA->id);

        $this->assertArrayHasKey($subA->id, $options);
        $this->assertArrayNotHasKey($subB->id, $options);
    }

    public function test_dependency_question_options_exclude_the_current_question(): void
    {
        $main = QuestionnaireSection::create(['name' => 'Main']);
        $sub = QuestionnaireSection::create(['name' => 'Sub', 'parent_id' => $main->id]);
        $questionA = QuestionnaireQuestion::create(['section_id' => $sub->id, 'title' => 'A', 'type' => QuestionType::TEXT]);
        $questionB = QuestionnaireQuestion::create(['section_id' => $sub->id, 'title' => 'B', 'type' => QuestionType::TEXT]);

        $options = QuestionnaireQuestionForm::getDependencyQuestionOptions($questionA, $sub->id);

        $this->assertArrayNotHasKey($questionA->id, $options);
        $this->assertArrayHasKey($questionB->id, $options);
    }

    public function test_report_category_options_include_the_approved_phase_two_values(): void
    {
        $options = QuestionnaireQuestionForm::getReportCategoryOptions();

        $this->assertSame(
            ['field', 'lookup', 'relationship', 'workflow', 'rule', 'alert', 'report', 'general'],
            array_keys($options),
        );
    }

    public function test_question_formats_yes_no_answer_readably(): void
    {
        $question = $this->makeQuestion(QuestionType::YES_NO);
        $answer = QuestionnaireAnswer::create([
            'question_id' => $question->id,
            'value' => true,
        ]);

        $this->assertSame(__('filament/resources/questionnaire_answers.values.yes'), $answer->fresh()->load('question')->formatValueForDisplay());
    }

    public function test_question_formats_option_answer_readably(): void
    {
        $question = $this->makeQuestion(QuestionType::SELECT);
        QuestionnaireQuestionOption::create([
            'question_id' => $question->id,
            'label' => 'مناسب مع تعديل',
            'value' => 'needs_changes',
        ]);

        $answer = QuestionnaireAnswer::create([
            'question_id' => $question->id,
            'value' => 'needs_changes',
        ]);

        $this->assertSame('مناسب مع تعديل', $answer->fresh()->load('question.options')->formatValueForDisplay());
    }

    public function test_question_formats_multi_choice_answer_readably(): void
    {
        $question = $this->makeQuestion(QuestionType::MULTI_CHOICE);
        QuestionnaireQuestionOption::create([
            'question_id' => $question->id,
            'label' => 'إنتاج',
            'value' => 'production',
        ]);
        QuestionnaireQuestionOption::create([
            'question_id' => $question->id,
            'label' => 'تسمين',
            'value' => 'fattening',
        ]);

        $answer = QuestionnaireAnswer::create([
            'question_id' => $question->id,
            'value' => ['production', 'fattening'],
        ]);

        $this->assertSame('إنتاج، تسمين', $answer->fresh()->load('question.options')->formatValueForDisplay());
    }

    public function test_notes_still_trigger_needs_review_in_admin_phase(): void
    {
        $question = $this->makeQuestion(QuestionType::TEXT);

        $answer = QuestionnaireAnswer::create([
            'question_id' => $question->id,
            'notes' => 'ملاحظة تحتاج مراجعة',
        ]);

        $this->assertTrue($answer->fresh()->needs_review);
    }

    public function test_review_status_update_still_controls_reviewed_at(): void
    {
        $question = $this->makeQuestion(QuestionType::TEXT);
        $answer = QuestionnaireAnswer::create([
            'question_id' => $question->id,
            'review_status' => AnswerReviewStatus::PENDING,
        ]);

        $answer->update(['review_status' => AnswerReviewStatus::REVIEWED]);

        $this->assertNotNull($answer->fresh()->reviewed_at);
    }

    private function makeQuestion(QuestionType $type): QuestionnaireQuestion
    {
        $main = QuestionnaireSection::create(['name' => fake()->unique()->word()]);
        $sub = QuestionnaireSection::create(['name' => fake()->unique()->word(), 'parent_id' => $main->id]);

        return QuestionnaireQuestion::create([
            'section_id' => $sub->id,
            'title' => fake()->sentence(),
            'type' => $type,
        ]);
    }
}
