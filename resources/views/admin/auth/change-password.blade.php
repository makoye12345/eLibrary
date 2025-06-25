@extends('layouts.admin')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Change Password</h2>

    @if (session('status'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.change-password.update') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Current Password</label>
            <input type="password" name="current_password" required class="w-full mt-1 border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" name="new_password" required class="w-full mt-1 border p-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" required class="w-full mt-1 border p-2 rounded">
        </div>

        <div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Update Password
            </button>
        </div>
    </form>
</div>
@endsection
