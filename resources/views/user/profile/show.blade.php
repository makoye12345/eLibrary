@extends('layouts.user')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .avatar-upload {
            position: relative;
            display: inline-block;
        }
        .avatar-upload input {
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .avatar-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 sm:p-6 lg:p-8">
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 w-full max-w-4xl" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-xl p-6 sm:p-8 lg:p-10 max-w-4xl w-full">
        <form action="{{ route('profile.update', ['username' => $user->username]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6 pb-6 border-b border-gray-200 mb-6">
                <div class="flex-shrink-0 avatar-upload">
                    <img class="h-24 w-24 sm:h-32 sm:w-32 rounded-full object-cover border-4 border-indigo-500 shadow-md"
                         src="{{ $user->avatar_url }}"
                         alt="User Avatar">
                    <input type="file" name="avatar" id="avatar" accept="image/*">
                    <label for="avatar" class="avatar-upload-label">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2H4zm2 3a2 2 0 012-2h4a2 2 0 012 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2V6zm4-1a1 1 0 00-1 1v1H8a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V6a1 1 0 00-1-1z"/>
                        </svg>
                    </label>
                </div>
                <div class="text-center sm:text-left">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ $user->name }}</h1>
                    <p class="text-lg text-gray-600">{{ $user->occupation ?? 'Not specified' }}</p>
                    <p class="text-md text-gray-500 mt-1">{{ $user->email }}</p>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                        <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3"
                                  placeholder="Tell us about yourself...">{{ $user->bio ?? '' }}</textarea>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" id="location" name="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3"
                               value="{{ $user->location ?? '' }}">

                        <label for="website" class="block text-sm font-medium text-gray-700 mt-4 mb-1">Website</label>
                        <input type="url" id="website" name="website" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3"
                               value="{{ $user->website ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-3"
                               value="{{ $user->phone ?? '' }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Social Media</label>
                        <div class="flex items-center space-x-3 mt-1">
                            <a href="{{ $user->linkedin ?? 'https://linkedin.com/in/johndoe' }}" target="_blank" class="text-blue-700 hover:text-blue-900 transition duration-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                            <a href="{{ $user->github ?? 'https://github.com/johndoe' }}" target="_blank" class="text-gray-800 hover:text-gray-900 transition duration-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.499.09.682-.217.682-.483 0-.237-.008-.865-.013-1.702-2.782.602-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.618.069-.606.069-.606 1.003.07 1.531 1.032 1.531 1.032.892 1.529 2.341 1.088 2.91.831.091-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.953 0-1.096.392-1.988 1.03-2.69-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.099 2.65.648.702 1.029 1.593 1.029 2.69 0 3.848-2.339 4.695-4.566 4.942.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.523 2 12 2z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <a href="{{ $user->twitter ?? 'https://x.com/johndoe' }}" target="_blank" class="text-gray-800 hover:text-gray-900 transition duration-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.21-6.874L4.937 21.75H1.62l7.73-8.835L1.254 2.25H8.08l4.714 6.23L18.244 2.25zm-2.88 16.636h1.54l-6.598-9.142-4.348 9.142h-1.54l6.598-9.142 4.348 9.142z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Preferences</h2>
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">Receive email notifications</span>
                    <label for="email-notifications" class="flex items-center cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" id="email-notifications" name="email_notifications" class="sr-only" {{ $user->email_notifications ? 'checked' : '' }}>
                            <div class="block bg-gray-300 w-14 h-8 rounded-full"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition transform duration-300"></div>
                        </div>
                    </label>
                </div>
            </div>

           <form action="{{ route('profile.update') }}" method="POST">
    @csrf
    @method('PUT') <!-- This tells Laravel it's a PUT request -->

    <!-- Your form fields here -->

    <div class="text-right"> 
        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300">
            Save Changes
        </button>
    </div>
</form>

        </form>

        <script>
            // JavaScript for the toggle switch
            const toggle = document.getElementById('email-notifications');
            const dot = toggle.nextElementSibling.querySelector('.dot');

            toggle.addEventListener('change', function() {
                if (this.checked) {
                    this.nextElementSibling.classList.remove('bg-gray-300');
                    this.nextElementSibling.classList.add('bg-indigo-600');
                    dot.classList.remove('translate-x-0');
                    dot.classList.add('translate-x-full');
                } else {
                    this.nextElementSibling.classList.remove('bg-indigo-600');
                    this.nextElementSibling.classList.add('bg-gray-300');
                    dot.classList.remove('translate-x-full');
                    dot.classList.add('translate-x-0');
                }
            });

            // Initial state for the toggle
            if (წ
            toggle.checked) {
                toggle.nextElementSibling.classList.add('bg-indigo-600');
                dot.classList.add('translate-x-full');
            } else {
                toggle.nextElementSibling.classList.add('bg-gray-300');
                dot.classList.add('translate-x-0');
            }
        </script>
    </div>
</body>
</html>
@endsection