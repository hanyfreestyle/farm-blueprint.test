<?php

namespace App\Filament\Resources\QuestionnaireAnswers\Pages;

use App\Enums\Questionnaire\AnswerReviewStatus;
use App\Filament\Resources\QuestionnaireAnswers\QuestionnaireAnswerResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditQuestionnaireAnswer extends EditRecord
{
    protected static string $resource = QuestionnaireAnswerResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['value'] = $this->normalizeValue($data);

        unset(
            $data['value_text'],
            $data['value_textarea'],
            $data['value_number'],
            $data['value_date'],
            $data['value_yes_no'],
            $data['value_option'],
            $data['value_options'],
        );

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('mark_reviewed')
                ->label(__('filament/resources/questionnaire_answers.actions.mark_reviewed'))
                ->color('success')
                ->visible(fn (): bool => $this->getRecord()->needs_review && $this->getRecord()->review_status === AnswerReviewStatus::PENDING)
                ->action(fn () => $this->getRecord()->update(['review_status' => AnswerReviewStatus::REVIEWED])),
            Action::make('mark_pending')
                ->label(__('filament/resources/questionnaire_answers.actions.mark_pending'))
                ->color('warning')
                ->visible(fn (): bool => $this->getRecord()->review_status === AnswerReviewStatus::REVIEWED)
                ->action(fn () => $this->getRecord()->update(['review_status' => AnswerReviewStatus::PENDING])),
        ];
    }

    protected function normalizeValue(array $data): mixed
    {
        foreach (['value_text', 'value_textarea', 'value_number', 'value_date', 'value_option'] as $field) {
            if (array_key_exists($field, $data) && $data[$field] !== null && $data[$field] !== '') {
                return $data[$field];
            }
        }

        if (array_key_exists('value_yes_no', $data)) {
            return (bool) $data['value_yes_no'];
        }

        if (! empty($data['value_options'])) {
            return array_values($data['value_options']);
        }

        return null;
    }
}
