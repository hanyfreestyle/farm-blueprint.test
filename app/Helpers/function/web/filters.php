<?php
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('printArrow')) {
  function getSelectedOption($filters, $key,$val): string {
    if (isset($filters[$key]) and $filters[$key] == $val ) {
      return 'selected';
    }
    return '';
  }
}



