<?php

namespace Nylo\LaravelNyloAuth;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

$publicMiddleware = array_merge(
    ['throttle:nylo-public'],
    (array) config('laravel-nylo-auth.middleware.public', [])
);

$authMiddleware = array_merge(
    ['auth:sanctum', 'throttle:nylo-auth'],
    (array) config('laravel-nylo-auth.middleware.authenticated', [])
);

$routeMiddleware = (array) config('laravel-nylo-auth.middleware.routes', []);

$perRoute = fn (string $name): array => (array) ($routeMiddleware[$name] ?? []);

Route::group([
    'as' => 'nylo.api.v1.',
    'prefix' => '/app/v1',
    'middleware' => $publicMiddleware,
], function () use ($authMiddleware, $perRoute) {
    Route::post('login', [AuthController::class, 'login'])
        ->name('login')
        ->middleware($perRoute('nylo.api.v1.login'));

    Route::post('register', [AuthController::class, 'register'])
        ->name('register')
        ->middleware($perRoute('nylo.api.v1.register'));

    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('forgot-password')
        ->middleware($perRoute('nylo.api.v1.forgot-password'));

    Route::group([
        'middleware' => $authMiddleware,
        'as' => 'auth.',
    ], function () use ($perRoute) {

        Route::get('user', [ApiController::class, 'getUser'])
            ->name('user')
            ->middleware($perRoute('nylo.api.v1.auth.user'));

    });
});
