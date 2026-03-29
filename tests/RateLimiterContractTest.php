<?php

use Nylo\LaravelNyloAuth\Contracts\RateLimiterContract;

it('RateLimiterContract interface exists and defines configure method')
    ->expect(RateLimiterContract::class)
    ->toBeInterface()
    ->toHaveMethod('configure');

use Nylo\LaravelNyloAuth\RateLimiters\PublicRateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

it('PublicRateLimiter implements RateLimiterContract', function () {
    expect(new PublicRateLimiter())
        ->toBeInstanceOf(RateLimiterContract::class);
});

it('PublicRateLimiter returns a Limit of 5 per minute', function () {
    $limiter = new PublicRateLimiter();
    $limit = $limiter->configure();

    expect($limit)->toBeInstanceOf(Limit::class);
    expect($limit->maxAttempts)->toBe(5);
    expect($limit->decayMinutes)->toBe(1);
});
