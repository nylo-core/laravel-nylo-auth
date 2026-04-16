# Mobile App Authentication for Laravel

This package provides API authentication endpoints for [Nylo](https://nylo.dev) Flutter apps, powered by Laravel Sanctum.

Check out the Flutter package here: [laravel_auth_slate](https://pub.dev/packages/laravel_auth_slate)

## Requirements

- PHP ^8.1
- Laravel 10, 11, 12, or 13
- Laravel Sanctum
- Your `User` model must use the `HasApiTokens` trait:

```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    // ...
}
```

## Installation

```bash
composer require nylo/laravel-nylo-auth
```

Publish the config and controllers:

```bash
php artisan vendor:publish --provider="Nylo\LaravelNyloAuth\LaravelNyloAuthServiceProvider"
```

This publishes:
- `config/laravel-nylo-auth.php`
- `app/Http/Controllers/AuthController.php`
- `app/Http/Controllers/ApiController.php`

## API Endpoints

All routes are prefixed with `/app/v1`.

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| POST | `/app/v1/login` | `nylo.api.v1.login` | Login and receive a Sanctum token |
| POST | `/app/v1/register` | `nylo.api.v1.register` | Register a new user and receive a token |
| POST | `/app/v1/forgot-password` | `nylo.api.v1.forgot-password` | Send a password reset link |
| GET | `/app/v1/user` | `nylo.api.v1.auth.user` | Get the authenticated user (requires Sanctum token) |

## Configuration

```php
// config/laravel-nylo-auth.php

return [
    // The Eloquent model used for authentication
    'user_model' => \App\Models\User::class,

    // Rate limiter classes for each route group
    'rate_limits' => [
        'public' => \Nylo\LaravelNyloAuth\RateLimiters\PublicRateLimiter::class,         // 5 req/min by IP
        'authenticated' => \Nylo\LaravelNyloAuth\RateLimiters\AuthenticatedRateLimiter::class, // 60 req/min by user
    ],
];
```

## Rate Limiting

Rate limiting is applied to all routes via named Laravel rate limiters:

- **`nylo-public`** — applies to login, register, and forgot-password (default: 5 requests/min per IP)
- **`nylo-auth`** — applies to authenticated routes (default: 60 requests/min per user)

### Custom Rate Limiters

Create a class that implements `RateLimiterContract` and update the config:

```php
use Nylo\LaravelNyloAuth\Contracts\RateLimiterContract;
use Illuminate\Cache\RateLimiting\Limit;

class MyPublicRateLimiter implements RateLimiterContract
{
    public function configure(): Limit|array
    {
        return Limit::perMinute(10)->by(request()->ip())->response(function () {
            return response()->json(['message' => 'Too many requests'], 429);
        });
    }
}
```

Then in `config/laravel-nylo-auth.php`:

```php
'rate_limits' => [
    'public' => \App\RateLimiters\MyPublicRateLimiter::class,
    'authenticated' => \Nylo\LaravelNyloAuth\RateLimiters\AuthenticatedRateLimiter::class,
],
```

## Custom Middleware

You can append your own middleware to the package's route groups via `config/laravel-nylo-auth.php`. Entries are merged *after* the built-in `throttle:*` and `auth:sanctum` middleware, so rate limiting and authentication still run first.

```php
'middleware' => [
    'public' => ['locale'],              // login, register, forgot-password
    'authenticated' => ['log.requests'], // authenticated endpoints (e.g. /user)

    // Target individual routes by their full name
    'routes' => [
        'nylo.api.v1.register' => ['captcha'],
        'nylo.api.v1.auth.user' => ['log.user.access'],
    ],
],
```

Use any middleware alias registered in your app or a fully-qualified middleware class name. Per-route middleware runs *after* the built-in and group-level middleware for that route.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Anthony Gordon](https://github.com/agordn52)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
