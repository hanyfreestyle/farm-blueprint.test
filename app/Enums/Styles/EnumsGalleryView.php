<?php

namespace App\Enums\Styles;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsGalleryView: string {
  use EnumHasLabelOptionsTrait;

  case masonry = 'masonry';
  case list = 'list';

//  case grid = 'col-grid';
  public function label(): string {
    return match ($this) {
      self::masonry => __('enums/styles.gallery_view.masonry'),
      self::list => __('enums/styles.gallery_view.list'),
    };
  }
}
