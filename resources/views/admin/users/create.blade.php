@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <h1 class="mb-4">Add New User</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form id="userForm" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf

                <!-- Name -->
                <div class="col-md-4">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Registration Number -->
                <div class="col-md-4">
                    <label for="reg_number" class="form-label">Registration Number</label>
                    <input type="text" class="form-control @error('reg_number') is-invalid @enderror" name="reg_number" id="reg_number" value="{{ old('reg_number') }}" required>
                    @error('reg_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Role -->
                <div class="col-md-4">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select @error('role') is-invalid @enderror" name="role" id="role" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation" required>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Profile Image -->
                <div class="col-md-6">
                    <label for="profile_image" class="form-label">Profile Image (Optional)</label>
                    <input type="file" class="form-control @error('profile_image') is-invalid @enderror" name="profile_image" id="profile_image" accept="image/jpeg,image/png,image/jpg,image/gif">
                    @error('profile_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <!-- Image Preview -->
                    <div class="mt-2">
                        <img id="image_preview" src="{{ asset('images/default-avatar.png') }}" alt="Profile Preview" class="img-thumbnail" style="max-width: 150px; height: auto;">
                    </div>
                </div>

                <!-- Buttons -->
                <div class="col-md-12 text-end">
                    <button type="submit" id="submitBtn" class="btn btn-success">
                        <i class="fas fa-check-circle me-2"></i> Save User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Image preview functionality
    document.getElementById('profile_image').addEventListener('change', function (e) {
        const preview = document.getElementById('image_preview');
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '{{ asset('images/default-avatar.png') }}';
        }
    });

    // Form submission handling
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
    });
</script>
@endsection