<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Library Management System</title>
    <link rel="icon" type="image/x-ico" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }
        
        .header {
            background-color: #1e3c72;
            padding: 20px 0;
            color: white;
            text-align: center;
        }
        .header img {
            height: 60px;
            margin-right: 15px;
            vertical-align: middle;
        }
        .header h1 {
            display: inline-block;
            font-size: 2rem;
            margin: 0;
            vertical-align: middle;
            text-align: center;
        }
        .navbar {
            background-color: #2a5298;
        }
        .nav-link {
            color: white !important;
        }
        .carousel-inner img {
            height: 300px;
            object-fit: cover;
        }
        .dashboard-content {
            padding: 50px 0;
        }
        .hero-section {
            text-align: center;
            margin-bottom: 50px;
        }
        .hero-section h2 {
            font-size: 2.5rem;
            color: #1e3c72;
        }
        .hero-section p {
            font-size: 1.2rem;
            color: #666;
        }
        .features-section h3 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2rem;
            color: #333;
        }
        .feature-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
        }
        .feature-card h4 {
            font-size: 1.5rem;
            color: #2a5298;
        }
        .feature-card p {
            color: #666;
        }
        .cta-button {
            background-color: #2a5298;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }
        .cta-button:hover {
            background-color: #1e3c72;
        }
        footer {
            background-color: #1e3c72;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <!-- Header with Logo -->
    <header class="header">
        <div class="container">
            <img  style=" margin-left:-30%"src="{{ asset('images/logo.jpg') }}" alt="Library Logo">
            <h1>Library Management System</h1>
        </div>
    </header>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="d-flex w-100">
                <!-- Left Side Navigation -->
               
                <!-- Right Side Login/Register -->
                <div class="ms-auto d-flex">
                    <a class="nav-link px-3" href="{{ route('login') }}">Login</a>
                    

                </div>
            </div>
        </div>
    </nav>

    <!-- Carousel Section -->
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/book1.jpg') }}" class="d-block w-100" alt="Book 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/book4.jpg') }}" class="d-block w-100" alt="Book 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/book3.jpg') }}" class="d-block w-100" alt="Book 3">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Dashboard Content -->
    <section class="dashboard-content">
        <div class="container">
            <!-- Hero Section -->
            <div class="hero-section">
                <h2>Welcome to Your Library Dashboard</h2>
                <p>Explore a world of knowledge with our efficient and user-friendly Library Management System.</p>
                <a href="{{ route('login') }}" class="cta-button mt-4">Get Started</a>
            </div>

            <!-- Features Section -->
            <div class="features-section">
                <h3>Our Key Features</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <h4>Book Management</h4>
                            <p>Easily add, update, and track books with details like ISBN, author, and availability.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <h4>User Management</h4>
                            <p>Manage library members, their borrowing history, and access permissions seamlessly.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <h4>Borrowing & Returns</h4>
                            <p>Automate book issuing and return processes with due date tracking and notifications.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Library Description -->
    <section class="bg-white shadow-lg rounded-lg p-6 m-4">
        <h2 class="text-2xl font-semibold mb-4 text-center">About Our Library</h2>
        <p class="text-gray-700 leading-relaxed text-center">
            Welcome to our Library Management System, a place where knowledge meets passion. Our library offers a vast collection of books, digital resources, and services designed to inspire learning and creativity. Whether you're a student, researcher, or book enthusiast, our state-of-the-art facilities and dedicated staff are here to support your journey. Explore our curated collections, attend workshops, or simply relax in our cozy reading spaces. Join us today and discover a world of endless possibilities!
        </p>
    </section>

    <!-- Footer -->
    <footer>
        <p>© {{ date('Y') }} Library Management System. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>