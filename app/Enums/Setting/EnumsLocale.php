<?php

namespace App\Enums\Setting;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsLocale: string {
  use EnumHasLabelOptionsTrait;

  case AR = 'ar';
  case EN = 'en';


  public function label(): string {
    return match ($this) {
      self::AR => __('enums/general.locale.ar'),
      self::EN => __('enums/general.locale.en'),
    };
  }

  public static function toSelectArray(string $idKey = 'id', string $nameKey = 'name'): array {
    return array_map(
      fn (self $case) => [
        $idKey => $case->value,
        $nameKey => $case->label(),
      ],
      self::cases()
    );
  }

}
