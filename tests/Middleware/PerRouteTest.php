<?php

namespace Nylo\LaravelNyloAuth\Tests\Middleware;

use Illuminate\Support\Facades\Route;

class PerRouteTestCase extends MiddlewareTestCase
{
    protected function middlewareConfig(): array
    {
        return [
            'laravel-nylo-auth.middleware.public' => ['group-public-mw'],
            'laravel-nylo-auth.middleware.routes' => [
                'nylo.api.v1.register' => ['captcha'],
                'nylo.api.v1.auth.user' => ['log.user.access'],
            ],
        ];
    }
}

uses(PerRouteTestCase::class);

it('attaches per-route middleware only to the targeted route', function () {
    $register = Route::getRoutes()->getByName('nylo.api.v1.register')->middleware();
    $login = Route::getRoutes()->getByName('nylo.api.v1.login')->middleware();
    $forgot = Route::getRoutes()->getByName('nylo.api.v1.forgot-password')->middleware();

    expect($register)->toContain('captcha');
    expect($login)->not->toContain('captcha');
    expect($forgot)->not->toContain('captcha');
});

it('applies per-route middleware on top of group-level custom middleware', function () {
    $register = Route::getRoutes()->getByName('nylo.api.v1.register')->middleware();

    expect($register)->toContain('throttle:nylo-public');
    expect($register)->toContain('group-public-mw');
    expect($register)->toContain('captcha');
});

it('runs built-in and group middleware before per-route middleware', function () {
    $register = Route::getRoutes()->getByName('nylo.api.v1.register')->middleware();

    expect(array_search('throttle:nylo-public', $register))
        ->toBeLessThan(array_search('captcha', $register));

    expect(array_search('group-public-mw', $register))
        ->toBeLessThan(array_search('captcha', $register));
});

it('supports per-route middleware on authenticated routes', function () {
    $user = Route::getRoutes()->getByName('nylo.api.v1.auth.user')->middleware();

    expect($user)->toContain('auth:sanctum');
    expect($user)->toContain('throttle:nylo-auth');
    expect($user)->toContain('log.user.access');
});

it('runs auth:sanctum before per-route authenticated middleware', function () {
    $user = Route::getRoutes()->getByName('nylo.api.v1.auth.user')->middleware();

    expect(array_search('auth:sanctum', $user))
        ->toBeLessThan(array_search('log.user.access', $user));
});
