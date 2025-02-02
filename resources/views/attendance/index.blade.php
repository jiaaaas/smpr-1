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
            <h4 class="text-center my-4" style="font-size: 23px; font-weight: bold;">List of Unscanned Employees</h4>
            <hr class="mb-4">
            <div class="d-flex justify-content-between mb-4">
                <form action="{{ route('attendance.index') }}" method="GET" class="row g-3 w-100">
                    <div class="col-12 col-md-3">
                        <input type="text" name="search_name" class="form-control" placeholder="Search by Name"
                        value="{{ request('search_name') }}">                    
                    </div>
                    <div class="col-12 col-md-3">
                        <select name="department" class="form-select">
                            <option value="">Select Department</option>
                            <option value="technical" {{ request()->input('department') == 'technical' ? 'selected' : '' }}>Technical</option>
                            <option value="management" {{ request()->input('department') == 'management' ? 'selected' : '' }}>Management</option>
                            <option value="admin" {{ request()->input('department') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="finance" {{ request()->input('department') == 'finance' ? 'selected' : '' }}>Finance</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <select name="work_type" class="form-select">
                            <option value="">Select Work Type</option>
                            <option value="hybrid" {{ request()->input('work_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                            <option value="non-hybrid" {{ request()->input('work_type') == 'non-hybrid' ? 'selected' : '' }}>Non-Hybrid</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('attendance.index') }}" class="btn btn-warning">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
            

            <form action="{{ route('attendance.update') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>NO.</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Phone</th>
                                <th>Work Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->department }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->work_type }}</td>
                                    <td>
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}">
                                    </td>
                                </tr>
                            @endforeach
                            @if ($users->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">No data found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mt-5">Update Attendance</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>