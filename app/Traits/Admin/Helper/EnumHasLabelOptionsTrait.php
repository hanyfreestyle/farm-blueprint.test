<?php

namespace App\Traits\Admin\Helper;

trait EnumHasLabelOptionsTrait {

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function options(): array {
    return collect(static::cases())
      ->mapWithKeys(fn ($case) => [
        $case->value => $case->label()
      ])
      ->toArray();
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public static function toSelectArray(string $idKey = 'id', string $nameKey = 'name'): array {
    return array_map(
      fn (self $case) => [
        $idKey => $case->value,
        $nameKey => $case->label(),
      ],
      self::cases()
    );
  }

//  // ✅ لو حبيت KV: [value => label]
//  public static function options(): array {
//    $out = [];
//    foreach (self::cases() as $case) {
//      $out[$case->value] = $case->label();
//    }
//    return $out;
//  }
//
}
