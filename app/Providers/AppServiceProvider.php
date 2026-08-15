<?php

namespace App\Providers;

use App\Models\User;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\Traits\LoadsTranslatedCachedRoutes;

class AppServiceProvider extends ServiceProvider
{
    use LoadsTranslatedCachedRoutes;

    public function register(): void
    {
        foreach (glob(app_path('Helpers/function/admin/*.php')) ?: [] as $helperFile) {
            require_once $helperFile;
        }

        foreach (glob(app_path('Helpers/function/web/*.php')) ?: [] as $helperFile) {
            require_once $helperFile;
        }
    }

    public function boot(): void
    {
        RouteServiceProvider::loadCachedRoutesUsing(fn () => $this->loadCachedRoutes());

        Gate::before(function ($user, string $ability): ?bool {
            if ($user instanceof User && $user->isPrimaryUser()) {
                return true;
            }

            return null;
        });

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch): void {
            $switch
                ->locales(getProjectActiveLocales())
                ->displayLocale('ar')
                ->labels([
                    'ar' => 'العربية',
                    'en' => 'English',
                ]);
        });
    }
}
