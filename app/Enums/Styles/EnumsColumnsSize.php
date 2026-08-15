<?php

namespace App\Enums\Styles;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsColumnsSize: string {
  use EnumHasLabelOptionsTrait;

  case col1 = 'col-1';
  case col2 = 'col-2';
  case col3 = 'col-3';
  case col4 = 'col-4';
  case col6 = 'col-6';

  public function size(): string {
    return explode('-', $this->value)[1]; // يرجع 10, 20, ...
  }

  public function label(): string {
    return "Columns " . $this->size();
  }
}

