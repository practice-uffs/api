<?php

namespace App\Http\Middleware;

use App\Auth\CredentialManager;
use Closure;

class JwtMiddleware
{
    private CredentialManager $credentialManager;

    public function __construct(CredentialManager $credentialManager)
    {
        $this->credentialManager = $credentialManager;       
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Se o token não for válido, o método abaixo levanta uma exceção.
        $this->credentialManager->checkPassportThenLocalyAuthenticate($request->bearerToken());
        return $next($request);
    }
}
