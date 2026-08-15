<?php

namespace App\FilamentHelpers\Form\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Closure;

class JsonTranslatableSelect extends Select {
  public static function make(string $name): static {
    $component = parent::make($name);
    return $component->searchable()->preload();
  }

  public function fromJsonModel(
    string $modelClass,
    string $labelColumn = 'name',
    string $keyColumn = 'id',
    Closure|array|null $filter = null
  ): static {
    return $this->options(function () use ($modelClass, $labelColumn, $keyColumn, $filter) {

      /** @var Builder $query */
      $query = $modelClass::query()
        ->select([$keyColumn, $labelColumn]);

      // لو عايز تضيف شرط ثابت زي animal_id (اختياري)
      // $query->where('animal_id', 1);

      // ✅ فلتر اختياري
      if ($filter instanceof Closure) {
        $filter($query); // مثال: fn($q)=>$q->where('animal_id',1)
      } elseif (is_array($filter)) {
        foreach ($filter as $condition) {
          // يدعم: ['col','=',val] أو ['col',val]
          $query->where(...$condition);
        }
      }

      $options = $query
        ->get()
        ->mapWithKeys(function (Model $row) use ($labelColumn, $keyColumn) {
          $value = data_get($row, $labelColumn);

          $name = is_array($value) ? $value : json_decode((string) $value, true);
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

//  public function fromJsonModel(
//    string $modelClass,
//    string $labelColumn = 'name',
//    string $keyColumn = 'id'
//  ): static {
//    return $this->options(function () use ($modelClass, $labelColumn, $keyColumn) {
//      $options = $modelClass::query()
//        ->get([$keyColumn, $labelColumn,'animal_id'])
//        ->where('animal_id',1)
//        ->mapWithKeys(function (Model $row) use ($labelColumn, $keyColumn) {
//          $value = data_get($row, $labelColumn);
//
//          $name = is_array($value) ? $value : json_decode((string)$value, true);
//          $name = is_array($name) ? $name : [];
//
//          $locale = app()->getLocale();
//          $label = $name[$locale] ?? $name['en'] ?? $name['ar'] ?? '';
//
//          return [data_get($row, $keyColumn) => $label];
//        })
//        ->toArray();
//
//      // ترتيب حسب الاسم (القيمة)
//      asort($options, SORT_NATURAL | SORT_FLAG_CASE);
//
//      return $options;
//    });
//  }

}