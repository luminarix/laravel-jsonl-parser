<?php

namespace Luminarix\JSONL\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestCase
{
    protected function setUp(): void
    {
        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Luminarix\\JSONL\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }
}
