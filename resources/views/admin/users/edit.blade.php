@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <h1 class="mb-4 text-xl md:text-2xl font-bold">Edit User</h1>

    <!-- Edit User Form -->
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Name Field -->
        <div class="mb-3">
            <label for="name" class="form-label text-sm">Name</label>
            <input type="text" name="name" class="form-control text-sm @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="invalid-feedback text-xs">{{ $message }}</div>
            @enderror
        </div>

        <!-- Registration Number Field -->
        <div class="mb-3">
            <label for="reg_number" class="form-label text-sm">Registration Number</label>
            <input type="text" name="reg_number" class="form-control text-sm @error('reg_number') is-invalid @enderror" value="{{ old('reg_number', $user->reg_number) }}" required>
            @error('reg_number')
                <div class="invalid-feedback text-xs">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label text-sm">Email</label>
            <input type="email" name="email" class="form-control text-sm @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="invalid-feedback text-xs">{{ $message }}</div>
            @enderror
        </div>

        <!-- Role Field -->
        <div class="mb-3">
            <label for="role" class="form-label text-sm">Role</label>
            <select name="role" class="form-control text-sm @error('role') is-invalid @enderror">
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
            </select>
            @error('role')
                <div class="invalid-feedback text-xs">{{ $message }}</div>
            @enderror
        </div>

        <!-- Profile Image Field -->
        <div class="mb-3">
            <label for="profile_image" class="form-label text-sm">Profile Image</label>
            <input type="file" name="profile_image" class="form-control text-sm @error('profile_image') is-invalid @enderror" accept="image/*">
            @if ($user->profile_image_path && Storage::disk('public')->exists($user->profile_image_path))
                <p class="mt-1 text-xs">Current: <img src="{{ asset('storage/' . $user->profile_image_path) }}" alt="Current profile image" class="current-profile-img"></p>
            @endif
            @error('profile_image')
                <div class="invalid-feedback text-xs">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary text-sm">Update User</button>
    </form>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .container-fluid {
        padding: 1rem;
    }
    .form-control {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
        width: 100%;
    }
    .form-label {
        font-size: 0.875rem;
    }
    .btn {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
    .invalid-feedback {
        font-size: 0.75rem;
    }
    .current-profile-img {
        max-width: 80px;
        max-height: 80px;
        object-fit: cover;
        margin-top: 0.25rem;
    }

    /* Mobile (≤ 576px) */
    @media (max-width: 576px) {
        .container-fluid {
            padding: 0.5rem;
        }
        .form-control {
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
        }
        .form-label {
            font-size: 0.75rem;
        }
        .btn {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            width: 100%;
            text-align: center;
        }
        .invalid-feedback {
            font-size: 0.65rem;
        }
        .current-profile-img {
            max-width: 60px;
            max-height: 60px;
        }
        .text-sm {
            font-size: 0.75rem;
        }
        .text-xs {
            font-size: 0.65rem;
        }
        .mb-3 {
            margin-bottom: 0.75rem !important;
        }
    }

    /* Tablet (577px–992px) */
    @media (min-width: 577px) and (max-width: 992px) {
        .form-control {
            font-size: 0.8rem;
            padding: 0.45rem 0.7rem;
        }
        .form-label {
            font-size: 0.8rem;
        }
        .btn {
            font-size: 0.8rem;
            padding: 0.45rem 0.9rem;
        }
        .invalid-feedback {
            font-size: 0.7rem;
        }
        .current-profile-img {
            max-width: 70px;
            max-height: 70px;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Debug form submission
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const formData = new FormData(this);
                console.log('Edit User Form data:', Array.from(formData.entries()));
            });
        }

        // Debug file input
        const fileInput = document.getElementById('profile_image');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                console.log('Profile Image selected:', this.files[0]?.name || 'None');
            });
        }
    });
</script>
@endsection