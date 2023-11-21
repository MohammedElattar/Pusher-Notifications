<?php

namespace Elattar\PusherNotification\Providers;

use Illuminate\Support\ServiceProvider;

class PusherNotificationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../Modules/Notification' => base_path('Modules/Notification'),
        ],
            'elattar-pusher-notifications',
        );
    }

    public function register(): void
    {
        $this->app->register(CommandServiceProvider::class);
    }
}