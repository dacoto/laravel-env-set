<?php

declare(strict_types=1);

namespace dacoto\EnvSet\Tests;

use dacoto\EnvSet\Facades\EnvSet;
use dacoto\EnvSet\EnvSetServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (file_exists(__DIR__ . '/.env')) {
            unlink(__DIR__ . '/.env');
        }
        copy(__DIR__ . '/stubs/env', __DIR__ . '/.env');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unlink(__DIR__ . '/.env');
    }

    protected function getPackageProviders($app): array
    {
        return [
            EnvSetServiceProvider::class
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app->useEnvironmentPath(__DIR__);
    }

    protected function getPackageAliases($app): array
    {
        return [
            'EnvSet' => EnvSet::class,
        ];
    }
}
