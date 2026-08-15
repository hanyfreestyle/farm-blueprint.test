<?php

namespace App\FilamentHelpers\Default;

use App\FilamentHelpers\FilamentHelpersSetValues;
use App\FilamentHelpers\Form\Editors\CKEditor4;
use App\FilamentHelpers\Form\Translation\TranslatableSlugInput;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
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
class TabsDefaultContentInput extends FilamentHelpersSetValues {


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


    if ($this->setSlugInput) {
      $columns[] = TextInput::make("{$getLocale}.name")
        ->label($this->setNameTitle)
        ->extraAttributes(fn () => rtlIfArabic($getLocale))
        ->live(onBlur: true) // هنخليه reactive مع delay خفيف
        ->afterStateUpdated(function ($state, callable $get, callable $set) use ($getLocale) {
          $slugField = $getLocale . '.slug';
          // لو الحقل slug لسه فاضي، نحقن فيه قيمة slugify من name
          if (blank($get($slugField))) {
            $set($slugField, Url_Slug($state));
          }
        })
        ->required(fn ($get) => $get('is_published') === true);
    } else {
      $columns[] = TextInput::make("{$getLocale}.name")
        ->label($this->setNameTitle)
        ->extraAttributes(fn () => rtlIfArabic($getLocale))
        ->required(fn ($get) => $get('is_published') === true);
    }

    if ($this->setSlugInput) {
      $columns[] = TranslatableSlugInput::make("{$getLocale}.slug")
        ->setLocale($getLocale)
        ->uniqueForLocale($this->setTranslationTableName, 'slug')
        ->required(fn ($get) => $get('is_published') === true);
    }

    if ($this->setH1Heading) {
      $columns[] = TextInput::make("{$getLocale}.g_h1")
        ->label(__('default/lang.columns.g_title'))
        ->extraAttributes(fn () => rtlIfArabic($getLocale))
        ->hiddenLabel(false)
        ->required(fn ($get) => $get('is_published') === true && $this->setH1HeadingRequired);
    }

    if ($this->setShortDescription) {
      $columns[] = Textarea::make("{$getLocale}.short_des")
        ->label(__('default/lang.columns.short_des'))
        ->rows(4)
        ->extraAttributes(fn () => rtlIfArabic($getLocale))
        ->required(fn ($get) => $get('is_published') === true);
    }



    if ($this->setTagsInput) {
      $columns[] = TagsInput::make("{$getLocale}.tags")
        ->label($this->setTagTitle)
        ->hiddenLabel(false)
        ->extraAttributes(fn () => rtlIfArabic($getLocale))
        ->nullable();
    }

    if ($this->setDescriptionView) {
      if ($this->setEditor) {
        $columns[] = CKEditor4::make("{$getLocale}.des")
          ->label($this->setDesTitle)
          ->required(fn ($get) => $get('is_published') === true)
          ->reactive()
          ->extraAttributes([
            'locale' => $getLocale,
          ]);
      } else {
        $columns[] = Textarea::make("{$getLocale}.des")
          ->label($this->setDesTitle)
          ->rows($this->setTextareaRows)
          ->extraAttributes(fn () => rtlIfArabic($getLocale))
          ->required(fn ($get) => $get('is_published') === true);
      }
    }

    return $columns;
  }
}
