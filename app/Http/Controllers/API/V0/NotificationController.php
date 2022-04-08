<?php

namespace App\Http\Controllers\API\V0;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Notifications\PushNotification;

class NotificationController extends Controller
{
    
    /**
     * @OA\Get(
     *      path="/user/notify/push",
     *      operationId="getProjectsList",
     *      tags={"Notification"},
     *      summary="Get list of projects",
     *      description="Returns list of projects",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
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
