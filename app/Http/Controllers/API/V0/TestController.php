<?php

namespace App\Http\Controllers\API\V0;

use App\Auth\CredentialManager;
use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use \Firebase\JWT\JWT;

class TestController extends Controller
{
    /**
     * @OA\Get(
     *      path="/credentials",
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
    public function credentials(Request $request, CredentialManager $credentialManager)
    {
        $app = App::first();

        $passport = $credentialManager->createPassportFromApp($app, [
            'iduffs' => 'fernando.bevilacqua',
            'email' => 'fernando.bevilacqua@uffs.edu.br',
            'matricula' => '1611100036',
        ]);

        $credentials = $credentialManager->createCredentials($passport);

        return [
            'credentials' => $credentials,
            'passport' => $passport
        ];
    }

    /**
     * @OA\Get(
     *      path="/passport",
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
    public function passport(Request $request)
    {
        $key = 'example_key';
        $payload = array(
            'iss' => 'http://example.org',
            'aud' => 'http://example.com',
            'iat' => 1356999524,
            'nbf' => 1357000000,
            'app_id' => 0,
            'user' => [
                'name' => 'Fernando Bevilacqua',
                'iduffs' => 'fernando.bevilacqua'
            ]
        );

        $jwt = JWT::encode($payload, $key);
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        return [
            'jwt' => $jwt,
            'decoded' => (array) $decoded
        ];
    }
}