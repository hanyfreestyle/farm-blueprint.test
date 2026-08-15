<?php

namespace App\Enums\Styles;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsContainerFluid: string {
  use EnumHasLabelOptionsTrait;

  case container = 'container';
  case containerFluid = 'container-fluid';



  public function label(): string {
    return __('enums/styles.container_fluid.' . $this->value);
  }


}
