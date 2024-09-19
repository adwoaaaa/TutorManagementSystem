<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'lastName' => 'required|string|max:255',
            'otherNames' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phoneNumber' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $role = 'student';

        // Create the user
        try {
            $user = User::create([
                'id' => (string) Str::uuid(),
                'lastName' => $request->lastName,
                'otherNames' => $request->otherNames,
                'email' => $request->email,
                'phoneNumber' => $request->phoneNumber,
                'password' => Hash::make($request->password),
                'role' => $role,
            ]);

            return response()->json([
                'message' => 'Student registered successfully!',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error during registration: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|string|email',
    //         'password' => 'required|string|min:8',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $credentials = $request->only('email', 'password');

    //     try {
    //         if (!$token = JWTAuth::attempt($credentials)) {
    //             return response()->json(['error' => 'Unauthorized'], 401);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Could not create token', 'message' => $e->getMessage()], 500);
    //     }

    //     return $this->respondWithToken($token);
    // }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

    // Getting the user from JWT token
        $user = JWTAuth::user();

    // Checking if the user role is student
        if ($user->role !== 'student') {
            return response()->json(['error' => 'Unauthorized! Only students can login here'], 401);
        }

       return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        $ttl = config('jwt.ttl', 60); // Default to 60 minutes if not set

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl * 60, // Convert minutes to seconds
        ]);
    }


    public function logout(Request $request)
    {
        try {
        JWTAuth::invalidate(JWTAuth::parseToken());
        return response()->json(['message' => 'Successfully logged out'], 200);
        
      } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to logout'], 500);
      }
    }
}
