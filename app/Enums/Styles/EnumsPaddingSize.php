<?php

namespace App\Enums\Styles;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsPaddingSize: string {
  use EnumHasLabelOptionsTrait;

  case p0 = 'p-0';
  case p10 = 'p-10';
  case p20 = 'p-20';
  case p30 = 'p-30';
  case p40 = 'p-40';
  case p50 = 'p-50';
  case p60 = 'p-60';
  case p70 = 'p-70';
  case p80 = 'p-80';
  case p90 = 'p-90';
  case p100 = 'p-100';
  case p110 = 'p-110';
  case p120 = 'p-120';
  case p130 = 'p-130';
  case p140 = 'p-140';
  case p150 = 'p-150';


  public function size(): string {
    return explode('-', $this->value)[1]; // يرجع 10, 20, ...
  }

  public function label(): string {
    return "Padding " . $this->size();
  }
}

