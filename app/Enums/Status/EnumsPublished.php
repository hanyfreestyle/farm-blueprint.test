<?php

namespace App\Enums\Status;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsPublished: int {
    use EnumHasLabelOptionsTrait;

    case Published = 1;
    case UnPublished = 0;

    public function label(): string {
        return match ($this) {
            self::Published => __('enums/status.published.published'),
            self::UnPublished => __('enums/status.published.unpublished'),
        };
    }

    public function color(): string {
        return match ($this) {
            self::Published => 'success',
            self::UnPublished => 'gray',
        };
    }
}
