<?php

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

Route::post('auth/login', 'App\Http\Controllers\API\AuthController@login');

Route::group(['middleware'=>['apiJwt']],function(){
    Route::post('auth/logout', 'App\Http\Controllers\API\AuthController@logout');
    Route::post('auth/refresh', 'App\Http\Controllers\API\AuthController@refresh');
    Route::post('auth/me', 'App\Http\Controllers\API\AuthController@me');
});