<?php

namespace App\Http\Controllers;


use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Handle user registration.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email|max:191',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate a token for the newly registered user
        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token, $user);
    }

    /**
     * Handle user login and return a JWT token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('api')->attempt($credentials)) {
            $user = Auth::guard('api')->user();
            Log::info('User authenticated: ' . $user->email);

            $token = auth('api')->login($user);
            return $this->respondWithToken($token, $user);
        } else {
            Log::warning('Authentication failed for email: ' . $request->email);
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }




    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken();  // Get the current token from the header
            Log::info('Token received:', ['token' => $token]); // Log the token
            JWTAuth::invalidate($token);   // Invalidate the token
            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            Log::error('Logout failed:', ['error' => $e->getMessage()]); // Log the error
            return response()->json(['error' => 'Failed to log out'], 500);
        }
    }


    /**
     * Refresh the token.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function refresh()
    {
        try {
            $newToken = auth()->refresh();
            return $this->respondWithToken($newToken);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not refresh token'], 500);
        }
    }

    protected function respondWithToken($token, $user = null)
    {
        $ttl = auth('api')->factory()->getTTL();
        Log::info('Generated Token: ' . $token);
        Log::info('Token Expiry Time (TTL): ' . ($ttl * 60));

        $response = response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl * 60,
            'user' => $user ?? auth('api')->user(),
        ]);

        // Convert response data to an array before logging
        Log::info('Response Body:', (array) $response->getData());
        return $response;
    }
}
