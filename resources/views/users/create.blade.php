<x-app-layout>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-2">Create Employee</h5>
                <hr class="mb-4">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    @include('users.form')
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mt-3">Create</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3 ms-2">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>