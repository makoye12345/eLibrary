<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .error-message { color: red; font-size: 0.875rem; }
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { 0% { opacity: 0; } 100% { opacity: 1; } }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
        <h1 class="text-2xl font-bold mb-4 text-center">Login</h1>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-4 p-2 bg-red-100 text-red-700 rounded fade-in">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="error-message">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-4 p-2 bg-green-100 text-green-700 rounded fade-in">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="reg_number" class="block mb-1 font-medium">Registration Number</label>
                <input type="text" name="reg_number" id="reg_number" value="{{ old('reg_number') }}" required
                       class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-1 font-medium">Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4 flex items-center">
                <input type="checkbox" name="remember" id="remember" class="mr-2">
                <label for="remember" class="text-sm">Remember me</label>
            </div>
            <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white p-2 rounded transition duration-200">
                Login
            </button>
        </form>

        <div class="mt-4 text-center">
            <p><a href="{{ route('password.request') }}" class="text-blue-500 hover:text-blue-600 text-sm">Forgot password?</a></p>
            <p><a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-600 text-sm">Haven't account? Sign up</a></p>
        </div>
    </div>
</body>
</html>