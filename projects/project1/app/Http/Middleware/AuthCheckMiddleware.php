<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ApiToken;
use Illuminate\Http\Request;

class AuthCheckMiddleware
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
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader) {
            return response()->json(['error' => 'Unauthorized token'], 401);
        }

        // Remove "Bearer " prefix
        $token = substr($authorizationHeader, 7);

        $apiToken = ApiToken::where('token', $token)
                            ->where('expires_at', '>', now())
                            ->first();

        if (!$apiToken) {
            return response()->json(['error' => 'Unauthorized user'], 401);
        }

        // Attach authenticated user/admin to the request
        $tokenable = $apiToken->tokenable;
        // dd($tokenable);
        $request->merge(['user' => $tokenable]);

        return $next($request);
    }
}
