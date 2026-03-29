<?php

namespace Nylo\LaravelNyloAuth;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'nylo.api.v1.',
    'prefix' => '/app/v1',
    'middleware' => 'throttle:nylo-public',
], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');

    Route::group([
        'middleware' => ['auth:sanctum', 'throttle:nylo-auth'],
        'as' => 'auth.',
    ], function () {

        Route::get('user', [ApiController::class, 'getUser'])->name('user');

    });
});
