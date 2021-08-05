<?php

namespace App\Http\Controllers\API\V0;

use App\Auth\CredentialManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InteractionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/oi",
     *      operationId="getProjectsList",
     *      tags={"Projects"},
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
    public function index(Request $request, CredentialManager $credentialManager)
    {
        $command = $request->input('q', 'Hello, world!');
        $passport = $request->header('X-Passport', '');

        $credentials = $credentialManager->createCredentials($passport);

        return [$credentials];
    }
}
