<?php

namespace App\FilamentHelpers\Default;

use App\Enums\Setting\EnumsAvailableColors;
use App\FilamentHelpers\FilamentHelpersSetValues;
use App\FilamentHelpers\Form\Select\CategoryMultiSelect;
use App\FilamentHelpers\UploadFile\WebpUploadWithFilter;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Toggle;


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
class TabsPostPropertiesInput extends FilamentHelpersSetValues {

  public function getColumns(): array {
    $rightColumns = self::getRightColumns();
    $leftColumns = self::getLeftColumns();

//    $rightColumns = [];
//    $leftColumns = [];

    return [
      Tab::make(__('default/lang.tab.info'))
        ->icon('heroicon-s-information-circle')
        ->schema([
          Group::make()->schema($rightColumns)->columnSpan(2),
          Group::make()->schema($leftColumns)->columnSpan(1),
        ])->columns(3)->columnSpanFull(),

    ];
  }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getRightColumns(): array {
    $columnsRight = [];

    if ($this->setAddPostCategory) {
      if ($this->setPostCategoryType == 'single') {
        $columnsRight[] = Select::make($this->setPostCategorySingleInputName)
          ->label($this->setPostCategorySingleInputTitle)
          ->options(
            fn () => $this->setPostCategoryModelName::all()->mapWithKeys(fn ($group) => [
              $group->id => $group->display_name,
            ])->toArray()
          )
          ->preload()
          ->searchable()
          ->required(fn ($get) => $get('is_published') === true);

      } elseif ($this->setPostCategoryType == 'many') {
        $columnsRight[] = CategoryMultiSelect::make('categories')
          ->categoryModel($this->setPostCategoryModelName)
          ->required(fn ($get) => $get('is_published') === true);
      } elseif ($this->setPostCategoryType == 'tree') {
        $columnsRight[] = [];
      }
    }

    if ($this->setAddPostYouTube) {
      $columnsRight[] = Group::make()
        ->schema(YouTubeInput::make()->getColumns())->columns(2);
    }

    if ($this->setAvailableColors) {
      $columnsRight[] = CheckboxList::make('available_colors')
        ->label(__('default/lang.columns.available_colors'))
        ->options(EnumsAvailableColors::options())
        ->columns([
          'default' => 2,
          'md' => 3,
          'lg' => 4,
        ])
        ->bulkToggleable()
        ->nullable();
    }


    $columnsRight[] = Group::make()->schema([])->columns(2);

    return $columnsRight;
  }
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getLeftColumns(): array {
    $columnsLeft = [];


    $leftSection[] = Toggle::make('is_published')
      ->label(__('default/lang.columns.published_post'))
      ->helperText(__('default/lang.columns.published_post_helper'))
      ->default(true)
      ->visible(fn ($record) => !$record?->is_published)
      ->required();

    $leftSection[] = Toggle::make('is_active')
      ->label(__('default/lang.columns.is_active'))
      ->default(true)
      ->visible(fn ($record) => $record?->is_published)
      ->required();

    if ($this->setAddPublishedUser) {
      $leftSection[] = Select::make('user_id')
        ->relationship('user', 'name')
        ->default(auth()->id())
        ->label(__('blog/blog-post.columns.author'))
        ->preload()
        ->required()
        ->searchable();
    }

    if ($this->setAddPublishedDate) {
      $leftSection[] = DatePicker::make('published_at')
        ->label(__('default/lang.columns.published_at'))
        ->default(now()->toDateString())
//      ->minDate(now()->toDateString())
//      ->visible(fn ($get) => $get('published_at') === null)
        ->required();
    }

    $columnsLeft[] = Section::make()->schema($leftSection);


    if ($this->setAddPostPhoto) {
      $columnsLeft[] = Section::make(__('default/lang.columns.photo'))->schema([
        ...WebpUploadWithFilter::make()
          ->setFilterId($this->setPhotoPostFilterId)
          ->setUploadDirectory($this->setUploadDirectory)
          ->setRequiredUpload($this->setPhotoRequiredUpload)
          ->setChangeFilter($this->setPhotoChangeFilter)
          ->setCanChangeFilter($this->setPhotoCanChangeFilter)
          ->getColumns(),
      ]);
    }


    return $columnsLeft;
  }

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

}
