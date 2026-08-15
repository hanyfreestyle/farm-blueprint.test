<?php

namespace App\Enums\Setting;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsAvailableColors: string {

  use EnumHasLabelOptionsTrait;

  case Black = 'black';
  case White = 'white';
  case OffWhite = 'off_white';
  case Gray = 'gray';
  case DarkGray = 'dark_gray';
  case LightGray = 'light_gray';

  case Navy = 'navy';
  case Blue = 'blue';
  case LightBlue = 'light_blue';
  case SkyBlue = 'sky_blue';

  case Red = 'red';
  case Burgundy = 'burgundy';
  case Pink = 'pink';

  case Green = 'green';
  case DarkGreen = 'dark_green';
  case LightGreen = 'light_green';
  case Olive = 'olive';


  case Yellow = 'yellow';
  case Orange = 'orange';
  case Gold = 'gold';
  case Beige = 'beige';
  case Brown = 'brown';
//  case Tan = 'tan';

  case Purple = 'purple';
  case Violet = 'violet';

  case Turquoise = 'turquoise';
  case Teal = 'teal';

//  case Khaki = 'khaki';
//  case Camel = 'camel';

  case Silver = 'silver';
//  case Charcoal = 'charcoal';

  /**
   * Get the translated color label.
   */
  public function label(): string {
    return __("enums/general.colors.{$this->value}");
  }

  /**
   * Get the color hex code for frontend preview.
   */
  public function hex(): string {
    return match ($this) {
      self::Black => '#111827',
      self::White => '#FFFFFF',
      self::OffWhite => '#F8F5EC',
      self::Gray => '#6B7280',
      self::DarkGray => '#374151',
      self::LightGray => '#D1D5DB',

      self::Navy => '#12355B',
      self::Blue => '#2563EB',
      self::LightBlue => '#60A5FA',
      self::SkyBlue => '#38BDF8',

      self::Red => '#DC2626',
      self::Burgundy => '#7F1D1D',

      self::Pink => '#EC4899',

      self::Green => '#166534',
      self::DarkGreen => '#14532D',
      self::LightGreen => '#22C55E',
      self::Olive => '#556B2F',


      self::Yellow => '#FACC15',
      self::Orange => '#F59E0B',
      self::Gold => '#D4AF37',
      self::Beige => '#E8DCC4',

      self::Brown => '#8B4513',
//      self::Tan => '#D2B48C',

      self::Purple => '#7E22CE',
      self::Violet => '#8B5CF6',

      self::Turquoise => '#40E0D0',
      self::Teal => '#0F766E',

//      self::Khaki => '#BDB76B',
//      self::Camel => '#C19A6B',

      self::Silver => '#C0C0C0',
//      self::Charcoal => '#2F2F2F',
    };
  }

  /**
   * Get options for Filament Select, CheckboxList, or native forms.
   *
   * @return array<string, string>
   */
  public static function options(): array {
    return collect(self::cases())
      ->mapWithKeys(fn (self $color): array => [
        $color->value => $color->label(),
      ])
      ->toArray();
  }

  /**
   * Get hex colors mapped by value.
   *
   * @return array<string, string>
   */
  public static function hexOptions(): array {
    return collect(self::cases())
      ->mapWithKeys(fn (self $color): array => [
        $color->value => $color->hex(),
      ])
      ->toArray();
  }

  /**
   * Get one enum instance from a nullable value.
   */
  public static function fromNullable(?string $value): ?self {
    return filled($value) ? self::tryFrom($value) : null;
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
