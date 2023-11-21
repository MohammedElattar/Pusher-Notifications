# Pusher Notification

- Install prepare package from [HERE](https://github.com/MohammedElattar/Prepare)
- Install pusher notification package
```shell
composer require elattar/pusher-notification
```

- Publish Notification Module
```shell
php artisan vendor:publish --tag=elattar-pusher-notifications
```

- Enable Notification Module
```shell
php artisan elattar:pusher-notification-enable
```
- Publish migration file
```shell
php artisan notifications:table
```
```shell
php artisan migrate
```
- `app\Models\User.php`
```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'notifications.users.' . $this->id;
    }
}
```

- `config\app.php`
```php
<?php

use Illuminate\Support\ServiceProvider;

return [
    'providers' => ServiceProvider::defaultProviders()->merge([
        ... 
        App\Providers\BroadcastServiceProvider::class,
    ])->toArray(),
];

```

- `app\Providers\BroadcastServiceProvider.php`
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require_once base_path('Modules/Notification/Routes/channels.php');
    }
}
```

- `routes\api.php`

```php
<?php

use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\SelectMenuController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Broadcast::routes();
// Broadcast::routes(['middleware' => ['auth:sanctum']]); // add auth middleware 

```


## Pusher Configurations

- `.env`
```apacheconf
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=YOUR_Pusher_ID
PUSHER_APP_KEY=YOUR_PUSHER_KEY
PUSHER_APP_SECRET=YOUR_PUSHER_SECRET
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=YOUR_PUSHER_CLUSTER
PUSHER_HOST=
PUSHER_PORT=443
```

## To test that out try that block of code
```html
<!DOCTYPE html>
<html>

<head>
    <title>Document</title>
</head>
<body>
    <!-- Axios -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <!-- <script src="main.js"></script> -->
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        const token = "2|FVyVdy317Mp866IP5p0G9JyVv5FiwR3qAGTlnjgL",
                PUBLIC_KEY = 'Your_Public_Key',
                CLUSTER ='mt1',
                AUTH_ENDPOINT = 'https://yourdomain.com/broadcasting/auth',
                CHANNEL_NAME = `private-notifications.users.${LocalStorage.getItem('loggedUserId')}`;
                EVENT_NAME = "Illuminate\\Notifications\\Events\\BroadcastNotificationCreated";
                
        let pusher = new Pusher(PUBLIC_KEY, {
            cluster: CLUSTER,
            channelAuthorization: {
                withCredentials:true,
                endpoint: AUTH_ENDPOINT,
                headers: {
                    "Authorization": `Bearer ${token}`,
                }
            }
        });

        let channel = pusher.subscribe(CHANNEL_NAME);
        channel.bind(EVENT_NAME, function (data) {
            console.log(data);
        });
    </script>
</body>

</html>
```
