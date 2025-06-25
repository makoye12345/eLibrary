
@extends('layouts.user')

@section('content')
<div class="bg-white shadow-xl rounded-xl p-6 sm:p-8 lg:p-10 max-w-4xl w-full mx-auto">
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 w-full max-w-4xl" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6 pb-6 border-b border-gray-200 mb-6">
        <div class="flex-shrink-0">
            <img class="h-24 w-24 sm:h-32 sm:w-32 rounded-full object-cover border-4 border-indigo-500 shadow-md"
                 src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : asset('images/default-profile.png') }}"
                 alt="User Avatar">
        </div>
        <div class="text-center sm:text-left">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ $user->name }}</h1>
            <p class="text-md text-gray-500 mt-1">{{ $user->email }}</p>
        </div>
    </div>

    <div class="text-right">
        <a href="{{ route('profile.edit') }}"
           class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300">
            Edit Profile
        </a>
    </div>
</div>
@endsection
