<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ClientRegisterRequest;


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
        //check if the key is register to check the validation for registration.
        if ($key === 'register') {
            app(RegisterRequest::class);
        }
        //check if the key is login to login to check the validation for login.
        if ($key === 'login') {
            app(LoginRequest::class);
        }
        //check if the key is client to check the details and date.
        if ($key === 'client') {
            app(ClientRegisterRequest::class);
        }

        return $next($request);
    }
}
