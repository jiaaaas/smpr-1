<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Leave;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Mail\QrReminderEmail;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // Check if it's past 9 AM
        if ($now->hour >= 9) {
            // Get users who have NOT scanned the QR code before 9 AM
            $users = User::whereDoesntHave('attendances', function ($query) {
                $query->whereDate('created_at', Carbon::today())
                      ->whereTime('created_at', '<', '09:00:00');
            })->get();
    
            // Send email to each user
            foreach ($users as $user) {
                Mail::to($user->email)->send(new QrReminderEmail($user));
            }
        }
        
        // Total leave applications
        $totalLeaveApplication = Leave::where('status', 'pending')->count();

        // Total absences for the day
        $today = Carbon::today();
        $totalAbsent = User::whereDoesntHave('attendances', function ($query) use ($today) {
            $query->whereDate('created_at', $today)
                  ->whereTime('created_at', '<=', '11:00:00')
                  ->where('status_scanned', 0);
        })->whereDoesntHave('leaves', function ($query) use ($today) {
            $query->where('status', 'approved')
                  ->whereDate('start_date', '<=', $today)
                  ->whereDate('end_date', '>=', $today);
        })->count();

        // Total users who are late and have not yet scanned the QR code
        $totalLate = Attendance::whereDate('date', $today)
                               ->whereTime('scanned_at', '>', '09:00:00')
                               ->count();

        // Total users who are present
        $totalPresent = Attendance::whereDate('date', $today)
                                  ->whereTime('scanned_at', '<=', '09:00:00')
                                  ->count();

        $attendances = Attendance::whereDate('date', $today)->with('user')->get();

        
        // Fetch attendance data for the past week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weeklyAttendance = Attendance::whereBetween('date', [$startOfWeek, $endOfWeek])
                                      ->get()
                                      ->groupBy(function($date) {
                                          return Carbon::parse($date->date)->format('l'); // grouping by days
                                      });

        $attendanceData = [];
        foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day) {
            $date = Carbon::parse($startOfWeek)->next($day)->format('Y-m-d');
            $attendancesForDay = $weeklyAttendance->get($day, collect());
            $attendanceData[$day] = [
                'date' => $date,
                'present' => $attendancesForDay->where('scanned_at', '<=', '09:00:00')->count(),
                'late' => $attendancesForDay->where('scanned_at', '>', '09:00:00')->count(),
                'absent' => $attendancesForDay->where('scanned_at', null)->count(),
                'total' => $attendancesForDay->count(),
            ];
        }

        // Calculate average performance for the month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $monthlyAttendance = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

        $totalEmployees = User::count();
        $totalDaysInMonth = Carbon::now()->daysInMonth;

        $totalAttendanceRecords = $monthlyAttendance->count();
        $totalLateRecords = $monthlyAttendance->where('scanned_at', '>', '09:00:00')->count();
        $totalAbsentRecords = ($totalEmployees * $totalDaysInMonth) - $totalAttendanceRecords;

        $averagePerformance = 0;
        if ($totalEmployees > 0 && $totalDaysInMonth > 0) {
            $averagePerformance = round((($totalAttendanceRecords - $totalLateRecords) / ($totalEmployees * $totalDaysInMonth)) * 100, 2);
        }

        return view('dashboard', compact('totalLeaveApplication', 'totalAbsent', 'totalLate', 'totalPresent', 'attendances', 'attendanceData', 'averagePerformance'));
    }

    public function getAbsentUsers()
    {
        $today = Carbon::today();
        $absentUsers = User::whereDoesntHave('attendances', function ($query) use ($today) {
            $query->whereDate('created_at', $today)
                ->whereTime('created_at', '<=', '11:00:00');
        })->whereDoesntHave('leaves', function ($query) use ($today) {
            $query->where('status', 'approved')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today);
        })->get();

        return response()->json($absentUsers);
    }


    public function getLeaveApplications()
    {
        $leaveApplications = Leave::with('user')->where('status', 'pending')->get();

        return response()->json($leaveApplications);
    }

}