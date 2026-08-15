<?php

namespace App\Enums\Styles;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsMinHeight: string {
  use EnumHasLabelOptionsTrait;

  case minHeight10 = 'hv-10';
  case minHeight20 = 'hv-20';
  case minHeight30 = 'hv-30';
  case minHeight40 = 'hv-40';
  case minHeight50 = 'hv-50';
  case minHeight60 = 'hv-60';
  case minHeight70 = 'hv-70';
  case minHeight80 = 'hv-80';
  case minHeight90 = 'hv-90';
  case minHeight100 = 'hv-100';


  public function label(): string {
    return match ($this) {
      self::minHeight10 => "Min Height 10Vh",
      self::minHeight20 => "Min Height 20Vh",
      self::minHeight30 => "Min Height 30Vh",
      self::minHeight40 => "Min Height 40Vh",
      self::minHeight50 => "Min Height 50Vh",
      self::minHeight60 => "Min Height 60Vh",
      self::minHeight70 => "Min Height 70Vh",
      self::minHeight80 => "Min Height 80Vh",
      self::minHeight90 => "Min Height 90Vh",
      self::minHeight100 => "Min Height 100Vh",
    };
  }

}

