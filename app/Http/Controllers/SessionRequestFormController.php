<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\SessionRequestForm;
use Illuminate\Support\Facades\Validator;


class SessionRequestFormController extends Controller
{
    
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

    public function show(SessionRequestForm $sessionRequestForm)
    {
       return $sessionRequestForm;
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


    public function destroy()
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
        'status' => 'required|in:approved,rejected',
    
    ]);

    // Update the session request form status
    $sessionRequestForm->status = $validatedData['status'];
    $sessionRequestForm->save();

    // Creating a session upon approval
    if ($sessionRequestForm->status === 'approved') {

        Session::create([
            'repetition_status' => 'pending',
            'repetition_period' => $sessionRequestForm->repetition_period,
            'session_status' => 'approved',
            'session_request_form_id' => $sessionRequestForm->id,
            'student_id' => $sessionRequestForm->student, 
        ]);

        return response()->json(['message' => 'Session request form approved and session created successfully.'], 201);
    }   
        return response()->json(['message' => 'Session request form rejected.'], 200);
   }
}