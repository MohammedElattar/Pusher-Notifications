<?php

use Modules\Notification\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('unread_notifications_count', [NotificationController::class, 'unreadNotificationsCount']);
    Route::get('', [NotificationController::class, 'index']);
    Route::patch('', [NotificationController::class, 'markAllAsRead']);
    Route::delete('', [NotificationController::class, 'destroyAll']);
    Route::patch('{notification}', [NotificationController::class, 'markOneAsRead']);
    Route::delete('{notification}', [NotificationController::class, 'destroyOne']);
});
