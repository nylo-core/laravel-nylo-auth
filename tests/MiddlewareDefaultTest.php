<?php

use Illuminate\Support\Facades\Route;

it('registers routes with only built-in middleware when config is empty', function () {
    $loginRoute = Route::getRoutes()->getByName('nylo.api.v1.login');
    $userRoute = Route::getRoutes()->getByName('nylo.api.v1.auth.user');

    expect($loginRoute->middleware())->toContain('throttle:nylo-public');
    expect($userRoute->middleware())->toContain('auth:sanctum');
    expect($userRoute->middleware())->toContain('throttle:nylo-auth');
});

it('exposes empty arrays as middleware config defaults', function () {
    expect(config('laravel-nylo-auth.middleware.public'))->toBe([]);
    expect(config('laravel-nylo-auth.middleware.authenticated'))->toBe([]);
    expect(config('laravel-nylo-auth.middleware.routes'))->toBe([]);
});
