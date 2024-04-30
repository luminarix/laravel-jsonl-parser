<?php

namespace Luminarix\JSONL;

use Luminarix\JSONL\Commands\JSONLCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class JSONLServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-jsonl-parser')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-jsonl-parser_table')
            ->hasCommand(JSONLCommand::class);
    }
}
