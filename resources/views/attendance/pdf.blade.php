<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Attendance Report</h2>
    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Time Scanned</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ $attendance->scanned_at ? $attendance->scanned_at->format('H:i:s') : 'N/A' }}</td>
                    <td>
                        @if ($attendance->scanned_at)
                            {{ $attendance->scanned_at->gt(Carbon\Carbon::parse('09:00:00')) ? 'Late' : 'On Time' }}
                        @else
                            Absent
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>