<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" placeholder="John Doe" required>
            </div>

        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" placeholder="user@gmail.com" required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="phone">Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}" placeholder="0186648712">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $user->address ?? '') }}" placeholder="Melaka, Malaysia">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Your Password ..." required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="work_type">Work Type</label>
                <select name="work_type" class="form-control" required>
                    <option value="hybrid" {{ old('work_type', $user->work_type ?? '') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                    <option value="non-hybrid" {{ old('work_type', $user->work_type ?? '') == 'Non-Hybrid' ? 'selected' : '' }}>Non-Hybrid</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="department">Department</label>
                <select name="department" class="form-control" required>
                    <option value="technical" {{ old('department', $user->department ?? '') == 'Technical' ? 'selected' : '' }}>Technical</option>
                    <option value="management" {{ old('department', $user->department ?? '') == 'Management' ? 'selected' : '' }}>Management</option>
                    <option value="admin" {{ old('department', $user->department ?? '') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="finance" {{ old('department', $user->department ?? '') == 'Finance' ? 'selected' : '' }}>Finance</option>
                </select>
            </div>
        </div>
    </div>
</div>