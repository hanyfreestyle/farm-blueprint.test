<?php

use App\Http\Controllers\MasterFunctionController;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('defPublicUrl')) {
  function defPublicUrl($type, $fileName, $secure = null): string {
    $FolderName = config('appConfig.client_name');
    $clientFolderName = $FolderName . '/';
    if ($type == 'FavIcon') {
      return app('url')->asset('fav/' . $clientFolderName . $fileName, $secure);
    } elseif ($type == 'Fonts') {
      return app('url')->asset('assets/fonts/' . $fileName, $secure);
    } elseif ($type == 'web-def') {
      return app('url')->asset('assets/web-def/' . $fileName, $secure);
    } elseif ($type == 'web-quiz') {
      return app('url')->asset('assets/web-quiz/' . $fileName, $secure);
    } elseif ($type == 'portal-card') {
      return app('url')->asset('assets/portal-card/' . $fileName, $secure);
    } else {
      return app('url')->asset($fileName, $secure);
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('existsDefPhoto')) {
  function existsDefPhoto($defPhotoList, $key): string|null {
    if (!isset($defPhotoList[$key])) {
      return null;
    }
    if (!isset($defPhotoList[$key]->photo)) {
      return null;
    }
    if (Storage::disk('root_folder')->exists($defPhotoList[$key]->photo)) {
      return Storage::disk('root_folder')->url($defPhotoList[$key]->photo);
    }

    return null;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getPhotoPath')) {
  function getPhotoPath($file, $defPhoto = "logo", $defPhotoRow = "photo"): string|null {

    if (!empty($file) && Storage::disk('root_folder')->exists($file)) {
      return Storage::disk('root_folder')->url($file);
    }
    return existsDefImagesDir($defPhoto, $defPhotoRow);
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('defImagesDir')) {
  function defImagesDir($defPhoto, $defPhotoRow = "photo_thumbnail"): string {
    $defPhotoList = MasterFunctionController::getDefPhotoById($defPhoto);
    $path = $defPhotoList->{$defPhotoRow} ?? '';
    return Storage::disk('root_folder')->url($path);
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('existsDefImagesDir')) {
  function existsDefImagesDir($defPhoto, $defPhotoRow = "photo_thumbnail"): string|null {
    $defPhotoList = MasterFunctionController::getDefPhotoById($defPhoto);
    $path = $defPhotoList->{$defPhotoRow} ?? null;
    if ($path && Storage::disk('root_folder')->exists($path)) {
      return Storage::disk('root_folder')->url($path);
    }
    return null;
  }
}


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getCopyRight')) {
  function getCopyRight($StartDate, $CompanyName): string {

    if (!$StartDate or $StartDate > date("Y")) {
      $StartDate = date("Y");
    }
    $Lang = LaravelLocalization::getCurrentLocale();
    switch ($Lang) {
      case 'ar':
        $copyname = "جميع الحقوق محفوظة";
        if ($StartDate == date("Y")) {
          $CopyRight = $copyname . " &copy; " . date("Y") . ' <span class="CompanyName">' . $CompanyName . '</span>';
        } else {
          $CopyRight = $copyname . " &copy; " . $StartDate . " - " . date("Y") . ' <span class="CompanyName">' . $CompanyName . '</span>';
        }
        break;
      default:
        $copyname = "All Rights Reserved";
        if ($StartDate == date("Y")) {
          $CopyRight = $copyname . " &copy; " . date("Y") . ' <span class="CompanyName">' . $CompanyName . '</span>';
        } else {
          $CopyRight = $copyname . " &copy; " . $StartDate . " - " . date("Y") . ' <span class="CompanyName">' . $CompanyName . '</span>';


        }
    }
    return $CopyRight;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('IsArr')) {
  function IsArr($Arr, $Name, $DefVall = "") {
    if (isset($Arr[$Name])) {
      $SendVal = $Arr[$Name];
    } else {
      $SendVal = $DefVall;
    }
    return $SendVal;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('selActive')) {
  function selActive($pageView, $thisMenu): string {
    if (isset($pageView['selMenu']) and $thisMenu == $pageView['selMenu']) {
      return 'active';
    } else {
      return '';
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('setSchemaIsActive')) {
  function setSchemaIsActive(): bool {
    if (config('appConfig.schema_is_active')) {
      return true;
    } else {
      return false;
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('setSchemaTestView')) {
  function setSchemaTestView(): bool {
    if (config('appConfig.schema_test_view')) {
      return true;
    } else {
      return false;
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('sendInputError')) {
  function sendInputError($value, $name, $label): string {
    $newName = trim(str_replace('_', " ", $name));
    return str_replace($newName, $label, $value);
  }
}