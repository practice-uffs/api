<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Auth\CredentialManager;
use App\Models\App;
use Illuminate\Support\Facades\Log;

/**
 * Disponibiliza formas de consumo de API de sistema web do próprio programa a partir
 * da nossa API central. Em linha gerais, essa classe é um cliente REST para consumo de
 * APIs de sistemas web que estejam funcionando. O objetivo dela é permitir que a API
 * central do programa tenha endpoint de outra apis (um api gateway).
 * 
 * @author Fernando Bevilacqua <fernando.bevilacqua@uffs.edu.br>
 */
class PracticeApiClientService
{
    protected array $config;
    protected Client $client;
    protected CredentialManager $credentialManager;

    protected function client()
    {
        $guzzClient = new Client([
            \GuzzleHttp\RequestOptions::VERIFY => \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath()
        ]);

        return $guzzClient;
    }

    protected function appRequestHeaders(App $app) {
        $authUser = auth()->user();
        $user = [
            'id' => $authUser->id,
            'uid' => $authUser->uid,
            'name' => $authUser->name,
            'email' => $authUser->email
        ];
        $tokenTtl = $this->config['token_ttl_seconds'];
        $bearerToken = $this->credentialManager->createPassportFromApp($app, $user, $tokenTtl);

        return [
            'Authorization' => 'Bearer ' . $bearerToken,
            'Accept' => 'application/json',
        ];
    }

    protected function appRoute(App $app, $route) {
        if ($route[0] == '/') {
            $route = substr($route, 1);
        }

        return $app->api_url . $route;
    }    

    public function __construct(array $config, CredentialManager $credentialManager)
    {
        $this->config = $config;
        $this->credentialManager = $credentialManager;
        $this->client = $this->client();
    }

    public function fetch(App $app, string $verb, string $route, array $params = [], array $headers = []) 
    {
        $headers = array_merge($this->appRequestHeaders($app), $headers);
        $url = $this->appRoute($app, $route);

        if (config('app.debug')) {
            Log::debug('PracticeApiClientService::fetch', [
                'verb' => $verb,
                'url' => $url,
                'params' => $params,
                'headers' => $headers,
            ]);
        }        

        switch(strtoupper($verb)) {
            case 'GET':
                $response = $this->client->get($url, [
                    'query' => $params,
                    'headers' => $headers
                ]);
                break;

            case 'POST':
                $response = $this->client->post($url, [
                    'form_params' => $params,
                    'headers' => $headers
                ]);
                break;

            case 'PUT':
                $response = $this->client->put($url, [
                    'form_params' => $params,
                    'headers' => $headers
                ]);
                break;

            case 'PATCH':
                $response = $this->client->patch($url, [
                    'form_params' => $params,
                    'headers' => $headers
                ]);
                break;                

            case 'DELETE':
                $response = $this->client->delete($url, [
                    'form_params' => $params,
                    'headers' => $headers
                ]);
                break;
        }

        return $response;
    }
}