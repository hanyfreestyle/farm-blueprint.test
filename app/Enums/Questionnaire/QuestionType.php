<?php

namespace App\Enums\Questionnaire;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum QuestionType: string
{
    use EnumHasLabelOptionsTrait;

    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case NUMBER = 'number';
    case DATE = 'date';
    case YES_NO = 'yes_no';
    case SINGLE_CHOICE = 'single_choice';
    case MULTI_CHOICE = 'multi_choice';
    case SELECT = 'select';

    public function label(): string
    {
        return __("enums/questionnaire.question_type.{$this->value}");
    }
}
