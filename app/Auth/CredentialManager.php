<?php

namespace App\Auth;

use App\Models\App;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Disponibiliza conferência de credenciais que utilizara u app_id e JWT para
 * identificação entre sistemas.
 */
class CredentialManager
{
    /**
     * 
     * @License: using code from https://github.com/firebase/php-jwt/blob/master/src/JWT.php
     */
    public function parseJwt($jwt) {
        $parts = explode('.', $jwt);

        if (count($parts) != 3) {
            throw new \Exception('Wrong number of segments in JWT');
        }
        
        list($headb64, $bodyb64, $cryptob64) = $parts;

        $header = json_decode(JWT::urlsafeB64Decode($headb64));
        $payload = json_decode(JWT::urlsafeB64Decode($bodyb64));        
        $sig = json_decode(JWT::urlsafeB64Decode($cryptob64));        

        if ($header === null) {
            throw new \Exception('Invalid header encoding in JWT');
        }

        if ($payload === null) {
            throw new \Exception('Invalid claims encoding in JWT');
        }

        if ($sig === false) {
            throw new \Exception('Invalid signature encoding in JWT');
        }        

        return [
            'header' => (array) $header,
            'payload' => (array) $payload,
            'sig' => $sig,
        ];
    }

    /**
     * 
     * @param $user array associativo com informações de usuário, por exemplo `['name' => 'Fernando Bevilacqua', 'uid' => 'fernando.bevilacqua']`
     * @return string JWT que pode ser utilizado como passaporte.
     */
    public function createPassportFromApp(App $app, array $user) {
        $key = $app->secret;
        $payload = array(
            'iss' => $app->name,
            'aud' => $app->domain,
            'iat' => Carbon::now()->timestamp,
            'nbf' => Carbon::now()->timestamp,
            'app_id' => $app->id,
            'user' => $user
        );

        $jwt = JWT::encode($payload, $key);

        return $jwt;
    }

    protected function getJwtKeyFromAppId($app_id) {
        $app = App::findOrFail($app_id);
        return $app->secret;
    }

    /**
     * 
     * @License: using code from https://github.com/firebase/php-jwt/blob/master/src/JWT.php
     */
    public function checkPassport(string $jwt, $key = null) {
        $infos = $this->parseJwt($jwt);
        $payload = $infos['payload'];

        if (!isset($payload['app_id'])) {
            throw new \Exception('Missing app_id in passport payload');
        }

        if ($key == null) {
            $key = $this->getJwtKeyFromAppId($payload['app_id']);
        }

        $decoded = JWT::decode($jwt, $key, array('HS256'));

        return $decoded;
    }

    public function checkPassportThenLocalyAuthenticate(string $jwt, $key = null) {
        // Se o token não for válido, o método abaixo levanta uma exceção.
        $payload = $this->checkPassport($jwt, $key);

        $appId = $payload->app_id;
        $informedUser = (array) $payload->user;
        $user = User::where('uid', $informedUser['uid'])->first();

        if (!$user) {
            $user = $this->createUserFromPassportInfo($informedUser);
        }

        Auth::login($user);
        return $user;
    }

    public function createCredentials(string $passport) {
        if(empty($passport)) {
            return new Credentials(['user' => 'guest']);
        }

        return new Credentials((array)$this->checkPassport($passport));
    }

    public function createUserFromPassportInfo(array $info) {
        $uid = $info['uid'];
        $password = Hash::make($uid . $info['email']);

        $user = User::where(['uid' => $uid])->first();
        $data = [
            'uid' => $uid,
            'email' => $info['email'],
            'name' => $info['name'],
            'password' => $password
        ];

        if($user) {
            $user->update($data);
        } else {
            $user = User::create($data);
        }

        return $user;
    }
}
