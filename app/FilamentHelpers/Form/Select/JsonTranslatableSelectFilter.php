<?php

namespace App\FilamentHelpers\Form\Select;

use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class JsonTranslatableSelectFilter extends SelectFilter {
  protected function setUp(): void {
    parent::setUp();

    $this->searchable();
    $this->preload();
  }

  public function fromJsonModel(
    string $modelClass,
    string $labelColumn = 'name',
    string $keyColumn = 'id'
  ): static {
    return $this->options(function () use ($modelClass, $labelColumn, $keyColumn) {
      $options = $modelClass::query()
        ->get([$keyColumn, $labelColumn])
        ->mapWithKeys(function (Model $row) use ($labelColumn, $keyColumn) {
          $value = data_get($row, $labelColumn);

          $name = is_array($value) ? $value : json_decode((string)$value, true);
          $name = is_array($name) ? $name : [];

          $locale = app()->getLocale();
          $label = $name[$locale] ?? $name['en'] ?? $name['ar'] ?? '';

          return [data_get($row, $keyColumn) => $label];
        })
        ->toArray();

      asort($options, SORT_NATURAL | SORT_FLAG_CASE);

      return $options;
    });
  }
}