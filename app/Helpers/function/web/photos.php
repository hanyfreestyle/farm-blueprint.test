<?php

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('checkPhotoAlt')) {
  function checkPhotoAlt($mainTable, $photoTable, $def = 'photo'): string {
    $mainAlt = $mainTable->name ?? null;
    $photoAlt = $photoTable->name ?? null;
    if ($photoAlt) {
      $def = $photoAlt;
    } elseif ($mainAlt) {
      $def = $mainAlt;
    }
    return $def;
  }

}


