<?php

namespace Nylo\LaravelNyloAuth;

use Illuminate\Support\Facades\RateLimiter;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelNyloAuthServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-nylo-auth')
            ->hasConfigFile('laravel-nylo-auth')
            ->hasRoute('web');

        $this->publishes([
            __DIR__.'/Http/Controllers/stubs/ApiController.php' => app_path('Http/Controllers/ApiController.php'),
            __DIR__.'/Http/Controllers/stubs/AuthController.php' => app_path('Http/Controllers/AuthController.php'),
        ]);
    }

    public function packageBooted()
    {
        $publicClass = config('laravel-nylo-auth.rate_limits.public');
        $authClass = config('laravel-nylo-auth.rate_limits.authenticated');

        RateLimiter::for('nylo-public', fn ($request) => app($publicClass)->configure());
        RateLimiter::for('nylo-auth', fn ($request) => app($authClass)->configure());
    }
}
