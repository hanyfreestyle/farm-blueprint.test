<?php

namespace App\FilamentHelpers\Form\Select;

use App\Models\Builder\BuilderPage;
use Filament\Forms\Components\Select;

class SelectBuilderPage extends Select {


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

  protected function setUp(): void {
    $locale = getAdminLangViewData();
    parent::setUp();
    $this
      ->label(__('default/lang.columns.builder_page_id'))
      ->searchable()
      ->preload()
      ->options(fn () => BuilderPage::query()->get()->pluck("name." . $locale, 'id'))
      ->nullable();

  }


}
