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
            return response('Rode no terminal: php artisan db:seed AppSeeder', 404)->header('Content-Type', 'text/plain');
        }

        $passport = $this->credentialManager->createPassportFromApp($app, [
            'uid' => 'practice',
            'email' => 'practice@uffs.edu.br',
            'name' => 'Practice',
        ], Carbon::now()->addYears(100)->timestamp);


        $url = url('/') . '/v0';
        $cmd = "curl -s -H 'Accept: application/json' -H 'Authorization: Bearer $passport' $url/ping";

        // Change content type to plain text

        return response("Rode no terminal: \n\n" . $cmd)->header('Content-Type', 'text/plain');
    }
}
