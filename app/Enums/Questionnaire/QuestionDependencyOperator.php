<?php

namespace App\Enums\Questionnaire;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum QuestionDependencyOperator: string
{
    use EnumHasLabelOptionsTrait;

    case EQUALS = 'equals';
    case CONTAINS = 'contains';

    public function label(): string
    {
        return __("enums/questionnaire.question_dependency_operator.{$this->value}");
    }
}
