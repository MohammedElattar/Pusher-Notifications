<?php
namespace Elattar\PusherNotification\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class EnableCommand extends Command
{
    protected $signature = 'elattar:pusher-notification-enable';

    protected $description = 'Enable pusher notification package';

    public function handle(): void
    {
        $basePath = base_path();

        $this->info('Enabling The module......');

        $process = Process::run("php $basePath/artisan module:enable Notification");

        $this->info($process->output());
    }
}