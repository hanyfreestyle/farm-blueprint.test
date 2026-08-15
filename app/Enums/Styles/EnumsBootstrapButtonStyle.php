<?php

namespace App\Enums\Styles;


enum EnumsBootstrapButtonStyle: string {

  case Primary = 'btn btn-primary';
  case Secondary = 'btn btn-secondary';
  case Success = 'btn btn-success';
  case Danger = 'btn btn-danger';
  case Warning = 'btn btn-warning';
  case Info = 'btn btn-info';
  case Light = 'btn btn-light';
  case Dark = 'btn btn-dark';

  case OutlinePrimary = 'btn btn-outline-primary';
  case OutlineSecondary = 'btn btn-outline-secondary';
  case OutlineSuccess = 'btn btn-outline-success';
  case OutlineDanger = 'btn btn-outline-danger';
  case OutlineWarning = 'btn btn-outline-warning';
  case OutlineInfo = 'btn btn-outline-info';
  case OutlineLight = 'btn btn-outline-light';
  case OutlineDark = 'btn btn-outline-dark';


  public function label(): string {
    return __('enums/styles.bootstrap_button_style.' . $this->value);
  }

  public static function options(): array {
    return collect(self::cases())
      ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
      ->toArray();
  }

}
