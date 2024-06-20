<?php

namespace App\Logging;

use MongoDB\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MongoDBLogger
{
    public function __invoke(array $config) {
        $client = new Client('mongodb://mongo:27017');
        $collection = $client->selectCollection('multivendor', 'laravel_logs');
        // Log the incoming request
        $this->logToMongoDB($collection, $request);

        // Log the outgoing response
        $this->logResponse($collection, $response);

        return $response;
    }

    protected function logRequest($collection, Request $request) {
        $collection->insertOne([
                'method' => $request->getMethod(),
                'url' => $request->fullUrl(),
                'headers' => $request->headers->all(),
                'body' => $request->all(),
                'ip' => $request->ip(),
        ]);
    }

    protected function logResponse($collection, Response $response) {
        $collection->insertOne([
            'status' => $response->getStatusCode(),
            'headers' => $response->headers->all(),
            'body' => $response->getContent(),
        ]);
    }

}


