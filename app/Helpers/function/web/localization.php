<?php

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('thisCurrentLocale')) {
  function thisCurrentLocale(): string {
    return LaravelLocalization::getCurrentLocale();
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('htmlDir')) {
  function htmlDir(): string {
    $sendStyle = ' dir="' . LaravelLocalization::getCurrentLocaleDirection() . '" ';
    return $sendStyle;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('htmlDirDirection')) {
  function htmlDirDirection($thisCurrentLocale): string {
    if ($thisCurrentLocale == 'ar') {
      $sendStyle = 'dir="rtl"';
    } else {
      $sendStyle = 'dir="ltr"';
    }
    return $sendStyle;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('metaLanguage')) {
  function metaLanguage($thisCurrentLocale): string {
    if ($thisCurrentLocale == 'ar') {
      $sendStyle = 'Arabic';
    } else {
      $sendStyle = 'English';
    }
    return $sendStyle;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getContentLanguage')) {
  function getContentLanguage($thisCurrentLocale): string {
    if ($thisCurrentLocale == 'ar') {
      $sendStyle = 'ar-EG';
    } else {
      $sendStyle = 'en-US';
    }
    return $sendStyle;
  }
}


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('webChangeLocale')) {
  function webChangeLocale(): string {
    $Current = LaravelLocalization::getCurrentLocale();
    if ($Current == 'ar') {
      $change = 'en';
    } else {
      $change = 'ar';
    }
    return $change;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('alternateLang')) {
  function alternateLang($currentLang): string {
    if ($currentLang == 'ar') {
      $change = 'en';
    } else {
      $change = 'ar';
    }
    return $change;
  }
}
if (!function_exists('alternateLangRange')) {
  function alternateLangRange($currentLang): string {
    if ($currentLang == 'ar') {
      $change = 'en-US';
    } else {
      $change = 'ar-EG';
    }
    return $change;
  }
}

if (!function_exists('langRange')) {
  function langRange($currentLang): string {
    if ($currentLang == 'ar') {
      $change = 'ar-EG';
    } else {
      $change = 'en-US';
    }
    return $change;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('webChangeLocaleText')) {
  function webChangeLocaleText(): string {
    $Current = LaravelLocalization::getCurrentLocale();
    if ($Current == 'ar') {
      $change = 'English';
    } else {
      $change = 'عربى';
    }
    return $change;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('setLocalizationRoute')) {
  function setLocalizationRoute($route): string {
    return LaravelLocalization::localizeUrl($route);
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('setLocalizationRouteLang')) {
  function setLocalizationRouteLang($route, $lang): string {
    return LaravelLocalization::localizeUrl($route, $lang);
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('setTransRoute')) {
//  function setTransRoute($routeName): string {
//    return LaravelLocalization::transRoute($routeName);
//  }
  function setTransRoute(string $routeKey): string {
    return trans("routes.{$routeKey}");
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('setTransRouteChange')) {
  function setTransRouteChange(string $routeKey, $lang): string {
    return trans("routes.{$routeKey}", [], $lang);
  }
}


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('createPagesLink')) {
  function createPagesLink($lang, $pageData): string {
    $link = '';

    if ($pageData->compound_id != null) {
      $parameters = optional($pageData->project)->slug . $pageData->hash;
    } else {
      $parameters = optional($pageData->location)->slug . $pageData->hash;
    }

    $link .= LaravelLocalization::getLocalizedURL($lang, route('web.listing.page.index', $parameters));
    return $link;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('textDir')) {
  function textDir(): string {
    if (thisCurrentLocale() == 'en') {
      $icon = 'text-end';
    } else {
      $icon = 'text-start';
    }
    return $icon;
  }
}
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('localizationDirSame')) {
  function localizationDirSame(): string {
    if (thisCurrentLocale() == 'en') {
      return 'left';
    } else {
      return 'right';
    }
  }
}
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('localizationDirDifferent')) {
  function localizationDirDifferent(): string {
    if (thisCurrentLocale() == 'en') {
      return 'right';
    } else {
      return 'left';
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('justifyContent')) {
  function justifyContent(): string {
    if (thisCurrentLocale() == 'en') {
      return 'justify-content-start';
    } else {
      return 'justify-content-end';
    }
  }
}










