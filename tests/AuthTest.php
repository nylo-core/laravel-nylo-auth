<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Workbench\App\Models\User;

// --- Login ---

it('logs in with valid credentials and returns a token', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/app/v1/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['status', 'token', 'message']);
    expect($response->json('status'))->toBe(200);
    expect($response->json('token'))->not->toBeEmpty();
});

it('fires Login event on successful login', function () {
    Event::fake([Login::class]);

    User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $this->postJson('/app/v1/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    Event::assertDispatched(Login::class);
});

it('rejects login with wrong password', function () {
    User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/app/v1/login', [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    expect($response->json('status'))->toBe(510);
    expect($response->json('message'))->toBe('Invalid login details');
});

it('rejects login with non-existent email', function () {
    $response = $this->postJson('/app/v1/login', [
        'email' => 'nobody@example.com',
        'password' => 'password',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['email']);
});

it('rejects login with missing fields', function () {
    $response = $this->postJson('/app/v1/login', []);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['email', 'password']);
});

// --- Register ---

it('registers a new user and returns a token', function () {
    $response = $this->postJson('/app/v1/register', [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['status', 'token', 'message']);
    expect($response->json('status'))->toBe(200);
    expect($response->json('token'))->not->toBeEmpty();

    $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
});

it('fires Registered event on registration', function () {
    Event::fake([Registered::class]);

    $this->postJson('/app/v1/register', [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password',
    ]);

    Event::assertDispatched(Registered::class);
});

it('registers a user without a name', function () {
    $response = $this->postJson('/app/v1/register', [
        'email' => 'noname@example.com',
        'password' => 'password',
    ]);

    $response->assertOk();
    $this->assertDatabaseHas('users', [
        'email' => 'noname@example.com',
        'name' => '',
    ]);
});

it('rejects registration with an existing email', function () {
    User::create([
        'name' => 'Existing',
        'email' => 'taken@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/app/v1/register', [
        'name' => 'New User',
        'email' => 'taken@example.com',
        'password' => 'password',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['email']);
});

it('rejects registration with missing fields', function () {
    $response = $this->postJson('/app/v1/register', []);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['email', 'password']);
});

// --- Forgot Password ---

it('sends a password reset link for a valid email', function () {
    Notification::fake();

    User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/app/v1/forgot-password', [
        'email' => 'test@example.com',
    ]);

    $response->assertOk();
    expect($response->json('status'))->toBe(200);
});

it('rejects forgot password with non-existent email', function () {
    $response = $this->postJson('/app/v1/forgot-password', [
        'email' => 'nobody@example.com',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['email']);
});

it('rejects forgot password with missing email', function () {
    $response = $this->postJson('/app/v1/forgot-password', []);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['email']);
});

// --- Authenticated Route ---

it('returns user data with a valid sanctum token', function () {
    $user = User::create([
        'name' => 'Auth User',
        'email' => 'auth@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->getJson('/app/v1/user');

    $response->assertOk();
    expect($response->json('email'))->toBe('auth@example.com');
});

it('rejects unauthenticated access to user endpoint', function () {
    $response = $this->getJson('/app/v1/user');

    $response->assertUnauthorized();
});
