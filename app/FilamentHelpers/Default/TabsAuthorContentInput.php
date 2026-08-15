<?php

namespace App\FilamentHelpers\Default;

use App\FilamentHelpers\FilamentHelpersSetValues;
use App\FilamentHelpers\Form\Editors\CKEditor4;
use App\FilamentHelpers\Form\Translation\TranslatableSlugInput;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

/**
 * Document magic fluent setters for IDE:
 *
 * @method $this setAddCategoryPhoto(bool $value)
 * @method $this setCategoryFilterId(int $value)
 *
 */

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
class TabsAuthorContentInput extends FilamentHelpersSetValues {


  public function getColumns(): array {
    $columns = collect(getProjectActiveLocales())
      ->map(fn ($locale) => Tab::make(__('default/lang.tab.content') . " " . strtoupper($locale))
        ->icon('heroicon-s-language')
        ->schema([
          ...self::getFormInputs($locale)
        ])
      )
      ->toArray();
    return $columns;
  }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getFormInputs($getLocale): array {
    $columns = [];

    $columns[] = TextInput::make("{$getLocale}.author_name")
      ->label(__('default/users.author.author_name'))
      ->extraAttributes(fn () => rtlIfArabic($getLocale))
      ->required();

    $columns[] = TranslatableSlugInput::make("{$getLocale}.slug")
      ->setLocale($getLocale)
      ->uniqueForLocale($this->setTranslationTableName, 'slug')
      ->required();

    $columns[] = TextInput::make("{$getLocale}.job_title")
      ->label(__('default/users.author.job_title'))
      ->extraAttributes(fn () => rtlIfArabic($getLocale))
      ->columnSpanFull()
      ->required();

    $columns[] = Textarea::make("{$getLocale}.short_des")
      ->label(__('default/lang.columns.short_des'))
      ->columnSpanFull()
      ->rows(4)
      ->extraAttributes(fn () => rtlIfArabic($getLocale))
      ->required();

    $columns[] = CKEditor4::make("{$getLocale}.des")
      ->label($this->setDesTitle)
      ->required()
      ->columnSpanFull()
      ->setEditorHeight(250)
      ->reactive()
      ->extraAttributes([
        'locale' => $getLocale,
      ]);


    return $columns;
  }
}
