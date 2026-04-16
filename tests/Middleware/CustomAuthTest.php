<?php

namespace Nylo\LaravelNyloAuth\Tests\Middleware;

use Illuminate\Support\Facades\Route;

class CustomAuthTestCase extends MiddlewareTestCase
{
    protected function middlewareConfig(): array
    {
        return ['laravel-nylo-auth.middleware.authenticated' => ['custom-auth-mw']];
    }
}

uses(CustomAuthTestCase::class);

it('appends configured middleware to the authenticated route', function () {
    $route = Route::getRoutes()->getByName('nylo.api.v1.auth.user');

    expect($route)->not->toBeNull();
    expect($route->middleware())->toContain('auth:sanctum');
    expect($route->middleware())->toContain('throttle:nylo-auth');
    expect($route->middleware())->toContain('custom-auth-mw');
});

it('runs auth:sanctum before custom authenticated middleware', function () {
    $mw = Route::getRoutes()->getByName('nylo.api.v1.auth.user')->middleware();

    expect(array_search('auth:sanctum', $mw))
        ->toBeLessThan(array_search('custom-auth-mw', $mw));
});

it('does not leak authenticated middleware onto public routes', function () {
    $mw = Route::getRoutes()->getByName('nylo.api.v1.login')->middleware();

    expect($mw)->not->toContain('custom-auth-mw');
});
