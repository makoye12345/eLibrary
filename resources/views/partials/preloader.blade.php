<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Your stylesheets -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        /* Preloader styles */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loader-container {
            animation: fadeIn 1s ease-in-out;
        }

        .loader-logo {
            width: 100px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>

    <!-- Preloader -->
    <div id="preloader">
        <div class="loader-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="loader-logo">
        </div>
    </div>

    <!-- Your Page Content -->
    <div class="container">
        <h1>Admin Report</h1>
        <p>This is the report page content...</p>
        <!-- Add more HTML here -->
    </div>

    <!-- Scripts -->
    <script>
        // Hide preloader after the page is fully loaded
        window.addEventListener('load', function () {
            const preloader = document.getElementById('preloader');
            preloader.style.display = 'none';
        });
    </script>
</body>
</html>
