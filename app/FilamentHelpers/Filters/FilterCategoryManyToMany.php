<?php

namespace App\FilamentHelpers\Filters;


use Closure;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;

class FilterCategoryManyToMany extends SelectFilter {

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
      ->relationship('categories', 'id')
      ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name)
      ->searchable()
      ->preload()
      ->getSearchResultsUsing(function (string $search) {
        return $this->categoryModelClass::query()
          ->with('translation')
          ->whereHas('translation', function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
          })
          ->limit(50)
          ->get()
          ->mapWithKeys(fn ($category) => [
            $category->id => $category->display_name,
          ]);
      })
      ->indicateUsing(function (array $state): array {
        if (empty($state['values'])) {
          return [];
        }

        $categories = $this->categoryModelClass::query()
          ->whereIn('id', $state['values'])
          ->with('translation')
          ->get();

        return $categories->map(fn ($category) => Indicator::make($category->display_name)
        )->toArray();
      })

//      ->label(__('default/lang.columns.category'))
//      ->searchable()
//      ->preload()
//      ->multiple()
//      ->relationship('categories', 'id')
//      ->getOptionLabelFromRecordUsing(fn ($record) => $record->display_name)
//      ->getSearchResultsUsing(function (string $search) {
//        return $this->categoryModelClass::query()
//          ->whereHas('translation', function ($query) use ($search) {
//            $query->where('name', 'like', "%{$search}%");
//          })
//          ->limit(50)
//          ->get()
//          ->mapWithKeys(fn ($category) => [
//            $category->id => $category->display_name,
//          ]);
//      })
//      ->nullable();

      ->columnSpan(4)
      ->multiple(true);

  }

}