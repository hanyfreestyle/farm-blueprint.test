<?php

namespace App\FilamentHelpers\Default;

use App\FilamentHelpers\Form\Inputs\SoftTranslatableInput;
use App\Traits\Admin\Helper\SmartSetFunctionTrait;
use Filament\Forms\Components\TextInput;

class YouTubeInput {
  use SmartSetFunctionTrait;


  public static function make(): static {
    return new static();
  }


  public function getColumns(): array {
    return [

      TextInput::make('youtube_code')
        ->label(__('default/lang.columns.youtube_code'))
        ->extraAttributes(fn () => rtlIfArabic('en'))
        ->columnSpanFull(),

      ...SoftTranslatableInput::make()
        ->setTransMode(true)
        ->setInputName('youtube_title')
        ->setNameLabel(__('default/lang.columns.youtube_title'))
        ->setDataRequired(false)
        ->setColumnSpanFull($this->setColumnSpanFull)
        ->getColumns(),
    ];
  }

}
