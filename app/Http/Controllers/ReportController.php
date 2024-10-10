<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //Create a new report
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string', 
        ]);

        $studentId = Auth::user()->id;

        $report = Report::create([
            'student' => $studentId,
            'message' => $request->message,
        ]);

        return response()->json($report, 201);
    }

    public function index()
    {
        $reports = Report::with('student:id,email,phoneNumber')->get();
        return response()->json($reports);
    }

    public function show($id)
    {
        $report = Report::with('student:id,email,phoneNumber')->findOrFail($id);
        return response()->json($report);
    }
}
