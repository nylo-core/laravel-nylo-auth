<?php

namespace Nylo\LaravelNyloAuth\Tests\Middleware;

use Nylo\LaravelNyloAuth\Tests\TestCase;

abstract class MiddlewareTestCase extends TestCase
{
    /**
     * Subclasses return ['config.key' => value, ...] to apply before the
     * package service provider boots and routes are registered.
     */
    abstract protected function middlewareConfig(): array;

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        foreach ($this->middlewareConfig() as $key => $value) {
            config()->set($key, $value);
        }
    }
}
