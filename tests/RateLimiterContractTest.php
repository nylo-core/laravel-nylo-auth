<?php

use Nylo\LaravelNyloAuth\Contracts\RateLimiterContract;

it('RateLimiterContract interface exists and defines configure method')
    ->expect(RateLimiterContract::class)
    ->toBeInterface()
    ->toHaveMethod('configure');

use Nylo\LaravelNyloAuth\RateLimiters\PublicRateLimiter;
use Nylo\LaravelNyloAuth\RateLimiters\AuthenticatedRateLimiter;
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

it('AuthenticatedRateLimiter implements RateLimiterContract', function () {
    expect(new AuthenticatedRateLimiter())
        ->toBeInstanceOf(RateLimiterContract::class);
});

it('AuthenticatedRateLimiter returns a Limit of 60 per minute', function () {
    $limiter = new AuthenticatedRateLimiter();
    $limit = $limiter->configure();

    expect($limit)->toBeInstanceOf(Limit::class);
    expect($limit->maxAttempts)->toBe(60);
    expect($limit->decayMinutes)->toBe(1);
});
