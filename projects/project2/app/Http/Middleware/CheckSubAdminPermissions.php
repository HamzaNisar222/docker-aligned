<?php
// app/Http/Middleware/CheckAdminPermissions.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSubAdminPermissions
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user;

        if (!$user) {
            return response()->json(['error' => 'Unauthorized from'], 401);
        }

        // Retrieve and decode permissions JSON
        $permissions = $user->permissions;


        // Check if the decoded permissions array contains the required permission

        if (collect($permissions)->contains($permission)) {
            return $next($request);
        }

        return response()->json(['error' => 'Insufficient permissions'], 403);
    }
}
