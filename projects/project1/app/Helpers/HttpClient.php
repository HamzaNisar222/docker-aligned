<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpClient
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function sendPostRequest($url, $headers = [], $body = [], $timeout = 30)
    {
        try {
            $response = $this->client->post($url, [
                'headers' => array_merge($this->client->getConfig('headers'), $headers),
                'json' => $body,
                'timeout' => $timeout,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function sendGetRequest($url, $headers = [], $timeout = 30)
    {
        try {
            $response = $this->client->get($url, [
                'headers' => array_merge($this->client->getConfig('headers'), $headers),
                'timeout' => $timeout,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
