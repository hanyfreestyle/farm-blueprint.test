<?php

namespace App\FilamentHelpers\Form\Inputs;

use App\Traits\Admin\Helper\SmartSetFunctionTrait;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;


class SoftTranslatableInput {
  use SmartSetFunctionTrait;

  protected ?string $setUniqueTable = null; // ✅ جديد

  public function __construct() {
    $this->initializeSmartSetFunction();
  }

  public static function make(): static {
    return new static();
  }

  public function setUniqueTable(?string $table): static {
    $this->setUniqueTable = $table;
    return $this;
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

      $input = TextInput::make($printName)
        ->label($this->setNameLabel . " " . $printLang)
        ->extraAttributes(fn () => rtlIfArabic($lang))
        ->columnSpan($this->setColumnSpanFull)
        ->validationMessages([
          'unique' => fn ($state) => __('validation.used_value_before', ['value' => $state]),
        ]);
//        ->required($this->setDataRequired)

      if ($this->setInputDisabled){
        $input = $input->disabled(fn (?Model $record): bool => $record && !(bool)$record->can_edit)->dehydrated();
      }


      // ✅ تطبيق شرط unique فقط لو تم تمرير اسم الجدول
      if (!empty($this->setUniqueTable)) {
        $input = $input->unique(
          table: $this->setUniqueTable,
          column: "{$this->setInputName}->{$lang}",
          ignorable: fn ($record) => $record
        );
      }

      if ($this->setActiveLang) {
        if ($this->setActiveLang == $lang) {
          $input = $input->required();
        }
      } else {
        $input = $input->required($this->setDataRequired);
      }

      $input = $input->required($this->setDataRequired);

      $columns[] = $input;
    }
    return $columns;
  }
}

