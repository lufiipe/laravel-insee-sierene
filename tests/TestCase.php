<?php

namespace LuFiipe\LaravelInseeSierene\Tests;

use LuFiipe\LaravelInseeSierene\InseeSiereneServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * INSEE Sierene base test
 */
abstract class TestCase extends Orchestra
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
            InseeSiereneServiceProvider::class,
        ];
    }
}
