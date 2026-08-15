<?php

namespace App\Enums\Setting;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EnumsSocialPlatform: string implements HasLabel, HasIcon {
  case Facebook = 'facebook';
  case Twitter = 'twitter';
  case Instagram = 'instagram';
  case YouTube = 'youtube';
  case LinkedIn = 'linkedin';
  case TikTok = 'tiktok';
  case Snapchat = 'snapchat';
  case Pinterest = 'pinterest';
  case Threads = 'threads';
  case WebSite = 'website';
  case Behance = 'behance';

  public function getLabel(): string {
    return __('enums/general.social_platforms.' . $this->value);
  }

  public function getIcon(): string {
    return match ($this) {
      self::Facebook => 'fa-brands fa-facebook-f',
      self::Twitter => 'fa-brands fa-twitter',
      self::Instagram => 'fa-brands  fa-instagram',
      self::YouTube => 'fa-brands fa-youtube',
      self::LinkedIn => 'fa-brands fa-linkedin',
      self::TikTok => 'fa-brands fa-tiktok',
      self::Snapchat => 'fa-brands fa-snapchat',
      self::Pinterest => 'fa-brands fa-pinterest',
      self::Threads => 'fa-solid fa-hashtag',
      self::WebSite => 'fa-solid fa-globe',
      self::Behance => 'fa-brands fa-behance',
    };
  }
}
