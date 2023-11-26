<?php

namespace Nylo\LaravelNyloAuth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;

Route::group([
    'as' => 'nylo.api.v1.',
    'prefix' => '/app/v1'
], function() {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forget-password');

    Route::group([
        'middleware' => 'auth:sanctum',
        'as' => 'auth.'
    ], function() {

        Route::get('user', [ApiController::class, 'getUser'])->name('user');

    });
});
