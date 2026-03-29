<?php

namespace Nylo\LaravelNyloAuth\Contracts;

use Illuminate\Cache\RateLimiting\Limit;

interface RateLimiterContract
{
    public function configure(): Limit|array;
}
