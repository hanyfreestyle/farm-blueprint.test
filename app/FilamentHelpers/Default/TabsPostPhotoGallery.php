<?php

namespace App\FilamentHelpers\Default;

use App\Enums\Styles\EnumsGalleryView;
use App\FilamentHelpers\UploadFile\WebpUploadWithFilter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Support\HtmlString;


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
class TabsPostPhotoGallery {

  protected string $setGalleryUploadDirectory = 'uploads';
  protected int $setGalleryFilter = 0;
  protected bool $setGalleryVisible = false;


  public static function make(): static {
    return new static();
  }

  public function setGalleryUploadDirectory(string $value): static {
    $this->setGalleryUploadDirectory = $value;
    return $this;
  }

  public function setGalleryFilter(int $value): static {
    $this->setGalleryFilter = $value;
    return $this;
  }

  public function setGalleryVisible(bool $value): static {
    $this->setGalleryVisible = $value;
    return $this;
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getColumns($model): array {

    if ($this->setGalleryVisible) {
      return [
        Tab::make(__('default/lang.construct.gallery_tab'))
          ->visible(fn ($record) => filled($record))
          ->icon('heroicon-s-photo')
          ->schema([
            Group::make()->schema([
              Placeholder::make("")
                ->content(function ($record) use ($model) {
                  return new HtmlString(view('components.admin.media.media-manager-list', [
                    'record' => $record,
                    'modelName' => $model,
                  ])->render());
                }),
            ])->columnSpan(2),
            Group::make()->schema([
              Section::make()->schema([
                Select::make('gallery_view')
                  ->label(__('default/lang.enum.gallery_view.label'))
                  ->options(EnumsGalleryView::options())
                  ->preload()
                  ->searchable()
                  ->nullable(),
              ]),

              Section::make(__('default/lang.construct.gallery_file'))->schema([
                ...WebpUploadWithFilter::make()
                  ->setFileName('gallery')
                  ->setMultipleFiles(true)
                  ->setFilterId($this->setGalleryFilter)
                  ->setUploadDirectory($this->setGalleryUploadDirectory)
                  ->setRequiredUpload(false)
                  ->setChangeFilter(true)
                  ->setRenameFromDb('english_name')
                  ->getColumns(),
              ]),
            ])->columnSpan(1),
          ])->columns(3)
      ];
    } else {
      return [];
    }

  }

}
