<?php

namespace Nylo\LaravelNyloAuth\Contracts;

use Illuminate\Cache\RateLimiting\Limit;

interface RateLimiterContract
{
    /**
     * @return Limit|Limit[]
     */
    public function configure(): Limit|array;
}
