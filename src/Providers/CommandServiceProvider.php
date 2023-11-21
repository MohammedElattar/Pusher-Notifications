<?php

namespace Elattar\PusherNotification\Providers;

use Elattar\PusherNotification\Console\Commands\EnableCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if($this->app->runningInConsole())
        {
            $this->commands([
                EnableCommand::class,
            ]);
        }
    }
}