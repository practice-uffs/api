<?php

use App\Http\Controllers\API\V0\ApiProxyController;
use App\Http\Controllers\API\V0\AuraController;
use App\Http\Controllers\API\V0\ChannelsController;
use App\Http\Controllers\API\V0\NotificationController;
use App\Http\Controllers\API\V0\AuthController;
use App\Http\Controllers\API\V0\CheckinController;
use App\Http\Controllers\API\V0\EnvironmentController;
use App\Http\Controllers\API\V0\InteractionController;
use App\Http\Controllers\API\V0\MuralController;
use App\Http\Controllers\API\V0\PingController;
use App\Http\Controllers\API\V0\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| Api routes shown below available at the /v0/ url.
*/

Route::post('/auth', [AuthController::class, 'index'])->name('auth');
Route::get('/checkin/marker', [CheckinController::class, 'marker']);

// Authendicated routes
Route::group(['middleware' => 'jwt.practice'], function () {
    // Aura
    Route::get('aura/nlp/{route}/{text}', [AuraController::class, 'index']);
    Route::match(['GET', 'POST'], 'interact', [InteractionController::class, 'index']);

    // Channels
    Route::post('user/channels', [ChannelsController::class, 'store']);
    Route::patch('user/channels', [ChannelsController::class, 'update']);
    Route::delete('user/channels', [ChannelsController::class, 'destroy']);

    // Notification
    Route::get('user/notify/push', [NotificationController::class, 'push']);

    // Environment
    Route::get('env', [EnvironmentController::class, 'index']);        

    // Check-in
    Route::get('/checkin/marker', [CheckinController::class, 'marker']);
    Route::post('/checkin', [CheckinController::class, 'store']);

    // Proxy para apis de outros serviços
    // Mural
    Route::get('/{app}/orders', [ApiProxyController::class, 'proxy']);
    Route::get('/{app}/ideas', [ApiProxyController::class, 'proxy']);
    Route::get('/{app}/categories', [ApiProxyController::class, 'proxy']);
    Route::get('/{app}/services', [ApiProxyController::class, 'proxy']);
    Route::get('/{app}/locations', [ApiProxyController::class, 'proxy']);

    // Test
    Route::get('ping', [PingController::class, 'index']);
});

// Test routes
Route::group(['prefix' => '/test'], function () {
    Route::get('/passport', [TestController::class, 'passport'])->name('test.passport');
    Route::get('/credentials', [TestController::class, 'credentials'])->name('test.credentials');
});
