<?php

namespace App\Http\Controllers\API\V0;

use App\Models\App;
use App\Models\User;
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
    
    protected function createPassport($appId, $uid, $email, $name) {
        if ($appId == null || $appId == 0) {
            return null;
        }

        $app = App::findOrFail($appId);

        $passport = $this->credentialManager->createPassportFromApp($app, [
            'uid' => $uid,
            'email' => $email,
            'name' => $name
        ]);

        return $passport;
    }

    protected function createScrapersIfNeeded(User $user, array $input) {
        $scraper = $user->scrapers()->where('target', 'uffs.edu.br')->first();

        if ($scraper) {
            return;
        }

        $user->scrapers()->create([
            'user_id' => $user->id,
            'target' => 'uffs.edu.br',
            'access_user' => $input['user'],
            'access_password' => $input['password']
        ]);
    }

    protected function attemptAuthentication(array $input) {
        $auth = new \CCUFFS\Auth\AuthIdUFFS();
        $info = (array) $auth->login($input);
        return $info;
    }
    
    /**
     * @OA\Get(
     *      path="/auth",
     *      operationId="getProjectsList",
     *      tags={"Autenticação"},
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
        $info = $this->attemptAuthentication($input);

        if($info === null) {
            return response()->json([
                'message' => 'Usuário ou senha incorretos',
                'errors' => [
                    'general' => ['Provided user or password is invalid']
                ]
            ]);
        }

        $user = $this->credentialManager->createUserFromPassportInfo($info);
        $passport = $this->createPassport($request->input('app_id'),
                                          $info['uid'],
                                          $info['email'],
                                          $info['name']);

        $this->createScrapersIfNeeded($user, $input);

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