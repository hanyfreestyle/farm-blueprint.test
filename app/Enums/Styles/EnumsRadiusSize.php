<?php

namespace App\Enums\Styles;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsRadiusSize: string {
  use EnumHasLabelOptionsTrait;

  case radius0 = 'builder-radius-0';
  case radius10 = 'builder-radius-10';
  case radius20 = 'builder-radius-20';
  case radius30 = 'builder-radius-30';
  case radius40 = 'builder-radius-40';
  case radius50 = 'builder-radius-50';
  case radius60 = 'builder-radius-60';
  case radius70 = 'builder-radius-70';

  case radius80 = 'builder-radius-80';
  case radius90 = 'builder-radius-90';

  case radius100 = 'builder-radius-100';
  case radius200 = 'builder-radius-200';


  case radius300 = 'builder-radius-50-y';
  case radius400 = 'builder-radius-100-y';


  public function label(): string {
    return match ($this) {
      self::radius0 => "Builder Radius None",
      self::radius10 => "Builder Radius 10",
      self::radius20 => "Builder Radius 20",
      self::radius30 => "Builder Radius 30",
      self::radius40 => "Builder Radius 40",
      self::radius50 => "Builder Radius 50",
      self::radius60 => "Builder Radius 60",
      self::radius70 => "Builder Radius 70",
      self::radius80 => "Builder Radius 80",
      self::radius90 => "Builder Radius 90",
      self::radius100 => "Builder Radius 100",
      self::radius200 => "Builder Radius 200",
      self::radius300 => "Builder Radius 50%",
      self::radius400 => "Builder Radius 100%",

    };
  }
}

