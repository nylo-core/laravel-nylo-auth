<?php

return [

    'user_model' => \App\Models\User::class,

    'rate_limits' => [
        'public' => \Nylo\LaravelNyloAuth\RateLimiters\PublicRateLimiter::class,
        'authenticated' => \Nylo\LaravelNyloAuth\RateLimiters\AuthenticatedRateLimiter::class,
    ],

];
