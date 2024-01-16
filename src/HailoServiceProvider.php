<?php

namespace Pardalsalcap\Hailo;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Pardalsalcap\Hailo\Commands\HailoCommand;

class HailoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('hailo')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_hailo_table')
            ->hasCommand(HailoCommand::class);
    }
}
