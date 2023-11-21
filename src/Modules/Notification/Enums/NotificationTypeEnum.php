<?php

namespace Modules\Notification\Enums;

enum NotificationTypeEnum
{
    const PREPARING_FIXING = 'preparing_fixing';
    const PENDING_FIXING = 'pending_fixing';
    const TASK_UPDATED = 'task_updated';

    const TASK_CREATED = 'task_created';

    const TASK_FINISHED = 'task_finished';

    const TASK_OVERDUE = 'task_overdue';
}
