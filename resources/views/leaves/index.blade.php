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
                <h4 class="text-center my-4" style="font-size: 23px; font-weight: bold;">List of Leave Applications</h4>
                <hr class="mb-4">
                <div class="d-flex justify-content-between mb-4">
                    <form action="{{ route('leaves.index') }}" method="GET" class="row g-3 w-100">
                        <div class="col-12 col-md-3">
                            <input type="text" name="search_name" class="form-control" placeholder="Search by Name"
                            value="{{ request('search_name') }}">                    
                        </div>
                        <div class="col-12 col-md-3">
                            <select name="leave_type" class="form-select">
                                <option value="">Select Leave Type</option>
                                <option value="leave/mc" {{ request()->input('leave_type') == 'leave/mc' ? 'selected' : '' }}>Leave/MC</option>
                                <option value="WFH" {{ request()->input('leave_type') == 'wfh' ? 'selected' : '' }}>WFH</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Select Status</option>
                                <option value="pending" {{ request()->input('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request()->input('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request()->input('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-flex align-items-center">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('leaves.index') }}" class="btn btn-warning">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>No.</th>
                                <th>User</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Leave Type</th>
                                <th>Leave Reasons</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaves as $leave)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $leave->user->name }}</td>
                                    <td>{{ $leave->start_date }}</td>
                                    <td>{{ $leave->end_date }}</td>
                                    <td>{{ ucfirst($leave->leave_type) }}</td>
                                    <td>{{ $leave->leave_reasons }}</td>
                                    <td>
                                        <span class="badge text-white {{ $leave->status == 'approved' ? 'bg-success' : ($leave->status == 'rejected' ? 'bg-danger' : 'bg-secondary') }}">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('leaves.updateStatus', $leave->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="status" value="approved" class="btn btn-success" {{ $leave->status == 'approved' ? 'disabled' : '' }}>Approved</button>
                                            <button type="submit" name="status" value="rejected" class="btn btn-danger" {{ $leave->status == 'rejected' ? 'disabled' : '' }}>Rejected</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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