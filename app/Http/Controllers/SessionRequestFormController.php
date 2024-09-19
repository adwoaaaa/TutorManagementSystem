<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\SessionRequestForm;
use App\Models\Sessions;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class SessionRequestFormController extends Controller
{
    public function __construct()
    {
        // Applying middleware for appropriate roles
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('student')->only([ 'store', 'update', 'destroy']);
        $this->middleware('administrator')->only('approve');
    }


    public function index()
    {
       $sessionRequests = SessionRequestForm::all();

       if ($sessionRequests->isEmpty()){
        return response()->json([
            'message' => 'No session requests yet'
        ], 200);
    }

     return response()->json($sessionRequests, 200);

   }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'level_of_education' => 'required|string|max:255',
            'session_period' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'additional_information' => 'nullable|string',
            'duration' => 'required|string',
            'repetition_period' => 'nullable'|'integer',
            'session_status' => 'required'|'string'|'max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'student' => 'required|uuid|exists:users,id',  // Foreign key check
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);

        } else {
            $sessionRequest = SessionRequestForm::create($request->all());

            return response()->json([
                'message' => 'Session request created successfully',
                'data' => $sessionRequest
            ], 201);
        }
    }

    public function show($id)
    {
        $sessionRequest = SessionRequestForm::find($id);

        if (!$sessionRequest) {
            return response()->json([
                'message' => 'Session request not found'
            ], 404);
        }

        return response()->json($sessionRequest, 200); 
    }


    public function update(Request $request, $id)
    {

        $sessionRequest = SessionRequestForm::find($id);

        if (!$sessionRequest) {
            return response()->json([
                'message' => 'Session request not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'level_of_education' => 'required|string|max:255',
            'session_period' => 'required|string|max:255',
            'venue' => 'required|string|max:255',
            'additional_information' => 'nullable|string',
            'duration' => 'required|string',
            'repetition_period' => 'nullable'|'integer',
            'session_status' => 'required'|'string'|'max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'student' => 'required|uuid|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        } else {
            $sessionRequest->update($request->all());

            return response()->json([
                'message' => 'Updated Successfully',
                'data' => $sessionRequest
            ], 200);
        }
    }


    public function destroy($id)
    {
        $sessionRequest = SessionRequestForm::find($id);

        if (!$sessionRequest) {
            return response()->json([
                'message' => 'Session request not found'
            ], 404);
        }

        $sessionRequest->delete();

        return response()->json([
            'message' => 'Session request successfully deleted'
        ], 200);
    }


    public function approve(Request $request, $id)
    {

    // Finding the session request form by its id
    $sessionRequestForm = SessionRequestForm::findOrFail($id);

    // Performing validation to either accept or reject
    $validatedData = $request->validate([
        'session_status' => 'required|in:approved,rejected',
    
    ]);

    // Update the session request form status
    $sessionRequestForm->session_status = $validatedData['session_status'];
    $sessionRequestForm->save();

    // Creating a session upon approval
    if ($sessionRequestForm->session_status === 'approved') {

        Sessions::create([
            'session_status' => 'approved',
            'session_request_form_id' => $sessionRequestForm->id,
        ]);

        return response()->json(['message' => 'Session request form approved and session created successfully.'], 201);
    }   
        return response()->json(['message' => 'Session request form rejected.'], 200);
   }
}