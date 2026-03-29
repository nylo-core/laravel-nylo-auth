<?php

namespace Nylo\LaravelNyloAuth\Tests;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use Laravel\Sanctum\SanctumServiceProvider;
use Nylo\LaravelNyloAuth\LaravelNyloAuthServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Workbench\App\Models\User;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! class_exists(AuthController::class)) {
            require __DIR__.'/../src/Http/Controllers/stubs/AuthController.php';
        }
        if (! class_exists(ApiController::class)) {
            require __DIR__.'/../src/Http/Controllers/stubs/ApiController.php';
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            SanctumServiceProvider::class,
            LaravelNyloAuthServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();

        $this->app['db']->connection()->getSchemaBuilder()->create('personal_access_tokens', function ($table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        config()->set('laravel-nylo-auth.user_model', User::class);
        config()->set('auth.providers.users.model', User::class);
    }
}
