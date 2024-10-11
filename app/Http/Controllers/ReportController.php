<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Ensure all methods require authentication
    }

    //Create a new report
    public function store(Request $request)
    {
        // ensuring only students can create reports
        if (Auth::user()->role !== 'student') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string', 
        ]);

        $studentId = Auth::user()->id;

        $report = Report::create([
            'student' => $studentId,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Report created successfully.',
        'data' => $report], 201);
    }

    public function index()
    {
          // Ensuring only admins can view all reports
        if (Auth::user()->role === 'administrator'){
        $reports = Report::with('student:id,email,phoneNumber')->get();

        if ($reports->isEmpty()) {
            return response()->json(['message' => 'No reports available in the system'], 404);
        }

             return response()->json($reports);
        }
        
      // Allowing students to view only their personal reports
    if (Auth::user()->role === 'student') {
        $studentId = Auth::user()->id;
        $reports = Report::where('student', $studentId)->with('student:id,email,phoneNumber')->get();
        
        if ($reports->isEmpty()) {
            return response()->json(['message' => 'No reports have been made yet.'], 404);
        }

        return response()->json($reports);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
          
    }

    public function show($id)
    {
        $report = Report::with('student:id,email,phoneNumber')->findOrFail($id);

        // Ensuring students can view only their specific reports
        if (Auth::user()->role === 'student' && $report->student !== Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$report) {
            return response()->json([
                'message' => 'Report not found'
            ], 404);
        }

        return response()->json($report);
    }

    /*
    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        // Ensure only the student who created the report or an admin can delete it
        if (Auth::user()->role === 'student' && $report->student !== Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $report->delete();

        // Return success message upon deletion
        return response()->json([
            'message' => 'Report deleted successfully!',
        ]);
    }
        */
}