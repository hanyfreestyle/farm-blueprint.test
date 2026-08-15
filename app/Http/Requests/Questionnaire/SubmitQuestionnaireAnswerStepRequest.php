<?php

namespace App\Http\Requests\Questionnaire;

use Illuminate\Foundation\Http\FormRequest;

class SubmitQuestionnaireAnswerStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => ['nullable'],
            'notes' => ['nullable', 'string'],
            'main_section_id' => ['required', 'integer'],
            'subsection_id' => ['required', 'integer'],
        ];
    }
}
