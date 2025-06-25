<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Add your CSS here (e.g., Bootstrap or custom styles) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2>Edit Profile</h2>

        <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Profile Picture Section -->
            <div class="form-group text-center">
                <img id="profileImage" src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" class="profile-img">
                <div class="mt-2">
                    <label for="profile_picture" class="btn btn-secondary">Change Profile Picture</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" onchange="previewImage(event)" class="d-none">
                </div>
            </div>

            <!-- Name Field -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="form-text text-muted">Leave blank if you don't want to change your password.</small>
            </div>

            <!-- Password Confirmation Field -->
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <!-- JavaScript for Previewing Image -->
    <script>
        // Preview the selected profile image
        function previewImage(event) {
            var output = document.getElementById('profileImage');
            output.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>

</body>

</html>
