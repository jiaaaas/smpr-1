<?php

namespace App\Exports;

use App\Models\PerformanceReport;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PerformanceReportExport implements FromCollection, WithHeadings
{
    protected $reportId;

    public function __construct($reportId)
    {
        $this->reportId = $reportId;
    }

    public function collection()
    {
        $report = PerformanceReport::findOrFail($this->reportId);
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

            $performanceData[] = [
                'Employee Name' => $employee->name,
                'Total Days' => $totalDays,
                'Present Days' => $presentDays,
                'Late Days' => $lateDays,
                'Leave/WFH Days' => $leaveDays,
                'Absent Days' => $absentDays,
            ];
        }

        return collect($performanceData);
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Total Days',
            'Present Days',
            'Late Days',
            'Leave/WFH Days',
            'Absent Days',
        ];
    }
}