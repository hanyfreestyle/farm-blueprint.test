<?php

namespace App\Enums\Styles;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsDivTopPercentage : string {
  use EnumHasLabelOptionsTrait;

  case textStart = 'text-start';
  case textCenter = 'text-center';
  case textEnd = 'text-end';


  public function label(): string {
    return __('enums/styles.text_position.' . $this->value);
  }
}
