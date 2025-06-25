@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold mb-4">My Profile</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2" required>
            @error('name') <small class="text-red-600">{{ $message }}</small> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2" required>
            @error('email') <small class="text-red-600">{{ $message }}</small> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">New Password (optional)</label>
            <input type="password" name="password"
                   class="w-full border border-gray-300 rounded px-3 py-2">
            @error('password') <small class="text-red-600">{{ $message }}</small> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update Profile
            </button>
        </div>
    </form>
</div>
@endsection
