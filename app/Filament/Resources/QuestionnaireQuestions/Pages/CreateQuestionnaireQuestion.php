<?php

namespace App\Filament\Resources\QuestionnaireQuestions\Pages;

use App\Enums\Questionnaire\QuestionType;
use App\Filament\Resources\QuestionnaireQuestions\QuestionnaireQuestionResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;
use Filament\Support\Exceptions\Halt;

class CreateQuestionnaireQuestion extends CreateRecord
{
    protected static string $resource = QuestionnaireQuestionResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['dependency_value'] = $data['dependency_value_select'] ?? $data['dependency_value_text'] ?? null;

        unset($data['main_section_id'], $data['has_dependency'], $data['dependency_value_select'], $data['dependency_value_text']);

        return $data;
    }

    protected function beforeCreate(): void
    {
        $this->guardQuestionState();
    }

    protected function guardQuestionState(): void
    {
        $data = $this->data ?? [];
        $type = QuestionType::tryFrom((string) ($data['type'] ?? ''));
        $options = $data['options'] ?? [];

        if (blank($data['section_id'] ?? null)) {
            Notification::make()->danger()->title(__('filament/resources/questionnaire_questions.messages.subsection_required'))->send();
            throw new Halt;
        }

        if (! in_array($type, [QuestionType::SINGLE_CHOICE, QuestionType::MULTI_CHOICE, QuestionType::SELECT], true) && filled($options)) {
            Notification::make()->danger()->title(__('filament/resources/questionnaire_questions.messages.options_type_change'))->send();
            throw new Halt;
        }

        if (in_array($type, [QuestionType::SINGLE_CHOICE, QuestionType::MULTI_CHOICE, QuestionType::SELECT], true) && count($options) < 1) {
            Notification::make()->danger()->title(__('filament/resources/questionnaire_questions.messages.options_required'))->send();
            throw new Halt;
        }

        if (($data['has_dependency'] ?? false) && (($data['depends_on_question_id'] ?? null) === null)) {
            Notification::make()->danger()->title(__('filament/resources/questionnaire_questions.messages.dependency_question_required'))->send();
            throw new Halt;
        }
    }
}
