<?php

use App\Services\Aleefak\Helpers\ShortcodeReplacer;
use Illuminate\Support\Str;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('seoDesClean')) {
  function seoDesClean($getDes, $limit = 155): string {
    if (empty($getDes)) {
      return '';
    }

    if (File::isFile(base_path('app/Services/Aleefak/Helpers/ShortcodeReplacer.php'))) {
      $shortCodeAll = ShortcodeReplacer::getAllShortCodes();
      $getDes = str_replace(array_keys($shortCodeAll), '', $getDes);
    }

    $str = strip_tags($getDes);
    $str = html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // ← أضفها هنا
    $str = str_replace('&nbsp;', ' ', $str);
    $str = preg_replace('/[\r\n]+/', ' ', $str);
    $str = preg_replace('/\s+/', ' ', $str);
    $str = trim($str);

//     dd(mb_strlen($str, 'UTF-8'));
    if (mb_strlen($str, 'UTF-8') <= $limit) {
      return $str;
    }

    $str = substr($str, 0, $limit);
    $last_space = strrpos($str, ' ');

    return $last_space !== false ? substr($str, 0, $last_space) : $str;
  }
}






