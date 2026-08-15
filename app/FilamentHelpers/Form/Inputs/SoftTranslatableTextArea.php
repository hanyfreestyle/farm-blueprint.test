<?php

namespace App\FilamentHelpers\Form\Inputs;

use App\Traits\Admin\Helper\SmartSetFunctionTrait;
use Filament\Forms\Components\Textarea;


class SoftTranslatableTextArea {
  use SmartSetFunctionTrait;

  public function __construct() {
    $this->initializeSmartSetFunction();
  }

  public static function make(): static {
    return new static();
  }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getColumns(): array {
    $columns = [];
    foreach ($this->setActiveLang as $lang) {

      if ($this->setTransMode) {
        $printName = $lang . "." . $this->setInputName;
      } else {
        $printName = $this->setInputName . "." . $lang;
      }


      if ($this->setTranslationsRelation) {
        $printName = "translations." . $lang . "." . $this->setInputName;
      }

      $printLang = "(" . ucfirst($lang) . ")";
      $columns[] = Textarea::make($printName)
        ->label($this->setDesLabel . " " . $printLang)
        ->extraAttributes(fn () => rtlIfArabic($lang))
        ->rows($this->setTextAreaRow)
        ->required($this->setDataRequired);
    }
    return $columns;
  }
}

