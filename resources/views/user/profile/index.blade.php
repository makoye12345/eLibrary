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
            <p class="text-lg text-gray-600">{{ $user->title ?? 'Not specified' }}</p>
            <p class="text-md text-gray-500 mt-1">{{ $user->email }}</p>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Personal Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-700 mb-1">Bio</p>
                <p class="text-sm text-gray-600">{{ $user->bio ?? 'Not provided' }}</p>
                <p class="text-sm font-medium text-gray-700 mt-4 mb-1">Skills</p>
                <p class="text-sm text-gray-600">{{ $user->skills ?? 'Not provided' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700 mb-1">Location</p>
                <p class="text-sm text-gray-600">{{ $user->location ?? 'Not provided' }}</p>
                <p class="text-sm font-medium text-gray-700 mt-4 mb-1">Website</p>
                <p class="text-sm text-gray-600">
                    @if ($user->website_url)
                        <a href="{{ $user->website_url }}" target="_blank" class="text-indigo-600 hover:underline">{{ $user->website_url }}</a>
                    @else
                        Not provided
                    @endif
                </p>
                <p class="text-sm font-medium text-gray-700 mt-4 mb-1">Portfolio</p>
                <p class="text-sm text-gray-600">
                    @if ($user->portfolio_url)
                        <a href="{{ $user->portfolio_url }}" target="_blank" class="text-indigo-600 hover:underline">{{ $user->portfolio_url }}</a>
                    @else
                        Not provided
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Contact Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-700 mb-1">Phone</p>
                <p class="text-sm text-gray-600">{{ $user->phone ?? 'Not provided' }}</p>
                <p class="text-sm font-medium text-gray-700 mt-4 mb-1">Available for Hire</p>
                <p class="text-sm text-gray-600">{{ $user->available_for_hire ? 'Yes' : 'No' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700 mb-1">Social Media</p>
                <div class="flex items-center space-x-3 mt-1">
                    @if ($user->linkedin)
                        <a href="{{ $user->linkedin }}" target="_blank" class="text-blue-700 hover:text-blue-900 transition duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                        </a>
                    @endif
                    @if ($user->github)
                        <a href="{{ $user->github }}" target="_blank" class="text-gray-800 hover:text-gray-900 transition duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.499.09.682-.217.682-.483 0-.237-.008-.865-.013-1.702-2.782.602-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.618.069-.606.069-.606 1.003.07 1.531 1.032 1.531 1.032.892 1.529 2.341 1.088 2.91.831.091-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.953 0-1.096.392-1.988 1.03-2.69-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.099 2.65.648.702 1.029 1.593 1.029 2.69 0 3.848-2.339 4.695-4.566 4.942.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.523 2 12 2z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    @endif
                    @if ($user->twitter)
                        <a href="{{ $user->twitter }}" target="_blank" class="text-gray-800 hover:text-gray-900 transition duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.21-6.874L4.937 21.75H1.62l7.73-8.835L1.254 2.25H8.08l4.714 6.23L18.244 2.25zm-2.88 16.636h1.54l-6.598-9.142-4.348 9.142h-1.54l6.598-9.142 4.348 9.142z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Preferences</h2>
        <div class="flex items-center justify-between">
            <span class="text-gray-700">Receive email notifications</span>
            <span class="text-sm text-gray-600">{{ $user->email_notifications ? 'Enabled' : 'Disabled' }}</span>
        </div>
        <div class="flex items-center justify-between mt-4">
            <span class="text-gray-700">Available for hire</span>
            <span class="text-sm text-gray-600">{{ $user->available_for_hire ? 'Yes' : 'No' }}</span>
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