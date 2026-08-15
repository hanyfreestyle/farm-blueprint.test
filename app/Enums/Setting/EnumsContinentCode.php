<?php

namespace App\Enums\Setting;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EnumsContinentCode: string   {
  case AS = 'AS';
  case EU = 'EU';
  case AF = 'AF';
  case OC = 'OC';
  case NA = 'NA';
  case AN = 'AN';
  case SA = 'SA';

  public function label(): string {
    return __('enums/general.continent_code.' . $this->value);
  }


  public static function options(): array
  {
    return collect(self::cases())
      ->mapWithKeys(fn(self $case) => [$case->value => $case->label()])
      ->toArray();
  }

}
