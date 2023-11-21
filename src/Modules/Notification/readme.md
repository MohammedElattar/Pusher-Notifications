# Notification Module

## Features

- Sending realtime notifications using pusher 
- Storing notifications in database to save it

## How To Install

### Used Packages
- Follow instructions to install laravel modules [HERE](https://nwidart.com/laravel-modules/v6/installation-and-setup)

Enable The Module

```shell
php artisan module:enable Notification
```

- Run that command to sync needed packages

```shell
php artisan module:update Notification
```

- Publish migration file
```shell
php artisan notifications:table
```
```shell
php artisan migrate
```

## Used files outside of module should include

- `app\Exceptions\Handler.php`
```php
<?php

namespace App\Exceptions;

use App\Traits\HttpResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Str;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Twilio\Exceptions\EnvironmentException;
use Twilio\Exceptions\RestException;

class Handler extends ExceptionHandler
{
    use HttpResponse;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle Unauthorized User
        $this->renderable(function (AuthenticationException $e, $req) {

            return $this->unauthenticatedResponse('You are not authenticated');
        });

        $this->renderable(function (NotFoundHttpException $e, $req) {
            $msg = $e->getMessage();

            if (Str::contains($msg, 'No query', true)) {
                $msg = translate_error_message('record', 'not_found');
            }

            return $this->errorResponse(null, Response::HTTP_NOT_FOUND, $msg);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return $this->errorResponse(
                null,
                Response::HTTP_METHOD_NOT_ALLOWED, $e->getMessage()
            );
        });

        // Too Many Requests
        $this->renderable(function (ThrottleRequestsException $e, $request) {
            return $this->errorResponse(
                null,
                Response::HTTP_TOO_MANY_REQUESTS,
                $e->getMessage()
            );
        });

        // Don't Have Permissions

        $this->renderable(function (UnauthorizedException $e, $request) {
            return $this->forbiddenResponse(
                translate_word('forbidden')
            );
        });

        $this->renderable(function (RestException $e) {

            $errorMessage = $e->getMessage();

            if (Str::contains($errorMessage, '[HTTP 400] Unable to create record: Invalid parameter `To`')) {
                $errorMessage = translate_word('phone_number_invalid');
            } elseif (Str::match('/.* was not found$/', $errorMessage)) {
                $errorMessage = 'code is incorrect';
            }

            return $this->errorResponse(
                null,
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: $errorMessage,
            );
        });

        $this->renderable(function (EnvironmentException $e) {

            return $this->errorResponse(
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: $e->getMessage()
            );
        });
    }
}

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

- `App\Http\Middleware\AlwaysAcceptJson.php` middleware
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AlwaysAcceptJson
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/vnd.api+json');

        return $next($request);
    }
}
```
- `app\Http\Kernel.php`
```php
<?php

namespace App\Http;

use App\Http\Middleware\AlwaysAcceptJson;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        ...
        AlwaysAcceptJson::class,
    ];
    
    ...
}
```
- `app\Traits\HttpResponse.php`
- `langs` folder
- `app\Helpers\helpers.php`
- `composer.json`
```json
{
  "autoload": {
    "files": [
      "app/Helpers/helpers.php"
    ]
  }
}
```
```shell
composer dump-autoload
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
## Configurations
- `.env`
```shell
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
    
    <button class="loggedUser">Get Logged User Info</button>
    <button id="pusher-test">Test Pusher</button>
    <!-- Axios -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <!-- <script src="main.js"></script> -->
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        const token = "2|FVyVdy317Mp866IP5p0G9JyVv5FiwR3qAGTlnjgL",
                PUBLIC_KEY = 'Your_Public_Key',
                CLUSTER ='mt1',
                AUTH_ENDPOINT = 'http://api.babi-shisha.test/broadcasting/auth',
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
