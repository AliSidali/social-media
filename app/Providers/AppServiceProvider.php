<?php

namespace App\Providers;

use App\Http\Enums\LanguageEnum;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        Inertia::share([
            'lang' => fn() => app()->getLocale(),
            'languages' => fn() => LanguageEnum::cases(),
            'translations' => function () {
                $locale_path = app()->getLocale() ? resource_path("../lang/" . app()->getLocale()) : resource_path("../lang/fr");
                $files = File::files($locale_path);
                return require $files[1]->getRealPath();
            }

        ]);

    }
}
