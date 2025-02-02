<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function leave_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required', // Validate user ID
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        // Retrieve all leave applications for the user
        $leaves = Leave::where('user_id', $request->user_id)->get();

        if ($leaves->isEmpty()) {
            return response()->json(['message' => 'No leave applications found for this user.'], 404);
        }

        return response()->json([
            'leaves' => $leaves,
        ]);
    }

    public function leave_form(Request $request)
    {
        // Validate the incoming JSON request
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'leave_type' => 'required|string|max:255',
            'leave_reasons' => 'required|string',
            'user_id' => 'required', // Validate user ID
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Create a new leave application
        $leave = Leave::create([
            'user_id' => $request->user_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'leave_type' => $request->leave_type,
            'leave_reasons' => json_encode($request->leave_reasons), // Encode leave_reasons as JSON
            'status' => 'pending', // Default status for a new leave application
        ]);

        // Return a success response
        return response()->json([
            'message' => 'Leave application submitted successfully.',
            'leave' => $leave,
        ], 201);
    }
}