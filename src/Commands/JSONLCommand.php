<?php

namespace Luminarix\JSONL\Commands;

use Illuminate\Console\Command;

class JSONLCommand extends Command
{
    public $signature = 'laravel-jsonl-parser';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
