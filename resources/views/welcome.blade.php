@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <section class="hero-bg h-screen flex items-center justify-center text-white text-center relative overflow-hidden" data-aos="fade-up" data-aos-duration="1000">
        <div class="slideshow-container">
            <div class="slide">
                <img src="{{ asset('images/book4.jpg') }}" alt="Hero Image 1" class="w-full h-full object-cover absolute top-0 left-0">
            </div>
            <div class="slide">
                <img src="{{ asset('images/book3.jpg') }}" alt="Hero Image 2" class="w-full h-full object-cover absolute top-0 left-0">
            </div>
            <div class="slide">
                <img src="{{ asset('images/book1.jpg') }}" alt="Hero Image 3" class="w-full h-full object-cover absolute top-0 left-0">
            </div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Welcome to eLibrary</h1>
            <p class="text-xl md:text-2xl mb-8">Manage your reading with our advanced Library Management System, offering digital access, reservations, and community events.</p>
            <a href="/login" class="bg-blue-500 text-white px-8 py-4 rounded-full hover:bg-blue-600 transition text-lg">Get Started</a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-gray-800 text-center mb-12" data-aos="fade-up" data-aos-duration="500">Our Library Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="fade-up" data-aos-duration="500">
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg card-hover">
                    <img src="{{ asset('images/book3.jpg') }}" alt="Digital Catalog" class="w-full h-48 object-cover rounded-t-lg mb-4">
                    <h3 class="text-2xl font-semibold mb-2">Digital Catalog Access</h3>
                    <p class="text-gray-600">Search and access thousands of e-books and resources via our LMS.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('service-1-content', this)">Read More</a>
                    <div id="service-1-content" class="expanded-content text-gray-600 mt-4">
                        <p>Our Library Management System provides a comprehensive digital catalog with over 10,000 e-books, audiobooks, and research papers. Search by title, author, or genre, and access resources instantly through your user account. Features include full-text search, bookmarking, and offline reading capabilities.</p>
                    </div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg card-hover">
                    <img src="{{ asset('images/bookk.jpeg') }}" alt="Book Borrowing" class="w-full h-48 object-cover rounded-t-lg mb-4">
                    <h3 class="text-2xl font-semibold mb-2">Book Borrowing & Tracking</h3>
                    <p class="text-gray-600">Borrow and track books seamlessly with our LMS.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('service-2-content', this)">Read More</a>
                    <div id="service-2-content" class="expanded-content text-gray-600 mt-4">
                        <p>Reserve physical or digital books through our LMS and track borrowing status in real-time. Receive notifications for due dates, renewals, and availability. Our system supports holds, waitlists, and automated reminders to ensure a smooth borrowing experience across all library branches.</p>
                    </div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg card-hover">
                    <img src="{{ asset('images/book12.jpg') }}" alt="Event Management" class="w-full h-48 object-cover rounded-t-lg mb-4">
                    <h3 class="text-2xl font-semibold mb-2">Event Management</h3>
                    <p class="text-gray-600">Join and manage library events through our LMS.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('service-3-content', this)">Read More</a>
                    <div id="service-3-content" class="expanded-content text-gray-600 mt-4">
                        <p>Our LMS allows you to browse, register, and manage library events like book clubs, author talks, and workshops. Sync events to your calendar, receive reminders, and connect with other participants. Check out our upcoming event on "The New Frontier" on July 10, 2025!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Books Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-gray-800 text-center mb-12" data-aos="fade-up" data-aos-duration="500">Featured Books</h2>
            <div class="mb-8 max-w-md mx-auto">
                <input type="text" id="search" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search books...">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8" data-aos="fade-up" data-aos-duration="500" id="books-grid">
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover">
                    <img src="{{ asset('images/book43.png') }}" alt="The Great Novel" class="w-full h-64 object-cover rounded-t-lg mb-4">
                    <h3 class="text-xl font-semibold mb-2">The Great Novel</h3>
                    <p class="text-gray-600 mb-4">A captivating story available in our LMS.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('book-1-content', this)">Read More</a>
                    <div id="book-1-content" class="expanded-content text-gray-600 mt-4">
                        <p>"The Great Novel" by Jane Author is a tale of adventure and discovery. Borrow it digitally through our LMS or reserve a physical copy. Check availability, place holds, and track your borrowing history via your user account. Available now with full-text search!</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover">
                    <img src="{{ asset('images/st5.jpg') }}" alt="Science Unveiled" class="w-full h-64 object-cover rounded-t-lg mb-4">
                    <h3 class="text-xl font-semibold mb-2">Science Unveiled</h3>
                    <p class="text-gray-600 mb-4">Scientific insights accessible via our LMS.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('book-2-content', this)">Read More</a>
                    <div id="book-2-content" class="expanded-content text-gray-600 mt-4">
                        <p>"Science Unveiled" by Dr. John Scholar explores modern scientific discoveries. Access it instantly through our LMS’s digital catalog, with features like highlighting and note-taking. Reserve a physical copy or check borrowing status in your account.</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover">
                    <img src="{{ asset('images/book4.jpg') }}" alt="History Chronicles" class="w-full h-64 object-cover rounded-t-lg mb-4">
                    <h3 class="text-xl font-semibold mb-2">History Chronicles</h3>
                    <p class="text-gray-600 mb-4">Historical epic in our LMS catalog.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('book-3-content', this)">Read More</a>
                    <div id="book-3-content" class="expanded-content text-gray-600 mt-4">
                        <p>"History Chronicles" by Alex Historian brings history to life. Access it digitally via our LMS or reserve a physical copy at your nearest branch. Use your account to track borrowing, renew loans, or join our history book club on July 15, 2025.</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover">
                    <img src="{{ asset('images/book12.jpg') }}" alt="Mystery Tales" class="w-full h-64 object-cover rounded-t-lg mb-4">
                    <h3 class="text-xl font-semibold mb-2">Mystery Tales</h3>
                    <p class="text-gray-600 mb-4">Thrilling mysteries in our LMS.</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('book-4-content', this)">Read More</a>
                    <div id="book-4-content" class="expanded-content text-gray-600 mt-4">
                        <p>"Mystery Tales" by Alex Noir offers suspenseful short stories. Borrow it digitally through our LMS or reserve a physical copy. Manage your loans, check due dates, and explore similar titles in our catalog with your user account.</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-8">
                <a href="/blog" class="bg-blue-500 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition">Browse All Books</a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-gray-800 text-center mb-12" data-aos="fade-up" data-aos-duration="500">What Our Users Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="fade-up" data-aos-duration="500">
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg card-hover">
                    <p class="text-gray-600 italic mb-4">"The LMS makes borrowing books so easy!"</p>
                    <p class="text-gray-800 font-semibold">Deus Demo</p>
                    <p class="text-gray-500">Student</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('testimonial-1-content', this)">Read More</a>
                    <div id="testimonial-1-content" class="expanded-content text-gray-600 mt-4">
                        <p>As a student, I love how the LMS lets me search for textbooks, check availability, and borrow digitally or physically. The real-time tracking and renewal options are fantastic, and the note-taking feature helps me study efficiently!</p>
                    </div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg card-hover">
                    <p class="text-gray-600 italic mb-4">"Event management in the LMS is seamless!"</p>
                    <p class="text-gray-800 font-semibold">Renatus Milazo</p>
                    <p class="text-gray-500">Book Enthusiast</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('testimonial-2-content', this)">Read More</a>
                    <div id="testimonial-2-content" class="expanded-content text-gray-600 mt-4">
                        <p>The LMS’s event management feature is amazing. I registered for a book club on "The New Frontier" through my account and got calendar reminders. The platform makes it easy to connect with other readers and stay updated on library events.</p>
                    </div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg card-hover">
                    <p class="text-gray-600 italic mb-4">"Digital access via the LMS is a game-changer."</p>
                    <p class="text-gray-800 font-semibold">Mary Nkwame</p>
                    <p class="text-gray-500">Researcher</p>
                    <a href="#!" class="text-blue-500 hover:underline read-more mt-4 block" onclick="toggleContent('testimonial-3-content', this)">Read More</a>
                    <div id="testimonial-3-content" class="expanded-content text-gray-600 mt-4">
                        <p>As a researcher, I rely on the LMS for instant access to journals and e-books. The full-text search and annotation tools are invaluable, and reserving physical books for pickup is effortless. The LMS has streamlined my research process!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Signup -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-6" data-aos="fade-up" data-aos-duration="500">Stay Updated</h2>
            <p class="text-lg text-gray-600 mb-8">Subscribe to our newsletter for updates on new books, LMS features, and events.</p>
            <div class="max-w-md mx-auto">
                <input type="email" id="newsletter" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4" placeholder="Enter your email">
                <button onclick="subscribeNewsletter()" class="bg-blue-500 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition w-full">Subscribe</button>
            </div>
        </div>
    </section>

    <!-- Call-to-Action Banner -->
    <section class="py-16 cta-bg text-white text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold mb-6" data-aos="fade-up" data-aos-duration="1000">Join eLibrary Today</h2>
            <p class="text-xl mb-8">Unlock a world of knowledge with our LMS-powered free membership.</p>
            <a href="#signup" class="bg-white text-blue-500 px-8 py-4 rounded-full hover:bg-gray-100 transition text-lg">Sign Up Now</a>
        </div>
    </section>

    <!-- Styles for Hero Slideshow -->
    <style>
        /* Hero Slideshow */
        .hero-bg {
            position: relative;
            width: 100%;
            height: 50vh;
            overflow: hidden;
        }

        .slideshow-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            animation: slideShow 15s infinite;
        }

        .slide:nth-child(1) {
            animation-delay: 0s;
        }

        .slide:nth-child(2) {
            animation-delay: 5s;
        }

        .slide:nth-child(3) {
            animation-delay: 10s;
        }

        @keyframes slideShow {
            0% {
                opacity: 0;
                transform: translateX(-100%);
            }
            10% {
                opacity: 1;
                transform: translateX(0);
            }
            33.33% {
                opacity: 1;
                transform: translateX(0);
            }
            43.33% {
                opacity: 0;
                transform: translateX(100%);
            }
            100% {
                opacity: 0;
                transform: translateX(100%);
            }
        }

        /* Ensure text is readable over images */
        
        
    </style>

    <script>
        function toggleContent(id, element) {
            const content = document.getElementById(id);
            const isExpanded = content.style.display === 'block';
            content.style.display = isExpanded ? 'none' : 'block';
            element.textContent = isExpanded ? 'Read More' : 'Read Less';
        }

        function subscribeNewsletter() {
            const email = document.getElementById('newsletter').value;
            if (email) {
                alert('Thank you for subscribing!');
                document.getElementById('newsletter').value = '';
            } else {
                alert('Please enter a valid email address.');
            }
        }

        document.getElementById('search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('#books-grid .card-hover').forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const content = card.querySelector('p:not(.expanded-content p)').textContent.toLowerCase();
                const expandedContent = card.querySelector('.expanded-content p')?.textContent.toLowerCase() || '';
                card.style.display = title.includes(searchTerm) || content.includes(searchTerm) || expandedContent.includes(searchTerm) ? 'block' : 'none';
            });
        });
    </script>
@endsection