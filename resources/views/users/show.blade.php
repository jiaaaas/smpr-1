<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="text-center my-4" style="font-size: 23px; font-weight: bold;">Employee's Detail</h4>
                <hr class="mb-4">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="text-custom1"><strong>Name:</strong></p>
                    </div>
                    <div class="col-md-8">
                        <p class="text-custom1">{{ $user->name }}</p>
                    </div>
                </div>
                <hr class="mb-3">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="text-custom1"><strong>Email:</strong></p>
                    </div>
                    <div class="col-md-8">
                        <p class="text-custom1">{{ $user->email }}</p>
                    </div>
                </div>
                <hr class="mb-3">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="text-custom1"><strong>Phone:</strong></p>
                    </div>
                    <div class="col-md-8">
                        <p class="text-custom1">{{ $user->phone }}</p>
                    </div>
                </div>
                <hr class="mb-3">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="text-custom1"><strong>Address:</strong></p>
                    </div>
                    <div class="col-md-8">
                        <p class="text-custom1">{{ $user->address }}</p>
                    </div>
                </div>
                <hr class="mb-3">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="text-custom1"><strong>Work Type:</strong></p>
                    </div>
                    <div class="col-md-8">
                        <p class="text-custom1">{{ $user->work_type }}</p>
                    </div>
                </div>
                <hr class="mb-3">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="text-custom1"><strong>Department:</strong></p>
                    </div>
                    <div class="col-md-8">
                        <p class="text-custom1">{{ $user->department }}</p>
                    </div>
                </div>
                <hr class="mb-3">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>