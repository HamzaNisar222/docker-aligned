<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\ApiToken;
use Illuminate\Http\Request;
use App\Jobs\SendConfirmationEmail;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Create user record
        $user = User::createUser($request->all());
        // Generate signed URL for email confirmation
        $confirmationUrl = URL::temporarySignedRoute('register.confirm', Carbon::now()->addMinutes(1), ['token' => $user->confirmation_token]);
        dispatch(new SendConfirmationEmail($user, $confirmationUrl));

        return Response::success('user created successfully. Verify your email', 201);

    }
    public function confirmEmail(Request $request, $token)
    {
        // If no user found or token expired, redirect or respond accordingly
        if (!$request->hasValidSignature()) {
            return Response::error('Invalid or expired token.', 401);
        }
        // Find the user by confirmation token
        $user = User::where('confirmation_token', $token)->first();

        // Activate the user
        $user->status = 1; // Active
        $user->confirmation_token = null;
        $user->email_verified_at = now();
        $user->save();

        return Response::success("Email Verified Successfully", 200);
    }
    // Client Login
    public function login(Request $request)
    {
        $user = User::authenticate($request->email, $request->password);
        // dd($request->user);
        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $ipAddress = $request->ip();
        $expiresIn = $request->has('remember') ? 24 * 7 : 1;
        $apiToken = ApiToken::createToken($user, $ipAddress, $expiresIn);

        return response()->json(['token' => $apiToken->token, 'user' => $user]);
    }

    public function logout(Request $request)
    {

        $token = $request->header('Authorization');

        $token = substr($token, 7);
        $apiToken = ApiToken::where('token', $token)->first();

        if ($apiToken) {
            // Token exists, delete it
            $apiToken->delete();
        } else {
            // Token doesn't exist, prompt the user to login first
            return response()->json(['error' => 'Please login first'], 401);
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}
