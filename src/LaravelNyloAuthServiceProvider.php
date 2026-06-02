<?php

namespace Nylo\LaravelNyloAuth;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\RateLimiter;
use InvalidArgumentException;
use Nylo\LaravelNyloAuth\Contracts\RateLimiterContract;
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

    public function packageBooted(): void
    {
        RateLimiter::for('nylo-public', fn () => $this->resolveRateLimiter('public')->configure());
        RateLimiter::for('nylo-auth', fn () => $this->resolveRateLimiter('authenticated')->configure());
    }

    /**
     * Resolve a configured rate limiter from the container, ensuring it
     * honours the package contract. Resolved per request so limiters may
     * depend-inject.
     */
    protected function resolveRateLimiter(string $group): RateLimiterContract
    {
        $class = Config::string("laravel-nylo-auth.rate_limits.{$group}");
        $limiter = app($class);

        if (! $limiter instanceof RateLimiterContract) {
            throw new InvalidArgumentException(
                "Rate limiter [{$class}] must implement ".RateLimiterContract::class.'.'
            );
        }

        return $limiter;
    }
}
