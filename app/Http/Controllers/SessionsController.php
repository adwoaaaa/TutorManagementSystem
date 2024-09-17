<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sessions;
use Illuminate\Support\Facades\Validator;

class SessionsController extends Controller
{
    public function index()
    {
        $sessions = Session::all();

        if ($sessions->isEmpty()) {
            return response()->json([
                'message' => 'No sessions available'
            ], 200);
        }

        return response()->json($sessions, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repetition_status' => 'required|string|max:255',
            'repetition_period' => 'nullable|integer',
            'session_status' => 'required|string|max:255',
            'session_request_form_id' => 'required|uuid|exists:session_request_forms,session_id',
            'student_id' => 'required|uuid|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        } else {
            $session = Session::create($request->all());

            return response()->json([
                'message' => 'Session created successfully',
                'data' => $session
            ], 201);
        }
    }

    public function update(Request $request, $id)
    {
        $session = Session::find($id);

        if (!$session) {
            return response()->json([
                'message' => 'Session not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'repetition_status' => 'required|string|max:255',
            'repetition_period' => 'nullable|integer',
            'session_status' => 'required|string|max:255',
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
                'message' => 'Updated Successfully',
                'data' => $session
            ], 200);
        }
    }
        public function destroy($id)
    {
        $session = Session::find($id);

        if (!$session) {
            return response()->json([
                'message' => 'Session not found'
            ], 404);
        }

        $session->delete();

        return response()->json([
            'message' => 'Session successfully deleted'
        ], 200);
    }
}
