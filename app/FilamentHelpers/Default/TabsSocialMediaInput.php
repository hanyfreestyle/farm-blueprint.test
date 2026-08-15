<?php

namespace App\FilamentHelpers\Default;

use App\FilamentHelpers\Form\Repeater\SocialPlatformRepeater;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs\Tab;


class TabsSocialMediaInput {

  protected string $setInputName = 'social';

  public static function make(): static {
    return new static();
  }

  public function setInputName(string $value): static {
    $this->setInputName = $value;
    return $this;
  }


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
  public function getColumns(): array {
    $columns = [];

    $columns[] = Tab::make(__('default/lang.tab.social'))
      ->icon('fas-share-nodes')
      ->schema([
        Group::make()->schema([
          ...SocialPlatformRepeater::make()->getColumns($this->setInputName),
        ])->columns(2)->columnSpan(2),
      ])->columns(1)->columnSpanFull();

    return $columns;
  }

}
