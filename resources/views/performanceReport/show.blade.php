<x-app-layout>
    <div class="container mt-5">
        @if (session('success'))
            <div id="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="text-center my-4" style="font-size: 23px; font-weight: bold;">Performance Report</h4>
                <div class="d-flex justify-content-end mb-2">
                    <a href="{{ route('performanceReport.exportPDF', $report->id) }}" class="btn border export-btn me-2">
                        <i class="fas fa-file-pdf p-1"></i> Download PDF
                    </a>
                    <a href="{{ route('performanceReport.exportExcel', $report->id) }}" class="btn border export-btn">
                        <i class="fas fa-file-excel p-1"></i> Download Excel
                    </a>
                </div>
                <hr class="mb-4">

                <div class="mb-4">
                    <p class="text-custom1"><strong>Report Type:</strong> {{ ucfirst($report->report_type) }}</p>
                    <p class="text-custom1"><strong>Year:</strong> {{ $report->year }}</p>
                    @if ($report->report_type == 'month')
                        <p class="text-custom1"><strong>Month:</strong> {{ \Carbon\Carbon::create()->month((int)$report->month)->format('F') }}</p>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary align-middle">
                            <tr>
                                <th>No.</th>
                                <th>Employee Name</th>
                                <th>Total Days</th>
                                <th>Present Days</th>
                                <th>Late Days</th>
                                <th>Leave/WFH Days</th>
                                <th>Absent Days<p class="muted_text">(Without Reasons)</p></th>
                                <th>Performance</th>
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
                                    <td>
                                        @if (!isset($performanceData[$employee->id]['percentage_performance']))
                                            <span class="badge bg-secondary text-white">No Data</span>
                                        @elseif ($performanceData[$employee->id]['percentage_performance'] < 50)
                                            <span class="badge bg-danger text-white">Poor</span>
                                        @elseif ($performanceData[$employee->id]['percentage_performance'] < 80)
                                            <span class="badge bg-warning text-white">Average</span>
                                        @else
                                            <span class="badge bg-success text-white">Excellent</span>
                                        @endif
                                    </td>                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    {{-- <a href="{{ route('performanceReport.download', $report->id) }}" class="btn btn-primary mt-3">Download</a>
                    <a href="{{ route('performanceReport.index') }}" class="btn btn-secondary mt-3 ms-2">Back</a> --}}
                    <a href="{{ route('performanceReport.index') }}" class="btn btn-secondary mt-3 ms-2">Back</a>
                </div>
            </div>


        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messageElement = document.getElementById('successMessage');
            if (messageElement) {
                setTimeout(() => {
                    messageElement.style.display = 'none';
                }, 5000);
            }
        });
    </script>
    @endpush
</x-app-layout>