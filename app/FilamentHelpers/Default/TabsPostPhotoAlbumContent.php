<?php

namespace App\FilamentHelpers\Default;

use App\FilamentHelpers\Form\Inputs\SoftTranslatableInput;
use App\FilamentHelpers\Form\Inputs\SoftTranslatableTextAreaWithEditor;
use App\Traits\Admin\Helper\SmartSetFunctionTrait;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
class TabsPostPhotoAlbumContent {
  use SmartSetFunctionTrait;

  protected bool $setViewSelectCategory = true;

  public function __construct() {
    $this->initializeSmartSetFunction();
  }

  public static function make(): static {
    return new static();
  }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getColumns(): array {

    return [
      Tabs::make('ContentTabs')
        ->activeTab(1)
        ->tabs([
          Tab::make(__('default/lang.photo_album.tab_info'))
            ->icon('heroicon-s-information-circle')
            ->schema([

                ...SoftTranslatableInput::make()
                  ->setNameLabel(__('default/lang.photo_album.columns_name'))
                  ->setInputName('name')
                  ->setTransMode(true)
                  ->setDataRequired(false)
                  ->setTranslationsRelation($this->setTranslationsRelation)
                  ->setActiveLang($this->setActiveLang)
                  ->getColumns(),

                Group::make()->schema([]),
              ]
            )
            ->columns(getCountLang())->columnSpanFull(),

          Tab::make(__('default/lang.photo_album.tab_des_up'))
            ->icon('fas-circle-up')
            ->schema([

              ...SoftTranslatableTextAreaWithEditor::make()
                ->setDesLabel(__('default/lang.photo_album.columns_content'))
                ->setInputName('des_up')
                ->setTransMode(true)
                ->setDataRequired(false)
                ->setEditor(true)
                ->setTranslationsRelation($this->setTranslationsRelation)
                ->setEditorHeight($this->setEditorHeight)
                ->setActiveLang($this->setActiveLang)
                ->getColumns(),

            ])->columns(1)->columnSpanFull(),

          Tab::make(__('default/lang.photo_album.tab_des_down'))
            ->icon('fas-circle-down')
            ->schema([

              ...SoftTranslatableTextAreaWithEditor::make()
                ->setDesLabel(__('default/lang.photo_album.columns_content'))
                ->setInputName('des_down')
                ->setTransMode(true)
                ->setTranslationsRelation($this->setTranslationsRelation)
                ->setDataRequired(false)
                ->setEditor(true)
                ->setEditorHeight($this->setEditorHeight)
                ->setActiveLang($this->setActiveLang)
                ->getColumns(),

            ])->columns(1)->columnSpanFull(),
        ]),
    ];
  }

}
