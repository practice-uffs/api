<?php

namespace App\Http\Controllers\API\V0;

use App\Auth\CredentialManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\App;
use App\Models\User;

class AuthController extends Controller
{
    protected CredentialManager $credentialManager;

    public function __construct(CredentialManager $credentialManager)
    {
        $this->credentialManager = $credentialManager;
    }
    
    protected function createPassport($appId, $uid, $email) {
        $app = App::findOrFail($appId);

        $passport = $this->credentialManager->createPassportFromApp($app, [
            'iduffs' => $uid,
            'email' => $email
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
        $info = $auth->login($input);

        if($info === null) {
            return response()->json([
                'message' => 'Usuário ou senha incorretos',
                'errors' => [
                    'general' => ['Provided user or password is invalid']
                ]
            ]);
        }

        $token = hash('sha256', Str::random(60));
        $password = Hash::make($info->pessoa_id);

        $user = User::where(['uid' => $info->uid])->first();
        $data = [
            'uid' => $info->uid,
            'email' => $info->email,
            'name' => $info->name,
            'password' => $password
        ];

        if($user) {
            $user->update($data);
        } else {
            $user = User::create($data);
        }

        $appId = $request->input('app_id');
        $passport = null;

        if ($appId != null) {
            $passport = $this->createPassport($appId, $info->uid, $info->email);
        } 

        return response()->json([
            'token' => $token,
            'passport' => $passport,
            'user' => [
                'name' => ucwords(strtolower($info->name)),
                'email' => $info->email,
                'username' => $info->username,
                'cpf' => $info->cpf,
                'uid' => $info->uid,
                'pessoa_id' => $info->pessoa_id
            ]
        ]);
    }
}