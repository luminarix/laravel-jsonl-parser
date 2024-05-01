<?php

declare(strict_types=1);

namespace Luminarix\JSONL\Tests\classes;

readonly class ExampleDTO
{
    /**
     * @param  array<array<string, string>>  $wins
     */
    public function __construct(
        public string $name,
        public array $wins,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'wins' => $this->wins,
        ];
    }
}
