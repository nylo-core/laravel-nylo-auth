<?php

use Nylo\LaravelNyloAuth\Contracts\RateLimiterContract;

it('RateLimiterContract interface exists and defines configure method')
    ->expect(RateLimiterContract::class)
    ->toBeInterface()
    ->toHaveMethod('configure');
