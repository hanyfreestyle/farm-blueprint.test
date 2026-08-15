<?php

namespace App\Enums\Product;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;

enum EnumsProductCategorySort: string {
  use EnumHasLabelOptionsTrait;

  case name_asc = 'name_asc';
  case name_desc = 'name_desc';
  case product_count = 'product_count';
  case position = 'position';


  public function label(): string {
    return match ($this) {
      self::name_desc => __('enums/product.product_category_sort.name_desc'),
      self::name_asc => __('enums/product.product_category_sort.name_asc'),
      self::product_count => __('enums/product.product_category_sort.product_count'),
      self::position => __('enums/product.product_category_sort.position'),

    };
  }
}
