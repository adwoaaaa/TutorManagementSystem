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

        // Checking if the users' role
        if ($user->role === 'student') {

            return response()->json([
                'message' => 'Student login successful',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60,
            ], 200);
        } elseif ($user->role === 'administrator') {

            return response()->json([
                'message' => 'Administrator login successful',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60,
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized.', 401]);
        }
    }

    /*
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
        */

     public function getAllStudents(Request $request)
    {
        // Fetch all students from the database
        $students = User::where('role', 'student')->get(); 
    
        return response()->json($students);
    }


    
    public function update(Request $request)
    {
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'lastName' => 'sometimes|string|max:255',
            'otherNames' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . auth()->user()->id,
            'phoneNumber' => 'sometimes|string|max:20',
            'password' => 'sometimes|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get the authenticated user
        $user = JWTAuth::user();

        // Update the user details
        $user->lastName = $request->get('lastName', $user->lastName);
        $user->otherNames = $request->get('otherNames', $user->otherNames);
        $user->email = $request->get('email', $user->email);
        $user->phoneNumber = $request->get('phoneNumber', $user->phoneNumber);
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'message' => 'User details updated successfully',
            'user' => $user
        ], 200);
    }

    public function deleteStudent($id)
    {
        // Get the authenticated user (admin)
        $admin = JWTAuth::user();

        // Check if the user is an administrator
        if ($admin->role !== 'administrator') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Find the student by ID
        $student = User::where('id', $id)->where('role', 'student')->first();

        // If student does not exist
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Delete the student account
        $student->delete();

        return response()->json([
            'message' => 'Student account deleted successfully'
        ], 200);
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
