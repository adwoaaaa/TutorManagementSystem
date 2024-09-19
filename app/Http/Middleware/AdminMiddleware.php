<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = JWTAuth::parseToken()->authenticate();
    
        // Chceking whether or not authenticated user is an admin

        if ($user && $user->role === 'administrator') {
            return $next($request);   
        }
        return response()->json(['error' => 'Unauthorized! Only administrators can access this route.'], 403);
    }
}
