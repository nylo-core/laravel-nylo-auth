<?php

namespace Nylo\LaravelNyloAuth\Tests\Middleware;

use Illuminate\Support\Facades\Route;

class CustomPublicTestCase extends MiddlewareTestCase
{
    protected function middlewareConfig(): array
    {
        return ['laravel-nylo-auth.middleware.public' => ['custom-public-mw', 'another-mw']];
    }
}

uses(CustomPublicTestCase::class);

it('appends configured middleware to every public route', function () {
    foreach (['nylo.api.v1.login', 'nylo.api.v1.register', 'nylo.api.v1.forgot-password'] as $name) {
        $route = Route::getRoutes()->getByName($name);

        expect($route)->not->toBeNull();
        expect($route->middleware())->toContain('throttle:nylo-public');
        expect($route->middleware())->toContain('custom-public-mw');
        expect($route->middleware())->toContain('another-mw');
    }
});

it('runs built-in public throttle before custom middleware', function () {
    $mw = Route::getRoutes()->getByName('nylo.api.v1.login')->middleware();

    expect(array_search('throttle:nylo-public', $mw))
        ->toBeLessThan(array_search('custom-public-mw', $mw));
});
