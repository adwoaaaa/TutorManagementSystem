<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['index', 'show']); // Ensure all methods require authentication
    }

    //Create a new report
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'required|string',
        ]); 

        $report = Report::create([
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Report submitted successfully',
            'data' => $report
        ], 201);
    }
    

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'administrator') {
            $reports = Report::all();

            if ($reports->isEmpty()) {
                return response()->json(['message' => 'No reports found.'], 404);
            }

            return response()->json($reports);
        }


        // Students can only view their specific reports
        if ($user->role === 'student') {
            $studentReports = Report::where('email', $user->email)->get();

            if ($studentReports->isEmpty()) {
                return response()->json(['message' => 'No reports found.'], 404);
            }

            return response()->json($studentReports);
        }


        return response()->json(['error' => 'Unauthorized'], 403);
    }
       
      

    public function show($id)
    {
        $report = Report::findOrFail($id);  // Find the report by ID or return 404
        $user = Auth::user();

        if ($user->role === 'administrator') {
            return response()->json($report);
        }

          // Students can only view their own specific reports
          if ($user->role === 'student' && $report->email === $user->email) {
            return response()->json($report);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
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