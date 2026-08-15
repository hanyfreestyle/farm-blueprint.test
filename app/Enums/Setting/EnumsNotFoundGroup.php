<?php

namespace App\Enums\Setting;

use App\Traits\Admin\Helper\EnumHasLabelOptionsTrait;
use Illuminate\Support\Facades\File;

enum EnumsNotFoundGroup: string {
  use EnumHasLabelOptionsTrait;

  case STATIC = 'static';
  case BLOG = 'blog';
  case BLOGCategory = 'blog-category';
  case Service = 'service';
  case ServiceCategory = 'service-category';
  case FAQ = 'faq';
  case OurClients = 'our-clients';
  case Listing = 'listing';
  case ListingPages = 'listing-pages';
  case DEVELOPERS = 'developers';


  public function label(): string {
    return match ($this) {
      self::BLOG => __('enums/general.not_found_group.blog'),
      self::BLOGCategory => __('enums/general.not_found_group.blog_category'),
      self::Service => __('enums/general.not_found_group.service'),
      self::ServiceCategory => __('enums/general.not_found_group.service_category'),
      self::FAQ => __('enums/general.not_found_group.faq'),
      self::OurClients => __('enums/general.not_found_group.our_clients'),
      self::Listing => __('enums/general.not_found_group.listing'),
      self::ListingPages => __('enums/general.not_found_group.listing_pages'),
      self::DEVELOPERS => __('enums/general.not_found_group.developers'),
      self::STATIC => __('enums/general.not_found_group.static'),
    };
  }

  public static function allowed(): array {
    $clientFolder = config('appConfig.client_name');
    $allowed = loadArrayFromPhpFile(base_path("app/ConfigApp/{$clientFolder}/not_found_groups/arr.php"));

//    dd($activeModules);

//    $activeModules = loadArrayFromPhpFile(base_path('app/ConfigApp/' . $folderName . '/activeModules/modules.php'));

    return array_values(array_filter(self::cases(), fn ($case) => in_array($case->value, $allowed, true)));
  }

  public static function allowedOptions(): array {
    return collect(static::allowed())
      ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
      ->all();
  }

}
