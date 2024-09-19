<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sessions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Ensure all methods require authentication
        $this->middleware('administrator')->only(['store']); // Admins can access this method
    }
 
    public function index()
    {
        $sessions = Sessions::all();

        if ($sessions->isEmpty()) {
            return response()->json([
                'message' => 'No sessions available.'
            ], 200);
        }

        return response()->json($sessions, 200);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Not allowed. Sessions are created automatically upon approval of session requests.'], 403);
    }

    public function show($id)
    {
        $session = Sessions::find($id);

        if (!$session) {
            return response()->json([
                'message' => 'Session not found'
            ], 404);
        }

        return response()->json($session, 200);
    }


    public function update(Request $request, $id)
    {
        $session = Sessions::find($id);

        if (!$session) {
            return response()->json([
                'message' => 'Session not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'session_request_form_id' => 'required|uuid|exists:session_request_forms,session_id',
            'student_id' => 'required|uuid|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        } else {
            $session->update($request->all());

            return response()->json([
                'message' => 'Updated Successfully.',
                'data' => $session
            ], 200);
        }
    }
        public function destroy($id)
    {
        $session = Sessions::find($id);

        if (!$session) {
            return response()->json([
                'message' => 'Session not found'
            ], 404);
        }

        $session->delete();

        return response()->json([
            'message' => 'Session successfully deleted.'
        ], 200);
    }
}
