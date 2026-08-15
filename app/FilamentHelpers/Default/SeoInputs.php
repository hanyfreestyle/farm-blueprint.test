<?php

namespace App\FilamentHelpers\Default;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;

class SeoInputs {

  protected array $setAvailableLocales = ['ar', 'en'];
  protected bool $setViewAsTabs = true;
  protected bool $setSeoRequired = false;
  protected bool $setSeoCounter = true;
  protected bool $setTransMode = true;
  protected bool $setAddSeoInput = true;
  protected bool $setVisible = true;

  public static function make(): static {
    return new static();
  }

  public function __construct() {
    $this->setAvailableLocales = getProjectActiveLocales();
  }

  public function setAvailableLocales(array $value): static {
    $this->setAvailableLocales = $value;
    return $this;
  }

  public function setViewAsTabs(bool $value): static {
    $this->setViewAsTabs = $value;
    return $this;
  }

  public function setSeoRequired(bool $value): static {
    $this->setSeoRequired = $value;
    return $this;
  }

  public function setSeoCounter(bool $value): static {
    $this->setSeoCounter = $value;
    return $this;
  }

  public function setTransMode(bool $value): static {
    $this->setTransMode = $value;
    return $this;
  }

  public function setAddSeoInput(bool $value): static {
    $this->setAddSeoInput = $value;
    return $this;
  }

  public function setVisible(bool $value): static {
    $this->setVisible = $value;
    return $this;
  }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getColumns(): array {
    if (!$this->setAddSeoInput) {
      return [];
    }
    $columns = [];
    $inputs = [];


    foreach ($this->setAvailableLocales as $locale) {

      if ($this->setTransMode) {
        $titleName = $locale . '.g_title';
        $desName = $locale . '.g_des';
      } else {
        $titleName = 'g_title.' . $locale;
        $desName = 'g_des.' . $locale;
      }


      $printLang = "(" . ucfirst($locale) . ")";

      $inputs[] = TextInput::make($titleName)
        ->label(__('default/lang.columns.g_title') . " " . $printLang)
        ->extraAttributes(fn () => rtlIfArabic($locale))
//        ->live(onBlur: true)
//        ->afterStateUpdated(fn ($state, callable $set) => $set('page_title_length', mb_strlen($state)))
        ->required($this->setSeoRequired);


      if ($this->setSeoCounter) {
        $inputs[] = View::make('components.admin.settings.character-counter')
          ->visible(fn (callable $get) => filled($get($titleName)))
          ->viewData(fn (callable $get) => [
            'current' => mb_strlen($get($titleName) ?? ''),
            'min' => config('app.seo_title_min'),
            'max' => config('app.seo_title_max'),
          ]);
      }


      $inputs[] = Textarea::make($desName)
        ->label(__('default/lang.columns.g_des') . " " . $printLang)
        ->rows(3)
        ->extraAttributes(fn () => rtlIfArabic($locale))
        ->dehydrateStateUsing(fn ($state) => $state
          ? preg_replace('/[ \t]+/', ' ', trim($state))
          : null
        )
//        ->live(onBlur: true)
//        ->afterStateUpdated(fn ($state, callable $set) => $set('page_title_length', mb_strlen($state)))
        ->required($this->setSeoRequired);


      if ($this->setSeoCounter) {
        $inputs[] = View::make('components.admin.settings.character-counter')
          ->visible(fn (callable $get) => filled($get($desName)))
          ->viewData(fn (callable $get) => [
            'current' => mb_strlen($get($desName) ?? ''),
            'min' => config('app.seo_des_min'),
            'max' => config('app.seo_des_max'),
          ]);
      }
    }
    $inputs[] = Group::make()->schema([])->columns(4);

    $columns[] = Group::make()->schema($inputs)->columns(1);

    if ($this->setViewAsTabs) {
      return [
        Tab::make(__('default/lang.tab.seo'))
          ->icon('fas-tags')
          ->visible($this->setVisible)
          ->schema($columns)->columns(1)->columnSpanFull(),
      ];
    } else {
      return $columns;
    }
  }

}
