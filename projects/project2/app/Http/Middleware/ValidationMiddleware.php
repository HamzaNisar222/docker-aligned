<?php

namespace App\Http\Middleware;

use App\Http\Requests\subadminRequest;
use Closure;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class ValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $key)
    {
        if ($key === 'register') {
            // dd($request->all());
            app(RegisterRequest::class);
        }
        if($key === 'login'){
            app(LoginRequest::class);
        }

        if($key === 'subadmin'){
            app(subadminRequest::class);
        }

        return $next($request);
    }
}
