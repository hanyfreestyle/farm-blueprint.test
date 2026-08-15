<?php

namespace App\Enums\Status;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsFeatured: int {
    use EnumHasLabelOptionsTrait;

    case Featured  = 1;
    case NOT_Featured  = 0;

    public function label(): string {
        return match ($this) {
            self::Featured => __('enums/status.featured.featured'),
            self::NOT_Featured  => __('enums/status.featured.not_featured'),
        };
    }
}
