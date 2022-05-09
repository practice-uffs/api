<?php

namespace App\Http\Controllers\API\V0;

use App\Auth\CredentialManager;
use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use \Firebase\JWT\JWT;

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
}