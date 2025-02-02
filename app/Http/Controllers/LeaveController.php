<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Leave::with('user');

        // Search by User Name (if provided)
        if ($request->filled('search_name')) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('search_name') . '%');
            });
        }

        // Search by Leave Type (if provided)
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->input('leave_type'));
        }

        // Search by Status (if provided)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $leaves = $query->get();
        return view('leaves.index', compact('leaves'));
    }

    
    public function updateStatus(Request $request, Leave $leave)
    {
        $leave->status = $request->status;
        $leave->save();

        return redirect()->route('leaves.index')->with('success', 'Leave status updated successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
