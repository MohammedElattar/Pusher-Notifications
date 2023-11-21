<?php

namespace Modules\Notification\Notifications;

use App\Helpers\DateHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Modules\Notification\Helpers\NotificationHelper;

class BaseNotification extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    private array $finalResult;

    public function __construct(string $message, string $notificationType = '', $id = 0, bool $isClickable = true, bool $shouldTranslateMessage = true)
    {
        $this->finalResult = [
            'isClickable' => $isClickable,
            'notificationType' => $notificationType,
            'modelId' => $id,
            'message' => $message,
            'shouldTranslateMessage' => $shouldTranslateMessage,
        ];
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array
    {
        return NotificationHelper::via();
    }

    /**
     * Get the array representation of the notification.
     */

    // Data will before in database
    public function toDatabase(): array
    {
        return $this->finalResult;
    }

    /**
     * Notification type that would be sent with broadcast message
     */
    public function broadcastType(): string
    {
        return 'AbstractNotification';
    }

    public function toBroadcast(): BroadcastMessage
    {
        $broadcastData = $this->finalResult;
        if (isset($broadcastData['shouldTranslateMessage']) && $broadcastData['shouldTranslateMessage']) {
            $broadcastData['message'] = translate_word($this->finalResult['message']);
        }

        unset($broadcastData['shouldTranslateMessage']);

        return new BroadcastMessage(
            [
                'createdAt' => DateHelper::dateDiffForHumans(now()),
                'seen' => false,
                'body' => $broadcastData,
            ]
        );
    }
}
