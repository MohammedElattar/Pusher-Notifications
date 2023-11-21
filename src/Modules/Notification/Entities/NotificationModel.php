<?php

namespace Modules\Notification\Entities;

use App\Models\User;
use App\Traits\PaginationTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Support\Carbon;
use PhpParser\Builder;

/**
 * Modules\Notification\Entities\NotificationModel
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property Carbon|null $read_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $notifiable
 *
 * @method static DatabaseNotificationCollection<int, static> all($columns = ['*'])
 * @method static DatabaseNotificationCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel query()
 * @method static Builder|DatabaseNotification read()
 * @method static Builder|DatabaseNotification unread()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel whereUserNotifiableType()
 * @method static \Illuminate\Database\Eloquent\Builder|NotificationModel formatResult()
 *
 * @mixin Eloquent
 */
class NotificationModel extends DatabaseNotification
{
    use PaginationTrait;

    public function scopeWhereUserNotifiableType($query)
    {
        return $query->whereNotifiableType(User::class);
    }
}
