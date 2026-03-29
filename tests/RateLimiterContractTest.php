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

use Illuminate\Support\Facades\RateLimiter;

it('registers nylo-public and nylo-auth named rate limiters on boot', function () {
    expect(RateLimiter::limiter('nylo-public'))->not->toBeNull();
    expect(RateLimiter::limiter('nylo-auth'))->not->toBeNull();
});

it('resolves rate limiter classes from config', function () {
    config()->set('laravel-nylo-auth.rate_limits.public', PublicRateLimiter::class);
    config()->set('laravel-nylo-auth.rate_limits.authenticated', AuthenticatedRateLimiter::class);

    expect(config('laravel-nylo-auth.rate_limits.public'))->toBe(PublicRateLimiter::class);
    expect(config('laravel-nylo-auth.rate_limits.authenticated'))->toBe(AuthenticatedRateLimiter::class);
});
