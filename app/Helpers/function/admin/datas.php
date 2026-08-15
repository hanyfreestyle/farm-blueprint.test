<?php

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\Exceptions\InvalidFormatException;


#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('printCountdownDate')) {
  function printCountdownDate($getDate, $format = "Y/m/d"): string|null {
    if (blank($getDate)) {
      return null;
    }

    try {
      $date = Carbon::createFromFormat('Y-m-d', $getDate);

      if (!$date || $date->format('Y-m-d') !== $getDate) {
        return null;
      }

      if ($date->startOfDay()->lt(today())) {
        return null;
      }

      return $date->format($format);
    } catch (InvalidFormatException) {
      return null;
    }
  }
}
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('printFormattedDate')) {
  function printFormattedDate($getDate, $setLang = true, $format = "jS M Y"): string {
    if ($setLang) {
      return Carbon::parse($getDate)->locale(app()->getLocale())->translatedFormat($format);
    } else {
      return Carbon::parse($getDate)->format($format);
    }
  }
}
#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getDateOfDay')) {
  function getDateOfDay($getDate, $setLang = true, $format = "d"): string {
    if ($setLang) {
      return Carbon::parse($getDate)->locale(app()->getLocale())->translatedFormat($format);
    } else {
      return Carbon::parse($getDate)->format($format);
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getDateOfMonth')) {
  function getDateOfMonth($getDate, $setLang = true, $format = "M"): string {
    if ($setLang) {
      return Carbon::parse($getDate)->locale(app()->getLocale())->translatedFormat($format);
    } else {
      return Carbon::parse($getDate)->format($format);
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('getUpdatedDiff')) {
  function getUpdatedDiff($getDate): string {
    $updatedAt = \Illuminate\Support\Carbon::parse($getDate);
    $now = now();

    if ($updatedAt->isToday()) {
      return __('default/lang.columns.updated_diff_today');
    }
    $diffInDays = $updatedAt->diffInDays($now);
    if ($diffInDays > 365) {
      return $updatedAt->diffForHumans(now(), [
        'parts' => 2, // يعرض مثلاً: سنة و9 أشهر
        'join' => true,
        'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
      ]);

    } else {
      return $updatedAt->diffForHumans(now(), [
        'parts' => 1, // يعرض مثلاً: سنة و9 أشهر
        'join' => true,
        'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
      ]);
    }
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
if (!function_exists('human_duration_days')) {
  /**
   * Convert days difference to human readable string using translations.
   *
   * @param int $days
   * @param string $underMonthMode 'weeks_and_days'|'days_only'
   * @param int $monthDays Default 30
   */
  function human_duration_days(int $days, string $underMonthMode = 'weeks_and_days', int $monthDays = 30): string {

    $days = max(0, $days);

    $months = intdiv($days, $monthDays);
    $daysR = $days % $monthDays;

    // Months output
    if ($months > 0) {
      if ($daysR > 0) {
        return __('default/dates.duration.month_and_day', [
          'months' => $months,
          'days' => $daysR,
        ]);
      }

      return __('default/dates.duration.month_only', [
        'months' => $months,
      ]);
    }

    // Under one month
    if ($underMonthMode === 'days_only') {
      return __('default/dates.duration.day_only', ['days' => $daysR]);
    }

    // weeks_and_days
    $weeks = intdiv($daysR, 7);
    $d = $daysR % 7;

    if ($weeks > 0 && $d > 0) {
      return __('default/dates.duration.week_and_day', [
        'weeks' => $weeks,
        'days' => $d,
      ]);
    }

    if ($weeks > 0) {
      return __('default/dates.duration.week_only', [
        'weeks' => $weeks,
      ]);
    }

    return __('default/dates.duration.day_only', ['days' => $d]);
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

if (!function_exists('human_diff_from_date')) {
  /**
   * Human diff from a given date to now (in days -> months/weeks/days).
   *
   * @param string|\DateTimeInterface|null $date
   * @param string $underMonthMode 'weeks_and_days'|'days_only'
   */
  function human_diff_from_date($date, string $underMonthMode = 'weeks_and_days'): ?string {
    if (!$date) return null;

    $days = Carbon::parse($date)->startOfDay()
      ->diffInDays(now()->startOfDay());

    return human_duration_days($days, $underMonthMode);
  }
}

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||

#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
#||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||







