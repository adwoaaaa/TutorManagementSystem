<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    
    $user = JWTAuth::parseToken()->authenticate();

    // Checking whether or not the authenticated user has the 'student' role

    if ($user && $user->role === 'student') {
        return $next($request);
    }

    return response()->json(['error' => 'Unauthorized! Only students can access this route.'], 403);

    }
}
