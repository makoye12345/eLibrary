<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eLibrary - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .expanded-content {
            transition: max-height 0.3s ease;
            max-height: 0;
            overflow: hidden;
        }
        .expanded-content.active {
            max-height: 1000px;
        }
        .logo-img {
            display: block;
            width: 82px;
            height: 52px;
            object-fit: cover;
        }
        .nav-container {
            display: flex;
            align-items: center;
        }
       
        
        .cta-bg {
            background-image: url('{{ asset('images/book1.jpg') }}');
            background-size: cover;
            background-position: center;
        }
        /* Navbar slide animation */
        header {
            transition: transform 0.3s ease;
        }
        header.hidden-nav {
            transform: translateY(-100%);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-100 font-sans">
    <header  class="bg-gray-900 text-white py-4 shadow-lg sticky top-0 z-50">
        <nav  class="container mx-auto flex justify-between items-center px-4 nav-container">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/ligo.jpeg') }}" alt="eLibrary Logo" class="logo-img" onerror="this.src='https://via.placeholder.com/32'; this.alt='Placeholder Logo';">
                <span class="text-xl font-bold">eLibrary</span>
            </div>
            <div class="hidden md:flex space-x-6">
                <a href="/" class="hover:text-blue-400 transition">Home</a>
                <a href="/about" class="hover:text-blue-400 transition">About Us</a>
                <a href="/blog" class="hover:text-blue-400 transition">Blog</a>
                <a href="/contact" class="hover:text-blue-400 transition">Contact</a>
                <a href="/login" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Login</a>
            </div>
            <button class="md:hidden text-2xl" onclick="toggleMenu()">☰</button>
            <div class="hidden md:hidden flex-col absolute top-16 left-0 w-full bg-gray-900 text-white p-4" id="mobile-menu">
                <a href="/" class="py-2 hover:text-blue-400">Home</a>
                <a href="/about" class="py-2 hover:text-blue-400">About Us</a>
                <a href="/blog" class="py-2 hover:text-blue-400">Blog</a>
                <a href="/contact" class="py-2 hover:text-blue-400">Contact</a>
                <a href="/login" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-2">Login</a>
            </div>
        </nav>
    </header>

    @yield('content')

    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-4">© 2025 eLibrary. All rights reserved.</p>
            <div class="space-x-4">
                <a href="/" class="hover:text-blue-400">Home</a>
                <a href="/about" class="hover:text-blue-400">About</a>
                <a href="/blog" class="hover:text-blue-400">Blog</a>
                <a href="/contact" class="hover:text-blue-400">Contact</a>
                <a href="/privacy" class="hover:text-blue-400">Privacy Policy</a>
                <a href="/term" class="hover:text-blue-400">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
        function toggleMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }
        function toggleContent(contentId, linkElement) {
            const content = document.getElementById(contentId);
            content.classList.toggle('active');
            linkElement.textContent = content.classList.contains('active') ? 'Read Less' : 'Read More';
        }

        // Navbar scroll behavior
        let lastScrollTop = 0;
        const header = document.querySelector('header');
        window.addEventListener('scroll', function() {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            if (currentScroll > lastScrollTop) {
                // Scrolling down
                header.classList.add('hidden-nav');
            } else {
                // Scrolling up
                header.classList.remove('hidden-nav');
            }
            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Prevent negative scroll
        });
    </script>
    @yield('scripts')
</body>
</html>
