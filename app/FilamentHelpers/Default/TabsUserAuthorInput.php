<?php

namespace App\FilamentHelpers\Default;

use App\FilamentHelpers\FilamentHelpersSetValues;
use App\FilamentHelpers\Form\Editors\CKEditor4;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
class TabsUserAuthorInput extends FilamentHelpersSetValues {

  public function getColumns(): array {
    $columns = collect($this->setAvailableLocales)
      ->map(fn ($locale) => Tab::make(__('default/lang.tab.content') . " " . strtoupper($locale))
        ->icon('heroicon-s-language')
        ->schema([
          ...self::getFormInputs($locale),
        ])->columns(2)
      )
      ->toArray();

    return $columns;
  }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getFormInputs($getLocale): array {
    $columns = [];

    $columns[] = TextInput::make("author_name.{$getLocale}")
      ->extraAttributes(fn () => rtlIfArabic($getLocale))
      ->label(__('default/users.author.author_name'))
      ->required();

    $columns[] = TextInput::make("job_title.{$getLocale}")
      ->extraAttributes(fn () => rtlIfArabic($getLocale))
      ->label(__('default/users.author.job_title'))
      ->required();

    $columns[] = Textarea::make("author_short_des.{$getLocale}")
      ->label(__('default/users.author.author_short_des'))
      ->columnSpanFull()
      ->extraAttributes(fn () => rtlIfArabic($getLocale));


    if ($this->setEditor) {
      $columns[] = CKEditor4::make("author_bio.{$getLocale}")
        ->label(__('default/users.author.author_bio'))
        ->reactive()
        ->extraAttributes([
          'locale' => $getLocale,
        ])->columnSpanFull();
    } else {
      $columns[] = Textarea::make("author_bio.{$getLocale}")
        ->label(__('default/users.author.author_bio'))
        ->rows($this->setTextareaRows)
        ->columnSpanFull()
        ->extraAttributes(fn () => rtlIfArabic($getLocale));
    }

    return $columns;
  }
}
