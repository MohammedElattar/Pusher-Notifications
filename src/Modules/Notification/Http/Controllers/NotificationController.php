<?php

namespace Modules\Notification\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Notification\Services\NotificationService;
use Modules\Notification\Transformers\NotificationResource;

class NotificationController extends Controller
{
    use HttpResponse;

    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    public function index()
    {
        $notifications = $this->notificationService->index();

        return $this->paginatedResponse($notifications, NotificationResource::class);
    }

    public function unreadNotificationsCount()
    {
        return $this->resourceResponse(
            [
                'unreadNotificationsCount' => auth()->user()->unreadNotifications()->count(),
            ]
        );
    }

    public function markOneAsRead(string $notification): JsonResponse
    {
        $this->notificationService->markOneAsRead($notification);

        return $this->okResponse(
            message: translate_success_message('notification', 'read')
        );

    }

    public function markAllAsRead(): JsonResponse
    {
        $this->notificationService->markAllAsRead();

        return $this->okResponse(
            message: translate_success_message('notifications', 'read')
        );
    }

    public function destroyOne(string $notification): JsonResponse
    {
        $this->notificationService->deleteNotification($notification);

        return $this->okResponse(
            message: translate_success_message('notification', 'deleted')
        );
    }

    public function destroyAll(): JsonResponse
    {
        $this->notificationService->deleteAllNotifications();

        return $this->okResponse(
            message: translate_success_message('notifications', 'deleted')
        );
    }
}
