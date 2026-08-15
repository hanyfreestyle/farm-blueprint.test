<?php
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('printArrowNormal')) {
  function printArrowNormal(): string {
    if (thisCurrentLocale() == 'en') {
      $icon = '<i class="fa-solid fa-angle-right"></i>';
    } else {
      $icon = '<i class="fa-solid fa-angle-left"></i>';
    }
    return $icon;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('printArrow')) {
  function printArrow(): string {
    if (thisCurrentLocale() == 'en') {
      $icon = '<i class="fas fa-arrow-right"></i>';
    } else {
      $icon = '<i class="fas fa-arrow-left" ></i>';
    }
    return $icon;
  }
}
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('printPhoneIcon')) {
  function printPhoneIcon(): string {
    if (thisCurrentLocale() == 'en') {
      $icon = '<i class="fa-solid fa-phone"></i>';
    } else {
      $icon = '<i class="fas fa-phone-alt"></i>';
    }
    return $icon;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('printPhoneIconBox')) {
  function printPhoneIconBox(): string {
    if (thisCurrentLocale() == 'en') {
      $icon = '<i class="fa-solid fa-square-phone"></i>';
    } else {
      $icon = '<i class="fa-solid fa-square-phone-flip"></i>';
    }
    return $icon;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('ctaWhatsappMass')) {
  function ctaWhatsappMass($webConfig, $unitData): string {
//        dd($webConfig->whatsapp_send);
    $isArabic = app()->getLocale() === 'ar';
    $rtlMark = $isArabic ? "\u{200F}" : '';

    $message = $rtlMark . __('webLang/whatsapp.cta_intro') . "\n";
    if ($unitData->listing_type == 'Project') {
      $message .= "*" . __('webLang/whatsapp.project') . ":* " . ($unitData->name ?? '—') . "\n";
    } elseif ($unitData->listing_type == 'Unit') {
      $message .= "*" . __('webLang/whatsapp.project') . ":* " . ($unitData->project->name ?? '—') . "\n";
      $message .= "*" . __('webLang/whatsapp.unit') . ":* " . ($unitData->name ?? '—') . "\n";
    } elseif ($unitData->listing_type == 'ForSale') {

    }

//        $message .= "*" . __('webLang/whatsapp.reference') . ":* " . ($unitData->id ?? '—');

    // ترميز النص ليكون مناسبًا لرابط واتساب
    $encodedMessage = urlencode($message);
    // رقم الواتساب (تأكّد أنه بصيغة دولية بدون "+" مثلاً: 201234567890)
    $whatsappNumber = $webConfig->whatsapp_send ?? '201110510003';
    // توليد رابط الواتساب النهائي
    $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";

    return $whatsappUrl;

  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('number_format_print')) {
  function number_format_print($num): string {
    $num = intval($num);
    return number_format($num);
  }
}


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('updateFontIcon')) {
  function updateFontIcon($getIcon): string {
    $arrFrom = [
      'fas-',
      'far-',
    ];
    $arrTo = [
      'fas fa-',
      'fas fa-',
    ];
    return str_replace($arrFrom, $arrTo, $getIcon);
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getLocationPathFromArray')) {
  function getLocationPathFromArray($locationTree): string {
    $links = collect($locationTree)->map(function ($location) {
      return '<a href="' . route('web.location.view', $location['slug']) . '">' . e($location['name']) . '</a>';
    });

    return $links->implode(', ');
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('UpdateTermsConditionsText')) {
  function UpdateTermsConditionsText($value, $webConfig): string {

    $CompanyName = '<span>' . $webConfig->name . '</span>';
    $webEmail = '<span>' . $webConfig->email . '</span>';
    $def_url = '<span>' . $webConfig->def_url . '</span>';
    $rep1 = array ("[CompanyName]", "[WebSiteName]", "[WebEmail]");
    $rep2 = array ($CompanyName, $def_url, $webEmail);
    return str_replace($rep1, $rep2, $value);

  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('printPostAuthor')) {
  function printPostAuthor($row): string {
    $url = route('web.team.author_view', $row->user->slug);
    $authorName = getLangDataJson($row->user, 'author_name');
//    dd($row);
    return ' <a href="' . $url . '">' . $authorName . '</a>';

  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
function wrapLastWords(string $title, int $wordsCount = 2): string {
  $words = preg_split('/\s+/u', trim($title));

  if (!is_array($words) || count($words) <= $wordsCount) {
    return '<span>' . e($title) . '</span>';
  }

  $lastWords = array_splice($words, -$wordsCount);

  return e(implode(' ', $words))
    . ' <span>'
    . e(implode(' ', $lastWords))
    . '</span>';
}



