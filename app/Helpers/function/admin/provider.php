<?php

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Illuminate\Support\Facades\File;


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('loadCssFiles')) {
  function loadCssFiles($folderName = null) {
    $cssPath = public_path($folderName);
    $cssFiles = collect(File::files($cssPath))
      ->filter(fn ($file) => $file->getExtension() === 'css')
      ->map(function ($file) use ($folderName) {
        $relativePath = $folderName . '/' . $file->getFilename();
        return Css::make(
          $file->getFilenameWithoutExtension(),
          asset($relativePath)
        );
      })
      ->toArray();
    return $cssFiles;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('loadAllCssFiles')) {
  /**
   * Load all CSS files from a public folder (including subfolders),
   * ignoring specific files or folders.
   *
   * @param string|null $folderName The folder under /public
   * @return array
   */
  function loadAllCssFiles(string $folderName = null): array {
    $cssPath = public_path($folderName);

    // ✅ تحديد المجلدات والملفات التي يتم تجاهلها
    $ignores = [
      'folders' => ['bootstrap', 'remove'],
      'files' => ['test.css'],
    ];

    // ✅ نحصل على كل الملفات بشكل recursive
    $cssFiles = collect(File::allFiles($cssPath))
      ->filter(function ($file) use ($ignores) {
        $fileName = $file->getFilename();
        $folderPath = str_replace(public_path(), '', $file->getPath());

        // تجاهل الملفات المحددة
        if (in_array($fileName, $ignores['files'])) {
          return false;
        }

        // تجاهل أي مجلد من المجلدات المحددة
        foreach ($ignores['folders'] as $ignoreFolder) {
          if (str_contains($folderPath, $ignoreFolder)) {
            return false;
          }
        }

        // السماح فقط بملفات CSS
        return $file->getExtension() === 'css';
      })
      ->map(function ($file) {
        $relativePath = str_replace(public_path(), '', $file->getPathname());
        $relativePath = ltrim($relativePath, '/\\');

        return Css::make(
          $file->getFilenameWithoutExtension(),
          asset($relativePath)
        );
      })
      ->values() // ترتيب العناصر من جديد بعد الفلترة
      ->toArray();

    return $cssFiles;
  }
}


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('loadJsFiles')) {
  function loadJsFiles($folderName = null) {
    $cssPath = public_path($folderName);
    $cssFiles = collect(File::files($cssPath))
      ->filter(fn ($file) => $file->getExtension() === 'js')
      ->map(function ($file) use ($folderName) {
        $relativePath = $folderName . '/' . $file->getFilename();
        return Js::make(
          $file->getFilenameWithoutExtension(),
          asset($relativePath)
        );
      })
      ->toArray();
    return $cssFiles;
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('loadAllJsFiles')) {
  function loadAllJsFiles($folderName = null): array {
    $jsPath = public_path($folderName);

    // ✅ define folders and files to ignore
    $ignores = [
      'folders' => ['node_modules', 'vendor', 'drafts'],
      'files' => ['test.js', 'ignore.js'],
    ];

    $jsFiles = collect(File::allFiles($jsPath))
      ->filter(function ($file) use ($ignores) {
        // ✅ اسم الملف فقط (مثال: app.js)
        $fileName = $file->getFilename();

        // ✅ مسار المجلد (كامل، مثال: /public/js/vendor/chart.js)
        $folderPath = str_replace(public_path(), '', $file->getPath());

        // نرجع false لو اسم الملف موجود في الملفات الممنوعة
        if (in_array($fileName, $ignores['files'])) {
          return false;
        }

        // نرجع false لو أي مجلد في المسار جزء من المجلدات الممنوعة
        foreach ($ignores['folders'] as $ignoreFolder) {
          if (str_contains($folderPath, $ignoreFolder)) {
            return false;
          }
        }

        return $file->getExtension() === 'js';
      })
      ->map(function ($file) {
        $relativePath = str_replace(public_path(), '', $file->getPathname());
        $relativePath = ltrim($relativePath, '/\\');

        return Js::make(
          $file->getFilenameWithoutExtension(),
          asset($relativePath)
        );
      })
      ->toArray();

    return $jsFiles;
  }
}

