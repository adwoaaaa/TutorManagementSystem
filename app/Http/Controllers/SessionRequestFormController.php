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
        $this->middleware('auth:api')->except(['show']);
        $this->middleware('student')->only([ 'store', 'update', 'destroy']);
        $this->middleware('administrator')->only(['index', 'approve', 'reject']);
    }


    public function index(Request $request)
    {
       
        $status = $request->input('status');
        $search = $request->input('search');

        $query = SessionRequestForm::query();

        if ($status){
            $query->where('session_status', $status);
        }

        if ($search){
            $query->whereHas('student', function($bring) use ($search) {
                $bring->where('lastName', 'like', '%' . $search . '%')->orWhere('otherNames', 'like', '%' . $search . '%');
            });
        }

       $sessionRequests = $query->get();

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
            'repetition_period' => 'nullable|integer',
           // 'session_status' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'student' => 'required|uuid|exists:users,id',  // Foreign key check
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);

        } else {
            $sessionRequest = SessionRequestForm::create(array_merge($request->all(), ['session_status' => 'pending',]));

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
            'repetition_period' => 'nullable|integer',
        //  'session_status' => 'required|string|max:255',
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
    
        // Check if the session request form is already approved
        if ($sessionRequestForm->session_status === 'approved') {
            return response()->json(['message' => 'Session request form is already approved.'], 200);
        }
    
        // Update the session request form status to approved
        $sessionRequestForm->session_status = 'approved';
        $sessionRequestForm->save();
    
        // Check if a session already exists for this request
        $existingSession = Sessions::where('session_request_form_id', $sessionRequestForm->id)->first();
    
        if (!$existingSession) {
            // Creating a session upon approval
            $session = Sessions::create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'session_status' => 'approved',
                'session_request_form_id' => $sessionRequestForm->id,
            ]);
        
        // Eager loading student info
            $session = Sessions::with('sessionRequestForm.student')->find($session->id);

    
            return response()->json(['message' => 'Session request form approved and session created successfully.', 'session' => $session], 201);
        } else {
            return response()->json(['message' => 'Session request form approved, but a session already exists.'], 200);
        }
    }


    public function reject(Request $request, $id)
   {
    // Find the session request by ID
    $sessionRequest = SessionRequestForm::findOrFail($id);
    
    // Find the session associated with this request using the correct foreign key
    $session = Sessions::where('session_request_form_id', $sessionRequest->id)->first();

    // Reject the session request
    $sessionRequest->session_status = 'rejected';
    $sessionRequest->save();

    // Delete the session if it exists
    if ($session) {
        $session->delete();
    }

    return response()->json([
        'message' => 'Session request rejected and associated session deleted.'
    ], 200);
    }
}