<?php
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getConfigByKey')) {
  function getConfigByKey($data, $key): bool {
    if (isset($data->product_menu[$key]) and $data->product_menu[$key]) {
      return true;
    }
    return false;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('printEmojiIcon')) {
  function printEmojiIcon($webConfig, $emojiIcon, $key): string {
    if (isset($webConfig->product_menu[$key]) and $webConfig->product_menu[$key]) {
      return $emojiIcon->emoji_icon ?? '';
    }
    return '';
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getPriceFormat')) {
  function getPriceFormat($webConfig, $price): string {
    $price = intval($price);
    if (isset($webConfig->product_menu['show_price_format']) and $webConfig->product_menu['show_price_format']) {
      return number_format($price, 2);
    } else {
      return number_format($price, 0);
    }

  }
}






