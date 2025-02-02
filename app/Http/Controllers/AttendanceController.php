<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Leave;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\AttendancesExport;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
    
        // Get users who are on leave today
        $usersOnLeave = Leave::where('status', 'approved')
                             ->whereDate('start_date', '<=', $today)
                             ->whereDate('end_date', '>=', $today)
                             ->pluck('user_id');
    
        // Get users who have not scanned today and are not on leave
        $query = User::whereDoesntHave('attendances', function ($query) use ($today) {
            $query->whereDate('date', $today);
        })->whereNotIn('id', $usersOnLeave);
    
        // Search by Name (if provided)
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->input('search_name') . '%');
        }
    
        // Search by Department (if provided)
        if ($request->filled('department')) {
            $query->where('department', $request->input('department'));
        }

        if ($request->filled('work_type')) {
            $query->where('work_type', $request->input('work_type'));
        }
    
        // Retrieve the filtered users
        $users = $query->get();
    
        return view('attendance.index', compact('users'));
    }
    

    public function update(Request $request)
    {
        $today = Carbon::today();
        $userIds = $request->input('user_ids', []);

        foreach ($userIds as $userId) {
            $attendance = Attendance::updateOrCreate(
                ['user_id' => $userId, 'date' => $today],
                ['scanned_at' => Carbon::now(), 'status_scanned' => 1]
            );

            // Ensure at least one scanned_at is not null and status_scanned is 1
            if ($attendance->scanned_at !== null) {
                $attendance->status_scanned = 1;
                $attendance->save();
            }
        }

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
    }

    public function exportPDF()
    {
        $today = Carbon::today();
        $attendances = Attendance::whereDate('date', $today)->with('user')->get();

        $pdf = Pdf::loadView('attendance.pdf', compact('attendances'));
        return $pdf->download('attendances.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new AttendancesExport, 'attendances.xlsx');
    }
}