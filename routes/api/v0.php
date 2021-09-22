<?php

use App\Http\Controllers\API\V0\AuraController;
use App\Http\Controllers\API\V0\ChannelsController;
use App\Http\Controllers\API\V0\NotificationController;
use App\Http\Controllers\API\V0\AuthController;
use App\Http\Controllers\API\V0\CheckinController;
use App\Http\Controllers\API\V0\InteractionController;
use App\Http\Controllers\API\V0\MuralController;
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

// Authendicated routes
Route::group(['middleware' => 'jwt.verify'], function () {
    Route::match(['GET', 'POST'], 'interact', [InteractionController::class, 'index']);

    Route::post('user/channels', [ChannelsController::class, 'store']);
    Route::patch('user/channels', [ChannelsController::class, 'update']);
    Route::delete('user/channels', [ChannelsController::class, 'destroy']);

    Route::get('user/notify/push', [NotificationController::class, 'push']);    

    Route::get('aura/nlp/{route}/{text}', [AuraController::class, 'index']);    

    Route::get('/checkin/marker', [CheckinController::class, 'marker']);    
    Route::post('/checkin', [CheckinController::class, 'store']);    

    // Proxy para apis de outros serviços
    Route::get('/mural/categories', [MuralController::class, 'proxy']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Test routes
Route::group(['prefix' => '/test'], function () {
    Route::get('/passport', [TestController::class, 'passport'])->name('test.passport');    
    Route::get('/credentials', [TestController::class, 'credentials'])->name('test.credentials');    
});