<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GeolocationService;

class GeolocationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
