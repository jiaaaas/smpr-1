<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReport;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Leave;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PerformanceReportExport;

class PerformanceReportController extends Controller
{
    public function index()
    {
        $employees = User::all();
        return view('performanceReport.index', compact('employees'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required',
            'year' => 'required|integer',
            'month' => 'required_if:report_type,month|integer|nullable',
            'employee_selection' => 'required',
            'employee_ids' => 'array|nullable|required_if:employee_selection,selected',
        ]);

        $report = new PerformanceReport();
        $report->report_type = $request->report_type;
        $report->year = $request->year;
        $report->month = $request->month;
        $report->employee_ids = $request->employee_ids ? json_encode($request->employee_ids) : null;
        $report->save();

        return redirect()->route('performanceReport.show', $report->id)->with('success', 'Performance report generated successfully.');
    }

    public function show($id)
    {
        $report = PerformanceReport::findOrFail($id);
        $employees = $report->employee_ids ? User::whereIn('id', json_decode($report->employee_ids))->get() : User::all();

        $performanceData = [];
        foreach ($employees as $employee) {
            $query = Attendance::where('user_id', $employee->id)
                ->whereYear('date', $report->year);

            if ($report->report_type == 'month') {
                $query->whereMonth('date', $report->month);
            }

            $totalDays = $report->report_type == 'month' ? Carbon::create($report->year, $report->month)->daysInMonth : Carbon::create($report->year)->daysInYear;
            $presentDays = $query->where('status_scanned', 1)->count();
            $lateDays = $query->where('status_scanned', 1)->whereTime('scanned_at', '>', '09:00:00')->count();

            // Calculate leave/WFH days
            $leaveQuery = Leave::where('user_id', $employee->id)
                ->whereYear('start_date', $report->year);

            if ($report->report_type == 'month') {
                $leaveQuery->whereMonth('start_date', $report->month);
            }

            $leaveDays = $leaveQuery->count();

            // Calculate absent days by subtracting leave days from total days
            $absentDays = $totalDays - $presentDays - $leaveDays;

            $performanceData[$employee->id] = [
                'total' => $totalDays,
                'present' => $presentDays,
                'late' => $lateDays,
                'absent' => $absentDays,
                'leave' => $leaveDays,
            ];
        }

        return view('performanceReport.show', compact('report', 'employees', 'performanceData'));
    }

    public function calculateAveragePerformance(Request $request)
    {
        $reportType = $request->input('report_type');
        $year = $request->input('year');
        $month = $request->input('month');
        $employeeIds = $request->input('employee_ids');

        $query = Attendance::whereYear('date', $year);

        if ($reportType == 'month') {
            $query->whereMonth('date', $month);
        }

        if ($employeeIds) {
            $query->whereIn('user_id', $employeeIds);
        }

        $totalDays = $reportType == 'month' ? Carbon::create($year, $month)->daysInMonth : Carbon::create($year)->daysInYear;
        $presentDays = $query->where('status_scanned', 1)->count();
        $lateDays = $query->where('status_scanned', 1)->whereTime('scanned_at', '>', '09:00:00')->count();

        // Calculate leave/WFH days
        $leaveQuery = Leave::whereYear('start_date', $year);

        if ($reportType == 'month') {
            $leaveQuery->whereMonth('start_date', $month);
        }

        if ($employeeIds) {
            $leaveQuery->whereIn('user_id', $employeeIds);
        }

        $leaveDays = $leaveQuery->count();

        $absentDays = $totalDays - $presentDays - $leaveDays - $lateDays;
        $percentagePerformance = $totalDays > 0 ? (($presentDays + $lateDays) / $totalDays) * 100 : 0;
    
        $averagePerformance = [
            'total' => $totalDays,
            'present' => $presentDays,
            'late' => $lateDays,
            'absent' => $absentDays,
            'leave' => $leaveDays,
            'percentage_performance' => $percentagePerformance,
        ];

        return response()->json($averagePerformance);
    }

    public function exportPDF($id)
    {
        $report = PerformanceReport::findOrFail($id);
        $employees = $report->employee_ids ? User::whereIn('id', json_decode($report->employee_ids))->get() : User::all();

        $performanceData = [];
        foreach ($employees as $employee) {
            $query = Attendance::where('user_id', $employee->id)
                ->whereYear('date', $report->year);

            if ($report->report_type == 'month') {
                $query->whereMonth('date', $report->month);
            }

            $totalDays = $report->report_type == 'month' ? Carbon::create($report->year, $report->month)->daysInMonth : Carbon::create($report->year)->daysInYear;
            $presentDays = $query->where('status_scanned', 1)->count();
            $lateDays = $query->where('status_scanned', 1)->whereTime('scanned_at', '>', '09:00:00')->count();

            // Calculate leave/WFH days
            $leaveQuery = Leave::where('user_id', $employee->id)
                ->whereYear('start_date', $report->year);

            if ($report->report_type == 'month') {
                $leaveQuery->whereMonth('start_date', $report->month);
            }

            $leaveDays = $leaveQuery->count();

            // Calculate absent days by subtracting leave days from total days
            $absentDays = $totalDays - $presentDays - $leaveDays;

            $performanceData[$employee->id] = [
                'total' => $totalDays,
                'present' => $presentDays,
                'late' => $lateDays,
                'absent' => $absentDays,
                'leave' => $leaveDays,
            ];
        }

        $pdf = Pdf::loadView('performanceReport.pdf', compact('report', 'employees', 'performanceData'));
        return $pdf->download('performance_report.pdf');
    }

    public function exportExcel($id)
    {
        return Excel::download(new PerformanceReportExport($id), 'performance_report.xlsx');
    }
}