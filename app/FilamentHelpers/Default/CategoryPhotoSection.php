<?php

namespace App\FilamentHelpers\Default;

use App\FilamentHelpers\FilamentHelpersSetValues;
use App\FilamentHelpers\UploadFile\WebpUploadFixedSize;
use App\FilamentHelpers\UploadFile\WebpUploadWithFilter;
use Filament\Forms;
use Guava\FilamentIconPicker\Forms\IconPicker;
use TangoDevIt\FilamentEmojiPicker\EmojiPickerAction;

/**
 * Document magic fluent setters for IDE:
 *
 * @method $this setAddCategoryPhoto(bool $value)
 * @method $this setAddCategoryFontIcon(bool $value)
 * @method $this setAddCategoryFontIconReq(bool $value)
 * @method $this setAddCategoryIconPhoto(bool $value)
 * @method $this setAddCategoryEmojiIcon(bool $value)
 * @method $this setCategoryFilterId(int $value)
 *
 */
class CategoryPhotoSection extends FilamentHelpersSetValues {

  public bool $setAddCategoryPhoto = true;
  public bool $setAddCategoryFontIcon = false;
  public bool $setAddCategoryFontIconReq = false;
  public bool $setAddCategoryIconPhoto = false;
  public bool $setAddCategoryEmojiIcon = false;
  public int $setCategoryFilterId = 0;


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getColumns(): array {
    $columns = [];

    $columns[] = Forms\Components\Section::make()->schema([
      Forms\Components\Toggle::make('is_published')
        ->label(__('default/lang.columns.published_post'))
        ->helperText(__('default/lang.columns.published_post_helper'))
        ->default(true)
        ->visible(fn ($record) => !$record?->is_published)
        ->required(),

      Forms\Components\Toggle::make('is_active')
        ->label(__('default/lang.columns.is_active'))
        ->default(true)
        ->visible(fn ($record) => $record?->is_published)
        ->required(),
    ]);

    if ($this->setAddCategoryEmojiIcon) {
      $columns[] = Forms\Components\Section::make('Emoji Icon')->schema([
        Forms\Components\TextInput::make('emoji_icon')
          ->hiddenLabel()
          ->extraAttributes(fn () => rtlIfArabic('en'))
          ->suffixAction(EmojiPickerAction::make('emoji-title'))
          ->nullable(),
      ]);
    }

    if ($this->setAddCategoryFontIcon) {
      $columns[] = IconPicker::make('font_icon')
        ->label(__('default/lang.columns.icon'))
        ->hiddenLabel()
        ->searchLabels()
        ->columnSpanFull()
        ->columns([
          'default' => 1,
          'lg' => 5,
          '2xl' => 5,
        ])
        ->sets(['fas', 'fab', "fontawesome-solid", "fontawesome-brands"])
        ->required($this->setAddCategoryFontIconReq);
    }

    if ($this->setAddCategoryPhoto) {
      $columns[] = Forms\Components\Section::make(__('default/lang.columns.photo'))->schema([
        ...WebpUploadWithFilter::make()
          ->setRenameTo($this->setReNameTo)
          ->setFilterId($this->setCategoryFilterId)
          ->setUploadDirectory($this->setUploadDirectory)
          ->setRequiredUpload($this->setPhotoRequiredUpload)
          ->setChangeFilter($this->setPhotoChangeFilter)
          ->setCanChangeFilter($this->setPhotoCanChangeFilter)
          ->getColumns(),
      ]);
    }

    if ($this->setAddCategoryIconPhoto) {
      $columns[] = Forms\Components\Section::make(__('default/lang.columns.icon'))->schema([
        ...WebpUploadFixedSize::make()
          ->setFileName('icon')
          ->setThumbnail(false)
          ->setUploadDirectory($this->setUploadDirectory)
          ->setFilter(4)
          ->setResize(100, 100, 90)
          ->setRequiredUpload(false)
          ->getColumns(),
      ]);
    }

    return $columns;
  }

}
