<?php

namespace App\FilamentHelpers\Form\Select;


use Closure;
use Filament\Forms\Components\Select;

class CategoryMultiSelect extends Select {

  protected string|Closure|null $categoryModelClass = null;

  public function categoryModel(string|Closure $model): static {
    $this->categoryModelClass = $model;
    return $this;
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

  protected function setUp(): void {

    parent::setUp();
    $this
      ->label(__('default/lang.columns.category'))
      ->searchable()
      ->preload()
      ->multiple()
      ->relationship('categories', 'id')
      ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name)
      ->getSearchResultsUsing(function (string $search) {
        return $this->categoryModelClass::query()
          ->whereHas('translation', function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
          })
          ->limit(50)
          ->get()
          ->mapWithKeys(fn ($category) => [
            $category->id => $category->display_name,
          ]);
      })
      ->nullable();

  }

}