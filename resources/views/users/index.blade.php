<x-app-layout>
    <div class="container mt-5">
        @if (session('success'))
            <div id="successMessage" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between">
                <h4 class="text-center my-4" style="font-size: 23px; font-weight: bold;">List of Employees</h4>
                <button onclick="window.location='{{ route('users.create') }}'" class="btn btn-primary btn-sm">
                    <i class="material-icons">Add New Employee</i>
                </button>
            </div>
            
            <div class="card-body">
                <div class="d-flex justify-content-between mb-4">
                    <form action="{{ route('users.index') }}" method="GET" class="row g-3 w-100">
                        <div class="col-12 col-md-3">
                            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ request()->input('name') }}">
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
                            <a href="{{ route('users.index') }}" class="btn btn-warning">
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Work Type</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ $user->work_type }}</td>
                                    <td>
                                        @if (strtolower($user->department) == 'admin')
                                            <span class="badge bg-success text-white">Admin</span>
                                        @else
                                            <span class="badge bg-secondary text-white">{{ ucfirst($user->department) }}</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-info btn-sm mx-1">
                                            <i class="fas fa-eye icon-custom"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm mx-1">
                                            <i class="fas fa-edit icon-custom"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash icon-custom"></i>
                                        </button>
                            
                                    </td>
                                </tr>
                            @endforeach
                            @if ($users->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center">No records found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    {{-- <div>
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this account? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('users.destroy', $user->id ?? '') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
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