<?php

use App\Models\User;
use Modules\Notification\Helpers\NotificationHelper;

Broadcast::channel(NotificationHelper::channelPrefix().'.users.{id}', function (User $user, $id) {
    return $user->id == $id;
});
