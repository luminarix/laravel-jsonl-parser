<?php

namespace Luminarix\Skeleton\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestCase
{
    protected function setUp(): void
    {
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Luminarix\\Skeleton\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }
}
