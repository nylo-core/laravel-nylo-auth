<?php

use App\Models\User;
use Nylo\LaravelNyloAuth\RateLimiters\AuthenticatedRateLimiter;
use Nylo\LaravelNyloAuth\RateLimiters\PublicRateLimiter;

return [

    'user_model' => User::class,

    'rate_limits' => [
        'public' => PublicRateLimiter::class,
        'authenticated' => AuthenticatedRateLimiter::class,
    ],

    // Extra middleware appended to the built-in middleware for each route group.
    // Public routes always run `throttle:nylo-public` first.
    // Authenticated routes always run `auth:sanctum` then `throttle:nylo-auth` first.
    // `routes` targets individual routes by their full name (e.g. `nylo.api.v1.register`).
    // All values must be zero-indexed arrays of middleware aliases or class names.
    'middleware' => [
        'public' => [],
        'authenticated' => [],
        'routes' => [
            // 'nylo.api.v1.register' => ['captcha'],
        ],
    ],

];
