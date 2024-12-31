<?php

namespace App\Providers;

use App\Services\GuardianApiService;
use App\Services\NewsApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind('ArticleFetcherInterface', function () {
            return [
                app(NewsApiService::class),
                app(GuardianApiService::class),
            ];
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
