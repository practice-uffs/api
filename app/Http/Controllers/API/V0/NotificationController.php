<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Notifications\PushNotification;

class NotificationController extends Controller
{
    public function push(Request $request){
        $user = auth()->user();

        $data = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $user->notify(new PushNotification($data['title'], $data['body']));

        return response(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
