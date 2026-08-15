<?php

namespace App\Enums\Status;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsArchived: int {
    use EnumHasLabelOptionsTrait;

    case ARCHIVED  = 1;
    case NOT_ARCHIVED  = 0;

    public function label(): string {
        return match ($this) {
            self::ARCHIVED => __('enums/status.archived.archived'),
            self::NOT_ARCHIVED  => __('enums/status.archived.not_archived'),
        };
    }
}
