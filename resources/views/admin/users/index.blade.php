@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <h1 class="mb-4">Manage Users</h1>

    <!-- Success/Error Messages -->
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

    <!-- Add User and Upload Users Buttons -->
    <div class="mb-4">
        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-user-plus me-2"></i> Add User
        </button>
        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#uploadUsersModal">
            <i class="fas fa-upload me-2"></i> Upload Users
        </button>
    </div>

    <!-- Users Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Reg No</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#profileModal{{ $user->id }}">
                                @if($user->profile_image && Storage::disk('public')->exists($user->profile_image))
                                    <img src="{{ Storage::url($user->profile_image) }}" 
                                         class="profile-img rounded-circle" 
                                         alt="{{ $user->name }}"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="profile-initials rounded-circle" 
                                         style="width: 40px; height: 40px; background-color: {{ '#' . substr(md5($user->reg_number), 0, 6) }}; 
                                                display: flex; align-items: center; justify-content: center;
                                                color: white; font-weight: bold;">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                            </a>
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->reg_number }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td class="d-flex">
                            <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal" 
                                    data-bs-target="#editUserModal" 
                                    onclick="fillEditForm({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->reg_number }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->profile_image ? Storage::url($user->profile_image) : '' }}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Profile Modal -->
                    <div class="modal fade" id="profileModal{{ $user->id }}" tabindex="-1" aria-labelledby="profileModalLabel{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="profileModalLabel{{ $user->id }}">{{ $user->name }}'s Profile</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="text-center mb-4">
                                        @if($user->profile_image && Storage::disk('public')->exists($user->profile_image))
                                            <img src="{{ Storage::url($user->profile_image) }}" 
                                                 class="rounded-circle border modal-profile-img" 
                                                 alt="{{ $user->name }}"
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        @else
                                            <div class="profile-initials rounded-circle mx-auto modal-profile-initials" 
                                                 style="width: 150px; height: 150px; background-color: {{ '#' . substr(md5($user->reg_number), 0, 6) }};
                                                        display: flex; align-items: center; justify-content: center;
                                                        color: white; font-weight: bold; font-size: 3rem;">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="user-details">
                                        <div class="mb-3">
                                            <h6 class="text-muted">Full Name</h6>
                                            <p>{{ $user->name }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted">Registration Number</h6>
                                            <p>{{ $user->reg_number }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted">Email Address</h6>
                                            <p>{{ $user->email }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted">Role</h6>
                                            <p>{{ ucfirst($user->role) }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-muted">Member Since</h6>
                                            <p>{{ $user->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button class="btn btn-primary" data-bs-toggle="modal" 
                                            data-bs-target="#editUserModal" 
                                            onclick="fillEditForm({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->reg_number }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->profile_image ? Storage::url($user->profile_image) : '' }}')">
                                        <i class="fas fa-edit me-1"></i> Edit Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted text-center">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <div class="col-md-12">
                            <label for="add_name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="add_name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="add_reg_number" class="form-label">Reg No</label>
                            <input type="text" class="form-control @error('reg_number') is-invalid @enderror" name="reg_number" id="add_reg_number" value="{{ old('reg_number') }}" required>
                            @error('reg_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="add_email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="add_email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="add_role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" name="role" id="add_role" required>
                                <option value="" disabled selected>Select Role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="add_password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="add_password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="add_password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="add_password_confirmation" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="add_profile_image" class="form-label">Profile Image (Optional)</label>
                            <input type="file" class="form-control @error('profile_image') is-invalid @enderror" name="profile_image" id="add_profile_image" accept="image/jpeg,image/png,image/jpg,image/gif">
                            @error('profile_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="add_image_preview" src="{{ asset('images/default-avatar.png') }}" alt="Profile Preview" class="img-thumbnail" style="max-width: 150px; height: auto;">
                            </div>
                        </div>
                        <div class="col-md-12 text-end">
                            <button type="submit" id="addSubmitBtn" class="btn btn-success">
                                <i class="fas fa-check-circle me-2"></i> Save User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Users Modal -->
    <div class="modal fade" id="uploadUsersModal" tabindex="-1" aria-labelledby="uploadUsersModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadUsersModalLabel">Upload Users from Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadUsersForm" action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <div class="col-md-12">
                            <label for="excel_file" class="form-label">Select Excel File</label>
                            <input type="file" class="form-control @error('excel_file') is-invalid @enderror" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" required>
                            @error('excel_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted">
                                <small>Supported formats: .xlsx, .xls, .csv. The file should contain columns: name, reg_number, email, role, password.</small>
                            </p>
                        </div>
                        <div class="col-md-12 text-end">
                            <button type="submit" id="uploadSubmitBtn" class="btn btn-success">
                                <i class="fas fa-upload me-2"></i> Upload Users
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" action="" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_id">
                        <div class="col-md-12">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="edit_name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="edit_reg_number" class="form-label">Reg No</label>
                            <input type="text" class="form-control @error('reg_number') is-invalid @enderror" name="reg_number" id="edit_reg_number" required>
                            @error('reg_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="edit_email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="edit_role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" name="role" id="edit_role" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="edit_profile_image" class="form-label">Profile Image (Optional)</label>
                            <input type="file" class="form-control @error('profile_image') is-invalid @enderror" name="profile_image" id="edit_profile_image" accept="image/jpeg,image/png,image/jpg,image/gif">
                            @error('profile_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="edit_image_preview" src="{{ asset('images/default-avatar.png') }}" alt="Profile Preview" class="img-thumbnail" style="max-width: 150px; height: auto;">
                            </div>
                        </div>
                        <div class="col-md-12 text-end">
                            <button type="submit" id="editSubmitBtn" class="btn btn-success">
                                <i class="fas fa-check-circle me-2"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .profile-img, .profile-initials {
        transition: transform 0.2s;
        cursor: pointer;
    }
    .profile-img:hover, .profile-initials:hover {
        transform: scale(1.1);
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .user-details h6 {
        font-size: 0.8rem;
        margin-bottom: 0.2rem;
        color: #6c757d;
    }
    .user-details p {
        font-size: 1rem;
        margin-bottom: 1rem;
    }
    .modal-profile-img, .modal-profile-initials {
        border: 3px solid #f8f9fa;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add User Image Preview
    document.getElementById('add_profile_image').addEventListener('change', function (e) {
        const preview = document.getElementById('add_image_preview');
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

    // Edit User Image Preview
    document.getElementById('edit_profile_image').addEventListener('change', function (e) {
        const preview = document.getElementById('edit_image_preview');
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Upload Users Form Submission Handling
    document.getElementById('uploadUsersForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('uploadSubmitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Uploading...';
    });

    // Reset upload form when modal is closed
    document.getElementById('uploadUsersModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('uploadUsersForm').reset();
        const submitBtn = document.getElementById('uploadSubmitBtn');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i> Upload Users';
    });

    // Fill Edit Form
    function fillEditForm(id, name, reg_number, email, role, profileImage) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_reg_number').value = reg_number;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_image_preview').src = profileImage || '{{ asset('images/default-avatar.png') }}';
        document.getElementById('editUserForm').action = '{{ url('admin/users') }}/' + id;
    }

    // Form Submission Handling
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('addSubmitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
    });

    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('editSubmitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Updating...';
    });

    // Reset form when modal is closed
    document.getElementById('addUserModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('addUserForm').reset();
        document.getElementById('add_image_preview').src = '{{ asset('images/default-avatar.png') }}';
        const submitBtn = document.getElementById('addSubmitBtn');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Save User';
    });

    // Reset edit form when modal is closed
    document.getElementById('editUserModal').addEventListener('hidden.bs.modal', function () {
        const submitBtn = document.getElementById('editSubmitBtn');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Update User';
    });
</script>
@endsection