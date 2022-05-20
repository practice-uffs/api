<?php

namespace App\Http\Controllers\API\V0;

use App\Auth\CredentialManager;
use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/user",
     *      operationId="getProjectsList",
     *      tags={"Teste"},
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
    public function index(Request $request)
    {
        return response()->json($request->user());
    }

    public function consent(Request $request)
    {   
        $user = $request->user();
        $data = [
            'aura_consent' => 1
        ];
        $user->update($data);

        return response()->json(
            ['aura_consent' => $user->aura_consent],
            Response::HTTP_OK
        );
    }

    public function unconsent(Request $request)
    {   
        // We're not saving this user's message history yet
        // Here should be done the deletion of the message history of this user
        $user = $request->user();
        $data = [
            'aura_consent' => 0
        ];
        $user->update($data);

        return response()->json(
            ['aura_consent' => $user->aura_consent],
            Response::HTTP_OK
        );
    }

    public function setAuraHistory(Request $request)
    {       
        $validator = Validator::make($request->all(), [
            'aura_history' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()], 
                Response::HTTP_BAD_REQUEST
            ); 
        }

        $user = $request->user();
        if ($user->aura_history == null){
            $data = [
                 'aura_history' => array($request["aura_history"])
            ];
        } else {
            $history = $user->aura_history;
            $newMessage = $request["aura_history"];
            array_push ($history,$newMessage);
            $data = [
                'aura_history' => $history
            ];
        }
        $user->update($data);

        return response()->json(
            Response::HTTP_OK
        );
    }

    public function getAuraHistory(Request $request)
    {   
        $user = $request->user();
        return response()->json(
            ['aura_history' => $user->aura_history],
            Response::HTTP_OK
        );
    }

    public function deleteAuraHistory(Request $request)
    {   
        $user = $request->user();
        $data = [
            'aura_history' => null
        ];
        $updated = $user->update($data);
        if ($updated) {
            return response()->json(
                Response::HTTP_OK
            );
        }
        
        return response()->json(
            ['errors' => ['updated' => $updated]], 
            Response::HTTP_BAD_REQUEST
        ); 
    }
}