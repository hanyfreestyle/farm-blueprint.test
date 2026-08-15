<?php

namespace App\FilamentHelpers\Form\Inputs;

use App\FilamentHelpers\Form\Editors\CKEditor4;
use App\Traits\Admin\Helper\SmartSetFunctionTrait;
use Filament\Forms\Components\Textarea;

class SoftTranslatableTextAreaWithEditor {
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

      if ($this->setTranslationsRelation) {
        $printName = "translations." . $lang . "." . $this->setInputName;
      } else {
        if ($this->setTransMode) {
          $printName = $lang . "." . $this->setInputName;
        } else {
          $printName = $this->setInputName . "." . $lang;
        }
      }

      $printLang = "(" . ucfirst($lang) . ")";

      if ($this->setEditor) {
        $columns[] = CKEditor4::make($printName)
          ->label($this->setDesLabel . " " . $printLang)
          ->setEditorHeight($this->setEditorHeight)
          ->required($this->setDataRequired)
          ->reactive()
          ->extraAttributes([
            'locale' => $lang,
          ]);

      } else {
        $columns[] = Textarea::make($printName)
          ->label($this->setDesLabel . " " . $printLang)
          ->extraAttributes(fn () => rtlIfArabic($lang))
          ->rows($this->setTextAreaRow)
          ->required($this->setDataRequired);
      }
    }

    return $columns;
  }
}

