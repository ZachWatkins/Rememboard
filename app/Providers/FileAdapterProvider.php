<?php

namespace App\Providers;

use App\Services\File\IcsFileAdapter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class FileAdapterProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(IcsFileAdapter::class, function (Application $app) {
            return new IcsFileAdapter();
        });
    }
}
