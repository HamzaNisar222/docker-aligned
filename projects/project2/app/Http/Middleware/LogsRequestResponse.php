<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use MongoDB\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogsRequestResponse
{
    protected $client;
    protected $collection;

    public function __construct() {
        $this->client = new Client(env('MONGO_DB_CONNECTION'));
        $this->collection = $this->client->selectCollection(env('MONGO_DB_DATABASE'), env('LOG_COLLECTION'));
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $this->logToMongoDB($request, $response);

        return $response;
    }

    protected function logToMongoDB(Request $request, Response $response) {

        $logLevel = config('logging.mongo_log_level', 'info');
        if ($logLevel === 'info') {
            $this->logRequest($request);
            $this->logResponse($response);
        }

        if ($logLevel === 'error' && $response->getStatusCode() >=400) {
            $this->logResponse($response);
        }
    }

    protected function logRequest(Request $request) {
        $this->collection->insertOne([
            'type' => 'request',
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'ip' => $request->ip(),
            'level' => 'info',
        ]);

    }
    protected function logResponse(Response $response) {
        $this->collection->insertOne([
            'type' => 'response',
            'status' => $response->getStatusCode(),
            'headers' => $response->headers->all(),
            'body' => $response->getContent(),
            'level' => 'info',
        ]);
    }

    // protected function logError($error) {
    //     $this->collection->insertOne([
    //         'type' => 'error',
    //         'error' => $error,
    //         'level' => 'error',
    //     ]);
    // }
}
