<!-- filepath: /C:/laragon/www/smpr/resources/views/performanceReport/pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Performance Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Performance Report</h2>
    <p><strong>Report Type:</strong> {{ ucfirst($report->report_type) }}</p>
    <p><strong>Year:</strong> {{ $report->year }}</p>
    @if ($report->report_type == 'month')
        <p><strong>Month:</strong> {{ \Carbon\Carbon::create()->month((int)$report->month)->format('F') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Employee Name</th>
                <th>Total Days</th>
                <th>Present Days</th>
                <th>Late Days</th>
                <th>Leave/WFH Days</th>
                <th>Absent Days</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $performanceData[$employee->id]['total'] }}</td>
                    <td>{{ $performanceData[$employee->id]['present'] }}</td>
                    <td>{{ $performanceData[$employee->id]['late'] }}</td>
                    <td>{{ $performanceData[$employee->id]['leave'] }}</td>
                    <td>{{ $performanceData[$employee->id]['absent'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>