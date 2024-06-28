<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogsRequestResponse
{
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

        $this->logToDatabase($request, $response);

        return $response;
    }

    // creted middleware to generate the logs and save it to MongoDB
    protected function logToDatabase(Request $request, $response) {

        // this is the log data which will be stored in MongoDB
        $logData = [
            'user_type' => $this->getUserTypeFromResponse($response),
            'user_id' => $this->getUserIdFromResponse($response),
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'response_status' => $response->getStatusCode(),
            'response_content' => $response->getContent(),
            'level' => 'info',
            'ip' => $request->ip(),
        ];
        Log::logToMongoDB($logData);
    }

    // check if the log generated for the authenticate User or the guest user.
    protected function getUserTypeFromResponse(Response $response)
    {
        // get the content from response to decode to get the role of the user
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        if (isset($decoded['user']) && isset($decoded['user']['role'])) {
            if ($decoded['user']['role'] === 'client') {
                return 'client';
            }
        }
        // Fallback if user type is store in ApiToken with types is not found.
        return 'guest';
    }

    // get the content from response to decode to get the id of the user
    protected function getUserIdFromResponse(Response $response)
    {
        $content = $response->getContent();
        $decoded = json_decode($content, true);

        if (isset($decoded['user']) && isset($decoded['user']['id'])) {
            return $decoded['user']['id'];
        }
        // Fallback if user ID is not found
        return null;
    }
}
