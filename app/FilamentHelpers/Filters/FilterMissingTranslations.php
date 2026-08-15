<?php

namespace App\FilamentHelpers\Filters;

use App\Traits\Admin\Helper\SmartSetFunctionTrait;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;


class FilterMissingTranslations {
  use SmartSetFunctionTrait;

  public string $setInputLabel = '';
  public string $setDbName = 'name';

  public bool $setCreatedRange = true;

  public function __construct() {
    $this->setInputLabel = __('default/lang.filter.missing_translations');
  }

  public function setInputLabel(string $value): static {
    $this->setInputLabel = $value;
    return $this;
  }

  public function setDbName(string $value): static {
    $this->setDbName = $value;
    return $this;
  }


  public static function make(): static {
    return new static();
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getColumns(): array {
    $columns = [];

    $columns[] = Filter::make('missing_arabic_name_ar')
      ->label($this->setInputLabel . " (Ar)")
      ->query(function (Builder $query) {
        $query->where(function (Builder $query) {
          $query
            ->whereDoesntHave('translations', function (Builder $q) {
              $q->where('locale', 'ar');
            })
            ->orWhereHas('translations', function (Builder $q) {
              $q->where('locale', 'ar')
                ->whereNull($this->setDbName);
            });
        });
      });

    $columns[] = Filter::make('missing_arabic_name_en')
      ->label($this->setInputLabel . " (En)")
      ->query(function (Builder $query) {
        $query->where(function (Builder $query) {
          $query
            ->whereDoesntHave('translations', function (Builder $q) {
              $q->where('locale', 'en');
            })
            ->orWhereHas('translations', function (Builder $q) {
              $q->where('locale', 'en')
                ->whereNull($this->setDbName);
            });
        });
      });

    $columns[] = Filter::make('missing_translation')
      ->label($this->setInputLabel . ' (Ar / En)')
      ->query(function (Builder $query) {
        $dbName = $this->setDbName;

        $query->where(function (Builder $query) use ($dbName) {
          // اللغة العربية
          $query
            ->whereDoesntHave('translations', function (Builder $q) {
              $q->where('locale', 'ar');
            })
            ->orWhereHas('translations', function (Builder $q) use ($dbName) {
              $q->where('locale', 'ar')
                ->whereNull($dbName);
            })
            // اللغة الإنجليزية
            ->orWhereDoesntHave('translations', function (Builder $q) {
              $q->where('locale', 'en');
            })
            ->orWhereHas('translations', function (Builder $q) use ($dbName) {
              $q->where('locale', 'en')
                ->whereNull($dbName);
            });
        });
      });
    return $columns;
  }

}
