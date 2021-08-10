<?php

use App\Http\Controllers\API\V0\AuthController;
use App\Http\Controllers\API\V0\InteractionController;
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
*/

Route::post('/auth', [AuthController::class, 'index'])->name('auth');    

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::match(['GET', 'POST'], 'interact', [InteractionController::class, 'index']);
});

Route::group(['prefix' => '/test'], function () {
    Route::get('/passport', [TestController::class, 'passport'])->name('test.passport');    
    Route::get('/credentials', [TestController::class, 'credentials'])->name('test.credentials');    
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
