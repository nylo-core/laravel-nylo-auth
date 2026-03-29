<?php

namespace Nylo\LaravelNyloAuth\RateLimiters;

use Illuminate\Cache\RateLimiting\Limit;
use Nylo\LaravelNyloAuth\Contracts\RateLimiterContract;

class AuthenticatedRateLimiter implements RateLimiterContract
{
    public function configure(): Limit|array
    {
        return Limit::perMinute(60)->by(request()->user()?->id ?: request()->ip());
    }
}
