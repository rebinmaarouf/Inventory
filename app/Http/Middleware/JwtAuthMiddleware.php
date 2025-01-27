<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Attempt to authenticate the user via the JWT token
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            // If there is an error (token missing, invalid, etc.), return a response
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Add the authenticated user to the request object
        $request->merge(['user' => $user]);

        return $next($request);
    }
}
