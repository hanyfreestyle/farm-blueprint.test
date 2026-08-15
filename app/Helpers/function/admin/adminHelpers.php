<?php

use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getProjectActiveLocales')) {
  function getProjectActiveLocales(): array {
    $locales = config('core.locales.admin_content', []);

    if ($locales !== []) {
      return array_values($locales);
    }

    $supportedLocales = array_keys(config('laravellocalization.supportedLocales', []));

    return $supportedLocales !== [] ? $supportedLocales : ['ar', 'en'];
  }
}

if (!function_exists('getCountLang')) {
  function getCountLang(): string {
    return count(getProjectActiveLocales()) === 1 ? '1' : '2';
  }
}

if (!function_exists('getLocalizedStateLabel')) {
  function getLocalizedStateLabel(array $state, string $key = 'title', ?string $fallback = null): string {
    $locale = getAdminLangViewData();
    $value = data_get($state, "{$key}.{$locale}");

    return filled($value)
      ? $value
      : ($fallback ?? __('builder/_default.item'));
  }
}

if (!function_exists('getAdminLangViewData')) {
  function getAdminLangViewData(): string {
    $locales = getProjectActiveLocales();

    return count($locales) === 1 ? $locales[0] : app()->getLocale();
  }
}

if (!function_exists('isLocalSuperAdmin')) {
  function isLocalSuperAdmin(): bool {
    $user = auth()->user();

    return config('app.env') === 'local' && $user->hasRole('super_admin');
  }
}

if (!function_exists('getImageDirForPdf')) {
  function getImageDirForPdf($row): string {
    if (config('app.env') === 'local' && $row) {
      return public_path('images/' . $row);
    }

    return Storage::disk('root_folder')->url($row);
  }
}

if (!function_exists('cashDay')) {
  function cashDay($days = 2) {
    return $days * 86400;
  }
}

if (!function_exists('calcRatio')) {
  function calcRatio($width, $height): string {
    $gcd = gcd($width, $height);
    $widthRatio = $width / $gcd;
    $heightRatio = $height / $gcd;

    return $widthRatio . ':' . $heightRatio;
  }
}

if (!function_exists('gcd')) {
  function gcd(int $a, int $b) {
    return $b === 0 ? $a : gcd($b, $a % $b);
  }
}

if (!function_exists('getNewIdByOldId')) {
  function getNewIdByOldId($map, $oldId): ?int {
    return $map[$oldId] ?? null;
  }
}

if (!function_exists('renameToSlug')) {
  function renameToSlug(string $field = 'slug'): Closure {
    return fn (Get $get) => $get($field);
  }
}

if (!function_exists('updateEnvValue')) {
  function updateEnvValue($key, $value) {
    $path = base_path('.env');

    if (!file_exists($path)) {
      throw new Exception(".env file not found at path: $path");
    }

    $env = file_get_contents($path);
    $keyExists = preg_match("/^{$key}=.*/m", $env);

    if ($keyExists) {
      $env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
    } else {
      $env .= "\n{$key}={$value}";
    }

    file_put_contents($path, $env);
  }
}

if (!function_exists('updateLocale')) {
  function updateLocale($newLocale) {
    $path = config_path('app.php');

    if (!file_exists($path)) {
      return response()->json(['error' => 'Config file not found.'], 404);
    }

    $content = file_get_contents($path);

    $content = preg_replace(
      "/('locale'\s*=>\s*)'(.*?)'/",
      "'locale' => '{$newLocale}'",
      $content
    );

    file_put_contents($path, $content);

    return response()->json(['success' => true, 'locale' => $newLocale]);
  }
}
