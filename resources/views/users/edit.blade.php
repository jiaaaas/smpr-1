<x-app-layout>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="text-center my-4" style="font-size: 23px; font-weight: bold;">Edit Employee's Detail</h4>
                <hr class="mb-4">                
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('users.form')
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3 ms-2">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>