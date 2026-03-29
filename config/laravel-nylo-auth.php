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

];
