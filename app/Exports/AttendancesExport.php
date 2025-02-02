<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendancesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $today = Carbon::today();
        return Attendance::whereDate('date', $today)->with('user')->get()->map(function ($attendance) {
            return [
                'name' => $attendance->user->name,
                'scanned_at' => $attendance->scanned_at ? $attendance->scanned_at->format('H:i:s') : 'N/A',
                'status' => $attendance->scanned_at ? ($attendance->scanned_at->gt(Carbon::parse('09:00:00')) ? 'Late' : 'On Time') : 'Absent',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Time Scanned',
            'Status',
        ];
    }
}