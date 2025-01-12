<?php

namespace App\Providers;

use Database\Factories\UserFactory;
use App\Services\GeolocationService;
use Illuminate\Support\Facades\Vite;
use App\Services\AddressParsingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GeolocationService::class, function () {
            return new GeolocationService();
        });
        $this->app->singleton(AddressParsingService::class, function () {
            return new AddressParsingService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
