<?php

namespace App\Enums\Questionnaire;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum AnswerReviewStatus: string
{
    use EnumHasLabelOptionsTrait;

    case PENDING = 'pending';
    case REVIEWED = 'reviewed';

    public function label(): string
    {
        return __("enums/questionnaire.answer_review_status.{$this->value}");
    }
}
