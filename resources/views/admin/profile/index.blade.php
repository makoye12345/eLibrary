@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>Admin Profile</h1>
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ $admin->profile_image ? asset('storage/' . $admin->profile_image) : asset('images/default-avatar.png') }}"
                             alt="Profile Image" class="img-fluid rounded-circle" style="max-width: 150px;">
                        <a href="{{ route('admin.profile.upload') }}" class="btn btn-primary mt-2">Change Image</a>
                    </div>
                    <div class="col-md-8">
                        <h4>{{ $admin->name }}</h4>
                        <p><strong>Email:</strong> {{ $admin->email }}</p>
                        <p><strong>Phone:</strong> {{ $admin->phone ?? 'Not provided' }}</p>
                        <p><strong>Address:</strong> {{ $admin->address ?? 'Not provided' }}</p>
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-secondary">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection