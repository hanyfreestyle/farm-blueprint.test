<?php

namespace App\FilamentHelpers\Filters;

use App\Enums\Status\EnumsActive;
use App\Enums\Status\EnumsFeatured;
use App\Enums\Status\EnumsPublished;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;


class DefaultFilters {
  public bool $setIsActive = true;
  public bool $setIsPublished = true;
  public bool $setCreatedRange = true;
  public bool $setTrashedFilter = true;
  public bool $setSeoStatusFilter = true;
  public bool $setIsFeatured = false;
  public bool $setHasPhoto = false;

  public function setIsActive(bool $value): static {
    $this->setIsActive = $value;
    return $this;
  }

  public function setIsFeatured(bool $value): static {
    $this->setIsFeatured = $value;
    return $this;
  }

  public function setIsPublished(bool $value): static {
    $this->setIsPublished = $value;
    return $this;
  }

  public function setCreatedRange(bool $value): static {
    $this->setCreatedRange = $value;
    return $this;
  }

  public function setTrashedFilter(bool $value): static {
    $this->setTrashedFilter = $value;
    return $this;
  }

  public function setSeoStatusFilter(bool $value): static {
    $this->setSeoStatusFilter = $value;
    return $this;
  }

  public function setHasPhoto(bool $value): static {
    $this->setHasPhoto = $value;
    return $this;
  }


  public static function make(): static {
    return new static();
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getColumns(): array {
    $columns = [];

    if ($this->setIsActive) {
      $columns[] = SelectFilter::make('is_active')
        ->label(__('default/lang.enum.active.label'))
        ->options(EnumsActive::options())
        ->searchable()
        ->preload();
    }

    if ($this->setIsPublished) {
      $columns[] = SelectFilter::make('is_published')
        ->label(__('default/lang.enum.Published.label'))
        ->options(EnumsPublished::options())
        ->searchable()
        ->preload();
    }

    if ($this->setIsFeatured) {
      $columns[] = SelectFilter::make('is_featured')
        ->label(__('default/lang.enum.featured.label'))
        ->options(EnumsFeatured::options())
        ->searchable()
        ->preload();
    }

    if ($this->setCreatedRange) {
      $columns[] = DateRangeFilter::make('created_at')
        ->label(__('default/lang.columns.created_at'));
    }


    if ($this->setTrashedFilter) {
      $columns[] = TrashedFilter::make()->searchable();
    }


    if ($this->setSeoStatusFilter) {
      $columns[] = SelectFilter::make('seo_status')
        ->label(__('default/lang.filter.seo_status'))
        ->options([
          'invalid' => __('default/lang.filter.seo_status_invalid'),
          'valid' => __('default/lang.filter.seo_status_valid'),
        ])
        ->searchable()
        ->preload()
        ->query(function (Builder $query, array $data): Builder {
          $locales = getProjectActiveLocales();

          $minTitle = (int)config('app.seo_title_min');
          $maxTitle = (int)config('app.seo_title_max');
          $minDesc = (int)config('app.seo_des_min');
          $maxDesc = (int)config('app.seo_des_max');

          return $query->where(function (Builder $query) use ($data, $locales, $minTitle, $maxTitle, $minDesc, $maxDesc) {
            foreach ($locales as $locale) {
              $query->orWhereHas('translations', function (Builder $q) use ($locale, $minTitle, $maxTitle, $minDesc, $maxDesc, $data) {
                $q->where('locale', $locale)
                  ->where(function (Builder $q) use ($minTitle, $maxTitle, $minDesc, $maxDesc, $data) {
                    if ($data['value'] === 'invalid') {
                      $q->whereRaw('CHAR_LENGTH(g_title) < ?', [$minTitle])
                        ->orWhereRaw('CHAR_LENGTH(g_title) > ?', [$maxTitle])
                        ->orWhereRaw('CHAR_LENGTH(g_des) < ?', [$minDesc])
                        ->orWhereRaw('CHAR_LENGTH(g_des) > ?', [$maxDesc]);
                    } elseif ($data['value'] === 'valid') {
                      $q->whereRaw('CHAR_LENGTH(g_title) >= ?', [$minTitle])
                        ->whereRaw('CHAR_LENGTH(g_title) <= ?', [$maxTitle])
                        ->whereRaw('CHAR_LENGTH(g_des) >= ?', [$minDesc])
                        ->whereRaw('CHAR_LENGTH(g_des) <= ?', [$maxDesc]);
                    }
                  });
              });
            }
          });
        });

    }

    if ($this->setHasPhoto) {
      $columns[] = TernaryFilter::make('has_image')
        ->label(__('default/lang.filter.has_image'))
        ->placeholder(__('default/lang.filter.has_image_all'))
        ->trueLabel(__('default/lang.filter.has_image_yes'))
        ->falseLabel(__('default/lang.filter.has_image_no'))
        ->preload(true)
        ->searchable()
        ->queries(
          true: fn (Builder $query) => $query
            ->whereNotNull('photo')
            ->where('photo', '!=', ''),
          false: fn (Builder $query) => $query
            ->whereNull('photo'),
          blank: fn (Builder $query) => $query,
        );
    }


    return $columns;
  }

}
