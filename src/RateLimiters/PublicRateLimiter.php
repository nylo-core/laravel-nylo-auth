<?php

namespace Nylo\LaravelNyloAuth\RateLimiters;

use Illuminate\Cache\RateLimiting\Limit;
use Nylo\LaravelNyloAuth\Contracts\RateLimiterContract;

class PublicRateLimiter implements RateLimiterContract
{
    public function configure(): Limit|array
    {
        return Limit::perMinute(5)->by(request()->ip());
    }
}
