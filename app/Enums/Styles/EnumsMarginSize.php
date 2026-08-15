<?php

namespace App\Enums\Styles;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsMarginSize: string {
  use EnumHasLabelOptionsTrait;

  case m0 = 'm-0';
  case m10 = 'm-10';
  case m20 = 'm-20';
  case m30 = 'm-30';
  case m40 = 'm-40';
  case m50 = 'm-50';
  case m60 = 'm-60';
  case m70 = 'm-70';
  case m80 = 'm-80';
  case m90 = 'm-90';
  case m100 = 'm-100';
  case m110 = 'm-110';
  case m120 = 'm-120';
  case m130 = 'm-130';
  case m140 = 'm-140';
  case m150 = 'm-150';


  public function size(): string {
    return explode('-', $this->value)[1]; // يرجع 10, 20, ...
  }

  public function label(): string {
    return "Margin " . $this->size();
  }
}

