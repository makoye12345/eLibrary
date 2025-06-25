<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Library Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

    @include('admin.layouts.nav') {{-- optional: create nav.blade.php for your navbar/sidebar --}}

    <div class="container py-4">
        @yield('content')
    </div>

</body>
</html>
