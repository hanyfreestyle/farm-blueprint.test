<?php

namespace App\FilamentHelpers\Filters;

use Closure;
use Filament\Tables\Filters\SelectFilter;

class FilterCategoryBelongsTo extends SelectFilter {

  protected string|Closure|null $categoryModelClass = null;
  protected array $filter = [];
  protected bool $setActive = true;

  public function categoryModel(string|Closure $model): static {
    $this->categoryModelClass = $model;
    return $this;
  }

  public function setFilter(array $filter): static {
    $this->filter = $filter;
    return $this;
  }

  public function setActive(bool $value): static {
    $this->setActive = $value;
    return $this;
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

  protected function setUp(): void {
    parent::setUp();
    $this
      ->label(__('default/lang.columns.category'))
      ->options(function () {
        if (count($this->filter)) {
          return $this->categoryModelClass::with('translation')
            ->where($this->filter['row'], $this->filter['value'])
            ->get()->pluck('display_name', 'id');
        } else {
          return $this->categoryModelClass::with('translation')->get()->pluck('display_name', 'id');
        }
      })
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
      ->columnSpan(4)
      ->multiple();

  }

}