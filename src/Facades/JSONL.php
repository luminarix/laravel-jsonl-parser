<?php

namespace Luminarix\JSONL\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Luminarix\JSONL\JSONL
 */
class JSONL extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Luminarix\JSONL\JSONL::class;
    }
}
