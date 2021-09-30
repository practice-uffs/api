<?php

namespace App\Http\Controllers;

use App\Auth\CredentialManager;
use App\Http\Controllers\Controller;
use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TestController extends Controller
{
    protected CredentialManager $credentialManager;

    public function __construct(CredentialManager $credentialManager)
    {
        $this->credentialManager = $credentialManager;
    }

    public function index(Request $request) {
        $app = App::where('slug', 'mural')->first();

        if (!$app) {
            return response('Rode no terminal: php artisan db:seed', 404)->header('Content-Type', 'text/plain');
        }

        $uid = $request->input('uid', 'practice');
        $email = $request->input('email', 'practice@uffs.edu.br');
        $name = $request->input('name', 'Usuário teste practice-api');

        $passport = $this->credentialManager->createPassportFromApp($app, [
            'uid' => $uid,
            'email' => $email,
            'name' => $name,
        ], Carbon::now()->addYears(100)->timestamp);

        $url = url('/') . '/v0';
        $cmd = "curl -s -H 'Accept: application/json' -H 'Authorization: Bearer $passport' $url/ping";

        return response(
            "PRACTICE PASSAPORT DESENVOLVIMENTO" . "\n" . str_repeat("---", 20) . "\n\n" .
            "Abaixo está o bearer token (passaporte practice) para uso em desenvolvimento [uid=$uid, email=$email, name=$name]: \n\n" . $passport . "\n\n" .
            "Se precisar testar no terminal, rode: \n\n" . $cmd . "\n\n\n" .
            "PRACTICE PASSAPORT DESENVOLVIMENTO PARA USUÁRIO" . "\n" . str_repeat("---", 20) . "\n\n" .
            "Você pode gerar um token (passaporte practice) para um usuário em particular colocando os parâmetros GET uid e email nessa URL. Ex.:" . "\n\n" .
            route('test') . '?uid=fernando.bevilacqua&email=fernando.bevilacqua@uffs.edu.br'
        )->header('Content-Type', 'text/plain');
    }
}
