<?php

namespace App\Http\Controllers\API\V0;

use App\Auth\CredentialManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InteractionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CredentialManager $credentialManager)
    {
        $command = $request->input('q', 'Hello, world!');
        $passport = $request->header('X-Passport', '');

        $credentials = $credentialManager->createCredentials($passport);

        return [$credentials];
    }
}
