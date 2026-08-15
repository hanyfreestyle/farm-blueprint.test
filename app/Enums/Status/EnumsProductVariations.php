<?php

namespace App\Enums\Status;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsProductVariations: int {
  use EnumHasLabelOptionsTrait;

  case has_variations = 1;
  case has_no_variations = 0;

  public function label(): string {
    return match ($this) {
      self::has_variations => __('enums/status.has_variations.has_variations'),
      self::has_no_variations => __('enums/status.has_variations.has_no_variations'),
    };
  }
}
