<?php

namespace App\Console;

use App\Console\Commands\BuildElasticSearchEventStoreCommand;
use App\Console\Commands\SalesApiEmailNotificationSender;
use App\Console\Commands\SalesApiProjectionGenerator;
use App\Console\Commands\SalesApiProjectionGeneratorAlias;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SalesApiProjectionGenerator::class,
        SalesApiProjectionGeneratorAlias::class,
        SalesApiEmailNotificationSender::class,
        BuildElasticSearchEventStoreCommand::class
    ];
}
