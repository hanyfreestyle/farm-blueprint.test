<?php

namespace App\FilamentHelpers\Filters;


use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Filament\Tables\Filters\Filter;


class NumericRangeFilter {
  protected string $name;
  protected string $column;
  protected ?string $label = null;
  protected bool $useMask = false;
  protected bool $hiddenLabel = true;

  public static function make(string $name, string $column): static {
    $instance = new static();
    $instance->name = $name;
    $instance->column = $column;
    return $instance;
  }

  public function label(string $label): static {
    $this->label = $label;
    return $this;
  }

  public function useMask(bool $useMask = true): static {
    $this->useMask = $useMask;
    return $this;
  }

  public function hiddenLabel(bool $hiddenLabel = true): static {
    $this->hiddenLabel = $hiddenLabel;
    return $this;
  }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function get(): Filter {
    $formKeyMin = "{$this->name}_min";
    $formKeyMax = "{$this->name}_max";
    $labelForm = $this->label . " " . __('default/lang.filter.from');
    $labelTo = $this->label . " " . __('default/lang.filter.to');

    return Filter::make($this->name)
      ->label($this->label)
      ->form([
        TextInput::make($formKeyMin)
          ->label($labelForm)
          ->extraAttributes(fn () => rtlIfArabic('en'))
          ->when($this->useMask, fn ($field) => $field
            ->mask(RawJs::make('$money($input)'))
            ->stripCharacters(',')
          )
          ->hiddenLabel($this->hiddenLabel)
          ->when($this->hiddenLabel, fn ($field) => $field
            ->placeholder($labelForm)
          )
          ->numeric(),

        TextInput::make($formKeyMax)
          ->label($labelTo)
          ->extraAttributes(fn () => rtlIfArabic('en'))
          ->when($this->useMask, fn ($field) => $field
            ->mask(RawJs::make('$money($input)'))
            ->stripCharacters(',')
          )
          ->hiddenLabel($this->hiddenLabel)
          ->when($this->hiddenLabel, fn ($field) => $field
            ->placeholder($labelTo)
          )
          ->numeric(),
      ])
      ->columns(2)
      ->query(function ($query, array $data) use ($formKeyMin, $formKeyMax) {
        return $query
          ->when(filled($data[$formKeyMin] ?? null), fn ($q) => $q->where($this->column, '>=', (float)str_replace(',', '', $data[$formKeyMin])))
          ->when(filled($data[$formKeyMax] ?? null), fn ($q) => $q->where($this->column, '<=', (float)str_replace(',', '', $data[$formKeyMax])));
      })
      ->indicateUsing(function (array $data) use ($formKeyMin, $formKeyMax, $labelForm, $labelTo) {
        $indicators = [];

        if ($data[$formKeyMin] ?? null) {
          $indicators[] = ($labelForm ?? __('default/lang.filter.from')) . ' ' . $data[$formKeyMin];
        }

        if ($data[$formKeyMax] ?? null) {
          $indicators[] = ($labelTo ?? __('default/lang.filter.to')) . ' ' . $data[$formKeyMax];
        }

        return $indicators;
      });
  }
}
