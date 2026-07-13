<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    // Force HTTPS URLs in production, since Railway's proxy terminates
    // SSL before the request reaches this container - without this,
    // Laravel generates http:// URLs even though the site is served over https
    if ($this->app->environment('production')) {
        \URL::forceScheme('https');
    }
}
}
