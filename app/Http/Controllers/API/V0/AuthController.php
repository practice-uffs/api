<?php

namespace App\Http\Controllers\API\V0;

use App\Models\App;
use App\Auth\CredentialManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected CredentialManager $credentialManager;

    public function __construct(CredentialManager $credentialManager)
    {
        $this->credentialManager = $credentialManager;
    }

    protected function createPassport($appId, $uid, $email, $name)
    {
        $app = App::findOrFail($appId);

        $passport = $this->credentialManager->createPassportFromApp($app, [
            'uid' => $uid,
            'email' => $email,
            'name' => $name
        ]);

        return $passport;
    }

    /**
     * @OA\Get(
     *      path="/projects",
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
    public function index(Request $request)
    {
        $this->validate($request, [
            'user' => 'required',
            'password' => 'required',
        ]);

        $input = $request->all();
        $auth = new \CCUFFS\Auth\AuthIdUFFS();
        $info = (array) $auth->login($input);

        if ($info === null) {
            return response()->json([
                'message' => 'Usuário ou senha incorretos',
                'errors' => [
                    'general' => ['Provided user or password is invalid']
                ]
            ]);
        }

        $user = $this->credentialManager->createUserFromPassportInfo($info);

        $appId = $request->input('app_id');
        $passport = null;

        if ($appId != null) {
            $passport = $this->createPassport($appId, $info['uid'], $info['email'], $info['name']);
        }

        return response()->json([
            'passport' => $passport,
            'user' => [
                'name' => Str::title($info['name']),
                'email' => $info['email'],
                'username' => $info['username'],
                'uid' => $info['uid'],
                'pessoa_id' => $info['pessoa_id']
            ]
        ]);
    }
}
