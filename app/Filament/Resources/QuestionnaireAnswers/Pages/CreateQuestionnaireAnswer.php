<?php

namespace App\Filament\Resources\QuestionnaireAnswers\Pages;

use App\Filament\Resources\QuestionnaireAnswers\QuestionnaireAnswerResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateQuestionnaireAnswer extends CreateRecord
{
    protected static string $resource = QuestionnaireAnswerResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
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
