<?php

namespace App\Http\Proxy;

use App\Http\Controllers\API\V0\ApiProxyController;
use Illuminate\Support\Facades\Route;

class PracticeApiProxy
{
    public static function resource(string $route)
    {
        Route::get($route, [ApiProxyController::class, 'proxy']);
        Route::post($route, [ApiProxyController::class, 'proxy']);
        Route::get("$route/{item}", [ApiProxyController::class, 'proxy']);
        Route::put("$route/{item}", [ApiProxyController::class, 'proxy']);
        Route::patch("$route/{item}", [ApiProxyController::class, 'proxy']);
        Route::delete("$route/{item}", [ApiProxyController::class, 'proxy']);
    }
}
