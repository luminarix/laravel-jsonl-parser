<?php

namespace Luminarix\JSONL\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Luminarix\JSONL\JSONLServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Luminarix\\JSONL\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            JSONLServiceProvider::class,
        ];
    }
}
