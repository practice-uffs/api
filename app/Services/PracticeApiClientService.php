<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Auth\CredentialManager;
use App\Models\App;

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
        $user = (array) auth()->user();
        $tokenTtl = $this->config['token_ttl_seconds'];
        
        $bearerToken = $this->credentialManager->createPassportFromApp($app, $user, $tokenTtl);

        return [
            'Authorization' => 'Bearer ' . $bearerToken
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

    public function fetch($appId, $verb, $route, array $params = [], array $headers = []) 
    {
        $app = App::findOrFail($appId);

        $headers = array_merge($this->appRequestHeaders($app), $headers);
        $route = $this->appRoute($app, $route);

        switch(strtoupper($verb)) {
            case 'GET':
                $response = $this->client->get($route, [
                    'query' => $params,
                    'headers' => $headers
                ]);
                break;

            case 'POST':
                $response = $this->client->post($route, [
                    'form_params' => $params,
                    'headers' => $headers
                ]);
                break;

            case 'PUT':
                $response = $this->client->put($route, [
                    'form_params' => $params,
                    'headers' => $headers
                ]);
                break;

            case 'PATCH':
                $response = $this->client->patch($route, [
                    'form_params' => $params,
                    'headers' => $headers
                ]);
                break;                

            case 'DELETE':
                $response = $this->client->delete($this->config['api_url'] . $route, [
                    'form_params' => $params,
                    'headers' => $headers
                ]);
                break;
        }

        return $response;
    }

    public function get($appId, $route, array $params = [], array $headers = [])
    {
        return $this->fetch($appId, 'GET', $route, $params, $headers);
    }

    public function post($appId, $route, array $params = [], array $headers = [])
    {
        return $this->fetch($appId, 'POST', $route, $params, $headers);
    }

    public function patch($appId, $route, array $params = [], array $headers = [])
    {
        return $this->fetch($appId, 'PATCH', $route, $params, $headers);
    }

    public function delete($appId, $route, array $params = [], array $headers = [])
    {
        return $this->fetch($appId, 'DELETE', $route, $params, $headers);
    }    
}