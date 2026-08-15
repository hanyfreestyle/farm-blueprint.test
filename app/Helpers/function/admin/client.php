<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Valuestore\Valuestore;

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('client_config')) {
  function client_config(string $fileName, $clientFolder = true) {
    $folderName = null;
    $default = [];
    if ($clientFolder) {
      $folderName = config('appConfig.client_name') . "/";
    }
    $basePath = 'app/ConfigApp/' . $folderName;
    $file = base_path($basePath . $fileName . '.php');

    // تحقق إذا الملف موجود
    if (file_exists($file)) {
      return require $file;
    } else {
      $basePath = 'app/ConfigApp/Default/';
      $file = base_path($basePath . $fileName . '.php');
      if (file_exists($file)) {
        return require $file;
      }
    }

    return $default;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getbrandLogo')) {
  function getbrandLogo(): string {
    $defLogo = asset('assets/client/_def/logo.png');
    $folderName = config('appConfig.client_name');

    if ($folderName) {
      $filePath = public_path("assets/client/{$folderName}/logo.png");
      if (File::isFile($filePath)) {
        return asset("assets/client/{$folderName}/logo.png");
      }
    }
    return $defLogo;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getFavIcon')) {
  function getFavIcon(): string {
    $defFav = asset('assets/client/_def/fav.png');
    $folderName = config('appConfig.client_name');
    if ($folderName) {
      $filePath = public_path("assets/client/{$folderName}/logo.png");
      if (File::isFile($filePath)) {
        return asset("assets/client/{$folderName}/fav.png");
      }
    }
    return $defFav;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getBackgroundsDirectory')) {
  function getBackgroundsDirectory(): string {
    $default = 'assets/client/_def/backgrounds';
    $folderName = config('appConfig.client_name');
    if ($folderName) {
      $relativePath = "assets/client/{$folderName}/backgrounds";
      $absolutePath = public_path($relativePath);
      if (File::isDirectory($absolutePath)) {
        return $relativePath;
      }
    }
    return $default;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getConfigJsonFile')) {
  function getConfigJsonFile($fileName) {
    $clientFolder = config('appConfig.client_name');
    $pathToFile = base_path("app/ConfigApp/$clientFolder/$fileName.json");
    if (!file_exists(dirname($pathToFile))) {
      mkdir(dirname($pathToFile), 0755, true);
    }
    $valuestore = Valuestore::make($pathToFile);
    return $valuestore;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getModuleConfigKey')) {
  function getModuleConfigKey($key, $default) {
    $valueStore = getConfigJsonFile("module");
    $state = $valueStore->get('modules', []); // هنا بنجيب كل الحقول العادية
    return data_get($state, $key, $default);
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('loadArrayFromPhpFile')) {
  function loadArrayFromPhpFile($path): array {
    if (file_exists($path)) {
      return require $path;
    }
    return [];
  }
}
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getAdminNavigation')) {
  function getAdminNavigation(): string {
    // Get CLIENT_NAME from .env
    $clientName = env('CLIENT_NAME', 'default'); // fallback
    $clientNamespace = Str::studly(str_replace('-', '_', $clientName));
//dd($clientName);
    // Build the full class name
    $navigationClass = "App\\ConfigApp\\{$clientName}\\Navigation\\AdminNavigation";

    $path = base_path('app/ConfigApp/' . $clientName . '/Navigation/AdminNavigation.php');

    if (!file_exists($path)) {
      // استخدم المسار الافتراضي
      $navigationClass = "App\\ConfigApp\\Default\\Navigation\\AdminNavigation";
    }

    return $navigationClass;
  }
}


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
function getClientSiteMap(): array {
  // نظّف قيمة العميل من أي مسافات/سلاشات
  $client = trim((string)config('appConfig.client_name'), " \t\n\r\0\x0B/\\");
  if ($client === '') {
    logger()->warning('client_name is empty');
    return [];
  }

  // جرّب أكثر من مسار محتمل بحسب مكان المجلد عندك
  $candidates = [
    app_path("ConfigApp/{$client}/sitemap/page-list.php"),          // لو المجلد داخل app/
    base_path("app/ConfigApp/{$client}/sitemap/page-list.php"),     // نفس مسارك الحالي
    base_path("ConfigApp/{$client}/sitemap/page-list.php"),         // لو ConfigApp جنب app/
  ];

  foreach ($candidates as $file) {
    // تحقّق أقوى من file_exists: لازم يكون ملف وقابل للقراءة
    if (is_file($file) && is_readable($file)) {
      /** @var array $data */
      $data = require $file; // يفترض الملف بيرجع مصفوفة
      return is_array($data) ? $data : [];
    }
  }

  // اطبع في اللوج المسارات التي جُرّبت للمساعدة في التشخيص
  logger()->warning('Sitemap file not found', ['client' => $client, 'tried' => $candidates]);

  return [];
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
function getClientRemoveFolder() {
  $client = trim((string)config('appConfig.client_name'), " \t\n\r\0\x0B/\\");
  $path = base_path('app/ConfigApp/' . $client . '/cleanFolder/folder_list.php');
  if (file_exists($path)) {
    $dataList = loadArrayFromPhpFile($path);
    if (is_array($dataList) and $dataList['domain'] == env("APP_URL")) {
      foreach ($dataList['list_folder'] as $folder) {
        if (File::exists($folder)) {
          File::deleteDirectory($folder);
        }
      }
    }
  } else {
    return null;
  }

}