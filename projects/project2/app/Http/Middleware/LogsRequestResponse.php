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

    protected function logToDatabase(Request $request, $response) {

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
    protected function getUserTypeFromResponse(Response $response)
    {
        $content = $response->getContent();
        $decoded = json_decode($content, true);
        // dd($decoded);
        if (isset($decoded['admin']) && isset($decoded['admin']['role'])) {
            if ($decoded['admin']['role'] === 'admin') {
                return 'admin';
            } elseif ($decoded['admin']['role'] === 'subadmin') {
                return 'subadmin';
            }
        } elseif (isset($decoded['user'])) {
            return 'user';
        }

        // Fallback if user type is not found
        return 'guest';
    }

    protected function getUserIdFromResponse(Response $response)
    {
        $content = $response->getContent();
        $decoded = json_decode($content, true);

        if (isset($decoded['admin']) && isset($decoded['admin']['id'])) {
            return $decoded['admin']['id'];
        } elseif (isset($decoded['user'])) {
            return $decoded['user']['id'];
        }

        // Fallback if user ID is not found
        return null;
    }
}
