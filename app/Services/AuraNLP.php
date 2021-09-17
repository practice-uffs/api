<?php

namespace App\Services;

class AuraNLP
{
    protected $config;
    protected $client;

    protected function client()
    {
        $guzzClient = new \GuzzleHttp\Client([
            \GuzzleHttp\RequestOptions::VERIFY => \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath()
        ]);

        return $guzzClient;
    }

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->client = $this->client();
    }

    public function fetch($route, $text)
    {
        $url = $this->config['api_url'] . "/$route/" . rawurlencode($text);
        $response = $this->client->get($url);

        return json_decode($response->getBody()->getContents(), true);
    }
}