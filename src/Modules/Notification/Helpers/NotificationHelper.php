<?php

namespace Modules\Notification\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Modules\Notification\Notifications\BaseNotification;

class NotificationHelper
{
    public static function channelPrefix(): string
    {
        return 'notifications';
    }

    public static function via(): array
    {
        return ['database', 'broadcast'];
    }

    public static function notifyUser(User $notifiable, string $message, array $additional = []): void
    {
        $payload = [
            'id' => 0,
            'message' => $message,
            'isClickable' => true,
            'type' => '',
            'shouldTranslateMessage' => true,
        ];

        foreach ($additional as $key => $value) {
            $payload[$key] = $value;
        }

        Notification::send(
            $notifiable,
            new BaseNotification(
                $payload['message'],
                $payload['type'],
                $payload['id'],
                $payload['isClickable'],
                $payload['shouldTranslateMessage'],
            )
        );
    }
}
