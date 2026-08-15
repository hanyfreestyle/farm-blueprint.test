<?php

namespace Tests\Feature;

use App\Enums\Questionnaire\AnswerReviewStatus;
use App\Enums\Questionnaire\QuestionDependencyOperator;
use App\Enums\Questionnaire\QuestionType;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use App\Models\QuestionnaireQuestionOption;
use App\Models\QuestionnaireSection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class QuestionnaireCoreDataModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');

        Artisan::call('migrate:fresh', ['--database' => 'sqlite']);
    }

    public function test_main_section_can_exist_without_parent_and_subsection_can_belong_to_main_section(): void
    {
        $mainSection = QuestionnaireSection::create([
            'name' => 'البيانات الأساسية',
            'sort_order' => 1,
        ]);

        $subsection = QuestionnaireSection::create([
            'parent_id' => $mainSection->id,
            'name' => 'المزرعة',
            'sort_order' => 2,
        ]);

        $this->assertNull($mainSection->parent);
        $this->assertTrue($mainSection->children->contains($subsection));
        $this->assertTrue($subsection->parent->is($mainSection));
    }

    public function test_section_ordering_scope_is_deterministic(): void
    {
        $mainSection = QuestionnaireSection::create(['name' => 'Main', 'sort_order' => 1]);

        QuestionnaireSection::create(['parent_id' => $mainSection->id, 'name' => 'Second', 'sort_order' => 10]);
        QuestionnaireSection::create(['parent_id' => $mainSection->id, 'name' => 'First', 'sort_order' => 5]);

        $orderedNames = $mainSection->children()->pluck('name')->all();

        $this->assertSame(['First', 'Second'], $orderedNames);
    }

    public function test_deleting_parent_with_children_is_prevented(): void
    {
        $this->expectException(QueryException::class);

        $mainSection = QuestionnaireSection::create(['name' => 'Main']);
        QuestionnaireSection::create([
            'parent_id' => $mainSection->id,
            'name' => 'Child',
        ]);

        $mainSection->delete();
    }

    public function test_deleting_section_with_questions_is_prevented(): void
    {
        $this->expectException(QueryException::class);

        $question = $this->createQuestion();

        $question->section->delete();
    }

    public function test_question_belongs_to_section_and_type_cast_works(): void
    {
        $section = QuestionnaireSection::create(['name' => 'Subsection']);

        $question = QuestionnaireQuestion::create([
            'section_id' => $section->id,
            'title' => 'ما اسم المزرعة؟',
            'type' => QuestionType::TEXT,
            'sort_order' => 5,
        ]);

        $this->assertTrue($question->section->is($section));
        $this->assertSame(QuestionType::TEXT, $question->type);
    }

    public function test_dependency_relationship_works_and_is_nulled_when_parent_is_deleted(): void
    {
        $section = QuestionnaireSection::create(['name' => 'Subsection']);

        $parentQuestion = QuestionnaireQuestion::create([
            'section_id' => $section->id,
            'title' => 'هل التصور مناسب؟',
            'type' => QuestionType::YES_NO,
        ]);

        $dependentQuestion = QuestionnaireQuestion::create([
            'section_id' => $section->id,
            'title' => 'ما التعديل المطلوب؟',
            'type' => QuestionType::TEXTAREA,
            'depends_on_question_id' => $parentQuestion->id,
            'dependency_operator' => QuestionDependencyOperator::EQUALS,
            'dependency_value' => 'needs_changes',
        ]);

        $this->assertTrue($dependentQuestion->dependencyQuestion->is($parentQuestion));
        $this->assertSame(QuestionDependencyOperator::EQUALS, $dependentQuestion->dependency_operator);

        $parentQuestion->delete();

        $dependentQuestion->refresh();

        $this->assertNull($dependentQuestion->depends_on_question_id);
        $this->assertSame(QuestionDependencyOperator::EQUALS, $dependentQuestion->dependency_operator);
        $this->assertSame('needs_changes', $dependentQuestion->dependency_value);
    }

    public function test_question_ordering_is_deterministic(): void
    {
        $section = QuestionnaireSection::create(['name' => 'Subsection']);

        QuestionnaireQuestion::create([
            'section_id' => $section->id,
            'title' => 'Second',
            'type' => QuestionType::TEXT,
            'sort_order' => 20,
        ]);

        QuestionnaireQuestion::create([
            'section_id' => $section->id,
            'title' => 'First',
            'type' => QuestionType::TEXT,
            'sort_order' => 10,
        ]);

        $orderedTitles = $section->questions()->pluck('title')->all();

        $this->assertSame(['First', 'Second'], $orderedTitles);
    }

    public function test_options_belong_to_question_and_are_ordered(): void
    {
        $question = $this->createQuestion(type: QuestionType::SINGLE_CHOICE);

        QuestionnaireQuestionOption::create([
            'question_id' => $question->id,
            'label' => 'ب',
            'value' => 'b',
            'sort_order' => 2,
        ]);

        QuestionnaireQuestionOption::create([
            'question_id' => $question->id,
            'label' => 'أ',
            'value' => 'a',
            'sort_order' => 1,
        ]);

        $orderedValues = $question->options()->pluck('value')->all();

        $this->assertSame(['a', 'b'], $orderedValues);
    }

    public function test_duplicate_option_value_under_same_question_is_rejected(): void
    {
        $this->expectException(QueryException::class);

        $question = $this->createQuestion(type: QuestionType::SELECT);

        QuestionnaireQuestionOption::create([
            'question_id' => $question->id,
            'label' => 'مناسب',
            'value' => 'approved',
        ]);

        QuestionnaireQuestionOption::create([
            'question_id' => $question->id,
            'label' => 'مناسب مرة أخرى',
            'value' => 'approved',
        ]);
    }

    public function test_same_option_value_under_different_questions_is_allowed(): void
    {
        $firstQuestion = $this->createQuestion(type: QuestionType::SELECT);
        $secondQuestion = $this->createQuestion(type: QuestionType::SELECT, title: 'Second');

        $firstOption = QuestionnaireQuestionOption::create([
            'question_id' => $firstQuestion->id,
            'label' => 'مناسب',
            'value' => 'approved',
        ]);

        $secondOption = QuestionnaireQuestionOption::create([
            'question_id' => $secondQuestion->id,
            'label' => 'مناسب',
            'value' => 'approved',
        ]);

        $this->assertModelExists($firstOption);
        $this->assertModelExists($secondOption);
    }

    public function test_question_has_one_answer_and_duplicate_answer_for_same_question_is_rejected(): void
    {
        $this->expectException(QueryException::class);

        $question = $this->createQuestion();

        QuestionnaireAnswer::create([
            'question_id' => $question->id,
            'value' => 'example',
        ]);

        QuestionnaireAnswer::create([
            'question_id' => $question->id,
            'value' => 'another',
        ]);
    }

    public function test_json_scalar_answer_value_works(): void
    {
        $question = $this->createQuestion(type: QuestionType::TEXT);

        $answer = QuestionnaireAnswer::create([
            'question_id' => $question->id,
            'value' => 'example',
        ]);

        $this->assertTrue($question->fresh()->answer->is($answer->fresh()));
        $this->assertSame('example', $answer->fresh()->value);
    }

    public function test_json_array_answer_value_works_for_multi_choice(): void
    {
        $question = $this->createQuestion(type: QuestionType::MULTI_CHOICE);

        $answer = QuestionnaireAnswer::create([
            'question_id' => $question->id,
            'value' => ['production', 'fattening'],
        ]);

        $this->assertSame(['production', 'fattening'], $answer->fresh()->value);
    }

    public function test_notes_automatically_set_and_clear_needs_review(): void
    {
        $question = $this->createQuestion();

        $answer = QuestionnaireAnswer::create([
            'question_id' => $question->id,
            'notes' => 'نحتاج أيضاً رقم السجل التجاري',
        ]);

        $this->assertTrue($answer->fresh()->needs_review);

        $answer->update([
            'notes' => '   ',
        ]);

        $this->assertFalse($answer->fresh()->needs_review);
    }

    public function test_reviewed_status_sets_and_pending_clears_reviewed_at(): void
    {
        $question = $this->createQuestion();

        $answer = QuestionnaireAnswer::create([
            'question_id' => $question->id,
            'review_status' => AnswerReviewStatus::REVIEWED,
        ]);

        $this->assertNotNull($answer->fresh()->reviewed_at);
        $this->assertSame(AnswerReviewStatus::REVIEWED, $answer->fresh()->review_status);

        $answer->update([
            'review_status' => AnswerReviewStatus::PENDING,
        ]);

        $answer->refresh();

        $this->assertNull($answer->reviewed_at);
        $this->assertSame(AnswerReviewStatus::PENDING, $answer->review_status);
    }

    private function createQuestion(
        QuestionType $type = QuestionType::TEXT,
        string $title = 'Sample question'
    ): QuestionnaireQuestion {
        $mainSection = QuestionnaireSection::create(['name' => fake()->unique()->word()]);
        $section = QuestionnaireSection::create([
            'parent_id' => $mainSection->id,
            'name' => fake()->unique()->word(),
        ]);

        return QuestionnaireQuestion::create([
            'section_id' => $section->id,
            'title' => $title,
            'type' => $type,
        ]);
    }
}
