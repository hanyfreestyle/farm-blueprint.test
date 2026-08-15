<?php

namespace App\FilamentHelpers\Filters;


use Closure;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;

class FilterRelationManyToMany {

  protected string|Closure|null $categoryModelClass = null;
  protected string $setName = 'categories';
  protected string $setLabel = '';

  public function __construct() {
    $this->setLabel = __('default/lang.construct.category');
  }

  public static function make(): static {
    return new static();
  }

  public function setName(string $value): static {
    $this->setName = $value;
    return $this;
  }

  public function categoryModel(string|Closure $model): static {
    $this->categoryModelClass = $model;
    return $this;
  }

  public function setLabel(string $value): static {
    $this->setLabel = $value;
    return $this;
  }




#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

  public function getColumns() {

    return [
      SelectFilter::make($this->setName)
        ->label($this->setLabel)
        ->relationship($this->setName, 'id')
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
        ->columnSpan(4)
        ->multiple(true)
    ];

  }

}