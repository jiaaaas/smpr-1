<x-app-layout>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12 mt-2 mb-2 d-flex justify-content-between align-items-center">
                <h1 class="fw-bold text-custom3 text-start">Welcome, {{ Auth::user()->name }}</h1>
                <p class="text-muted mb-0 text-custom1">{{ \Carbon\Carbon::now()->toFormattedDateString() }}</p>
            </div>
            <hr class="mt-2 mb-2">
        </div>
        <div class="row g-3 justify-content-between">
            <div class="col-md-3">
                <div class="card shadow-md border-1" id="leaveApplicationCard" style="cursor: pointer;">
                    <div class="card-header text-center">
                        <h5 class="fw-bold text-custom1">Leave Application Pending</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="fw-bold text-custom2 text-blue">{{ $totalLeaveApplication ?? 'N/A' }}</h2>
                    </div>
                </div>
            </div>
    
            <div class="col-md-3">
                <div class="card shadow-md border-1" id="totalAbsentCard" style="cursor: pointer;">
                    <div class="card-header text-center">
                        <h5 class="fw-bold text-custom1">Total Absent</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="fw-bold text-custom2 text-yellow">{{ $totalAbsent ?? 'N/A' }}</h2>
                    </div>
                </div>
            </div>
    
            <div class="col-md-3">
                <div class="card shadow-md border-1">
                    <div class="card-header text-center">
                        <h5 class="fw-bold text-custom1">Total Late</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="fw-bold text-custom2 text-red">{{ $totalLate ?? 'N/A' }}</h2>
                    </div>
                </div>
            </div>
    
            <div class="col-md-3">
                <div class="card shadow-md border-1">
                    <div class="card-header text-center">
                        <h5 class="fw-bold text-custom1">Average Performance</h5>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="fw-bold text-custom2 text-purple">{{ $averagePerformance ?? 'N/A' }} %</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for displaying leave applications -->
    <div class="modal fade" id="leaveApplicationsModal" tabindex="-1" aria-labelledby="leaveApplicationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leaveApplicationsModalLabel">Leave Applications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>NO.</th>
                                <th>Employee's Name</th>
                                <th>Leave Type</th>
                            </tr>
                        </thead>
                        <tbody id="leaveApplicationsTableBody">
                            <!-- Leave applications will be populated here by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for displaying absent users -->
    <div class="modal fade" id="absentUsersModal" tabindex="-1" aria-labelledby="absentUsersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="absentUsersModalLabel">Absent Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>NO.</th>
                                <th>Employee's Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="absentUsersTableBody">
                            <!-- Absent users will be populated here by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12 mt-2 mb-2 d-flex justify-content-between align-items-center">
                <h1 class="fw-bold text-custom3 text-start">Employees Attendance Record</h1>
                <div>
                    <a href="{{ route('attendance.export.excel') }}" class="btn border export-btn">
                        <i class="fas fa-file-excel p-1"></i> Download Excel
                    </a>
                    <a href="{{ route('attendance.export.pdf') }}" class="btn border export-btn">
                        <i class="fas fa-file-pdf p-1"></i> Download PDF
                    </a>
                </div>
            </div>
            <hr class="mt-2 mb-2">
        </div>
        <div class="row">
            <div class="card shadow-md border-1 mt-4">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>NO.</th>
                                    <th style="width: 50%">Employee's Name</th>
                                    <th>Time Scanned</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $attendance)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attendance->user->name }}</td>
                                        <td>{{ $attendance->scanned_at ? $attendance->scanned_at->format('H:i:s') : 'N/A' }}</td>
                                        <td>
                                            @if ($attendance->scanned_at)
                                                @if ($attendance->scanned_at->gt(Carbon\Carbon::parse('09:00:00')))
                                                    <span class="badge bg-danger text-white">Late</span>
                                                @else
                                                    <span class="badge bg-success text-white">On Time</span>
                                                @endif
                                            @else
                                                Absent
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($attendances->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center">No record found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12 mt-2 mb-2 d-flex justify-content-between align-items-center">
                <h1 class="fw-bold text-custom3 text-start">Weekly Attendance Statistics</h1>
            </div>
            <hr class="mt-2 mb-2">
        </div>

        <div class="card shadow-md border-1 mt-4">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="fw-bold text-center">Weekly Attendance Statistics</h2>
                    <canvas id="attendanceChart" width="100" height="20"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            const attendanceData = @json($attendanceData);

            const labels = Object.keys(attendanceData).map(day => `${day} (${attendanceData[day].date})`);
            const presentData = labels.map(day => attendanceData[day.split(' ')[0]].present);
            const lateData = labels.map(day => attendanceData[day.split(' ')[0]].late);
            const absentData = labels.map(day => attendanceData[day.split(' ')[0]].absent);

            const data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Present',
                        data: presentData,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Late',
                        data: lateData,
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1,
                        fill: false,
                        tension: 0.1
                    },
                    {
                        label: 'Absent',
                        data: absentData,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        fill: false,
                        tension: 0.1
                    }
                ]
            };

            const config = {
                type: 'line',
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            };

            new Chart(ctx, config);

            // Handle click event on the Total Absent card
            document.getElementById('totalAbsentCard').addEventListener('click', function () {
                fetchAbsentUsers();
            });

            // Fetch absent users and display in the modal
            function fetchAbsentUsers() {
                fetch('{{ route('absent.users') }}')
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('absentUsersTableBody');
                        tableBody.innerHTML = '';

                        data.forEach((user, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${user.name}</td>
                                <td>Absent</td>
                            `;
                            tableBody.appendChild(row);
                        });

                        // Show the modal
                        const absentUsersModal = new bootstrap.Modal(document.getElementById('absentUsersModal'));
                        absentUsersModal.show();
                    })
                    .catch(error => console.error('Error fetching absent users:', error));
            }

            // Handle click event on the Leave Application Pending card
            document.getElementById('leaveApplicationCard').addEventListener('click', function () {
                fetchLeaveApplications();
            });

            // Fetch leave applications and display in the modal
            function fetchLeaveApplications() {
                fetch('{{ route('leave.applications') }}')
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('leaveApplicationsTableBody');
                        tableBody.innerHTML = '';

                        data.forEach((leave, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${leave.user.name}</td>
                                <td>${leave.leave_type}</td>
                            `;
                            tableBody.appendChild(row);
                        });

                        // Show the modal
                        const leaveApplicationsModal = new bootstrap.Modal(document.getElementById('leaveApplicationsModal'));
                        leaveApplicationsModal.show();
                    })
                    .catch(error => console.error('Error fetching leave applications:', error));
            }
        });
    </script>
</x-app-layout>