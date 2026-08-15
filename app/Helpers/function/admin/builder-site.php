<?php
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getTranslatedValue')) {
  function getTranslatedValue(?array $translations): ?string {
    if (!is_array($translations)) {
      return null;
    }
    $locale = getAdminLangViewData();
    return $translations[$locale]
      ?? collect($translations)->filter()->first() // fallback لأول ترجمة غير null
      ?? null;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('updateFontawesomeIcon')) {
  function updateFontawesomeIcon(?string $icon): ?string {
    $arr = ['fas-', 'fab-'];
    $arr_replace = ['fa fa-', 'fa-brands fa-'];
    return str_replace($arr, $arr_replace, $icon);
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('updateCounterPlusMakkah')) {
//  function updateCounterPlusMakkah(?string $value): ?string {
//    $line = '';
//    // يقبل أرقاماً تحتوي على فواصل آلاف، مع أو بدون علامة +
//    if (preg_match('/^([\d,]+)(\+?)$/', $value ?? '', $m)) {
//      // لو احتجت الرقم خالياً من الفواصل (لأن بعض إضافات الـ JS تطلبه هكذا)
//      $numeric = str_replace(',', '', $m[1]);
//
//      $line .= '<h1><span class="counter" data-target="' . $numeric . '">' . $m[1] . '</span>';
//      if ($m[2] === '+') {
//        $line .= '<span class="counterUp-icon">+</span>';
//      }
//      $line .= '</h1>';
//    }
//    return $line;
//  }

  /**
   * Format a numeric counter string for UI display with support for +, %, $.
   *
   * @param string|null $value Raw input (e.g. "1,200+", "$500", "85%", etc.)
   * @return string|null HTML string with formatted counter span, or null if invalid
   */
  function updateCounterPlusMakkah(?string $value): ?string {
    $line = '';

    // Pattern: match number with optional thousand separators, optional trailing symbol
    if (preg_match('/^([$%]?)([\d,]+)([+%$]?)$/', $value ?? '', $m)) {
      [$full, $prefix, $number, $suffix] = $m;

      // Get numeric value without commas
      $numeric = str_replace(',', '', $number);

      $line .= '<h1>';

      // Prefix symbol (e.g. $)
      if ($prefix) {
        $line .= '<span class="counterUp-icon prefix">' . $prefix . '</span>';
      }

      // Main number
      $line .= '<span class="counter" data-target="' . $numeric . '">' . $number . '</span>';

      // Suffix symbol (e.g. + or %)
      if ($suffix) {
        $line .= '<span class="counterUp-icon suffix">' . $suffix . '</span>';
      }

      $line .= '</h1>';
    }

    return $line ?: null;
  }
}




