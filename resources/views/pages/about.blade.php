@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gray-50 py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6" data-aos="fade-up" data-aos-duration="1000">About eLibrary</h1>
            <p class="text-lg md:text-xl text-gray-600 mb-8">Empowering knowledge access through innovative digital and traditional library solutions.</p>
            <img src="{{ asset('images/book1.jpg') }}" alt="Library Image" class="w-full max-w-3xl mx-auto h-64 md:h-96 object-cover rounded-lg shadow-lg" onerror="this.src='https://via.placeholder.com/1200x600?text=Library+Image'; this.alt='Placeholder Library Image';">
        </div>
    </section>

    <!-- Mission and Vision -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-gray-800 text-center mb-12" data-aos="fade-up" data-aos-duration="500">Our Mission & Vision</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8" data-aos="fade-up" data-aos-duration="500">
                <div class="bg-gray-50 p-8 rounded-lg shadow-lg card-hover">
                    <h3 class="text-2xl font-semibold mb-4">Our Mission</h3>
                    <p class="text-gray-600">To democratize access to knowledge by providing a seamless, user-friendly platform that integrates traditional and digital library services, empowering learners and readers worldwide.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-lg shadow-lg card-hover">
                    <h3 class="text-2xl font-semibold mb-4">Our Vision</h3>
                    <p class="text-gray-600">To be the leading global library platform, fostering a connected community of readers, researchers, and educators through innovative technology and inclusive access.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our History (Timeline) -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-gray-800 text-center mb-12" data-aos="fade-up" data-aos-duration="500">Our Journey</h2>
            <div class="max-w-3xl mx-auto">
                <div class="timeline-item mb-8">
                    <h3 class="text-xl font-semibold mb-2">2020: Founded</h3>
                    <p class="text-gray-600">eLibrary was established with a mission to modernize library access through technology.</p>
                </div>
                <div class="timeline-item mb-8">
                    <h3 class="text-xl font-semibold mb-2">2021: Digital Platform Launch</h3>
                    <p class="text-gray-600">Launched our digital library platform, offering thousands of e-books and resources.</p>
                </div>
                <div class="timeline-item mb-8">
                    <h3 class="text-xl font-semibold mb-2">2023: Community Expansion</h3>
                    <p class="text-gray-600">Partnered with schools and libraries to host community events and book clubs.</p>
                </div>
                <div class="timeline-item mb-8">
                    <h3 class="text-xl font-semibold mb-2">2025: Global Reach</h3>
                    <p class="text-gray-600">Expanded our services to serve users in over 50 countries, with multilingual support.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Team -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-gray-800 text-center mb-12" data-aos="fade-up" data-aos-duration="500">Meet Our Team</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8" data-aos="fade-up" data-aos-duration="500">
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg card-hover">
                    <img src="{{ asset('images/rumbagunya.png') }}" alt="Team Member 1" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Team+Member'; this.alt='Placeholder Team Image';">
                    <h3 class="text-xl font-semibold mb-2">Pro.Rumbagunya</h3>
                    <p class="text-gray-600">Head Librarian</p>
                    <p class="text-gray-500 text-sm">Over 15 years of experience in library management.</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg card-hover">
                    <img src="{{ asset('images/deus.png') }}" alt="Team Member 2" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Team+Member'; this.alt='Placeholder Team Image';">
                    <h3 class="text-xl font-semibold mb-2">Deus Daud</h3>
                    <p class="text-gray-600">Lead Developer</p>
                    <p class="text-gray-500 text-sm">Expert in building scalable digital platforms.</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg card-hover">
                    <img src="{{ asset('images/st2.jpg') }}" alt="Team Member 3" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Team+Member'; this.alt='Placeholder Team Image';">
                    <h3 class="text-xl font-semibold mb-2">Sarah Njoroge</h3>
                    <p class="text-gray-600">Community Manager</p>
                    <p class="text-gray-500 text-sm">Passionate about fostering reader communities.</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-lg card-hover">
                    <img src="{{ asset('images/st1.jpg') }}" alt="Team Member 4" class="w-full h-48 object-cover rounded-t-lg mb-4" onerror="this.src='https://via.placeholder.com/300x200?text=Team+Member'; this.alt='Placeholder Team Image';">
                    <h3 class="text-xl font-semibold mb-2">Lightness Otieno</h3>
                    <p class="text-gray-600">Customer Support Lead</p>
                    <p class="text-gray-500 text-sm">Dedicated to ensuring user satisfaction.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-gray-800 text-center mb-12" data-aos="fade-up" data-aos-duration="500">Our Core Values</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-aos="fade-up" data-aos-duration="500">
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover">
                    <h3 class="text-xl font-semibold mb-2">Accessibility</h3>
                    <p class="text-gray-600">Ensuring knowledge is available to all, anytime, anywhere.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover">
                    <h3 class="text-xl font-semibold mb-2">Innovation</h3>
                    <p class="text-gray-600">Leveraging technology to enhance the library experience.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg card-hover">
                    <h3 class="text-xl font-semibold mb-2">Community</h3>
                    <p class="text-gray-600">Building a vibrant, inclusive community of readers.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="py-16 stats-bg text-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12" data-aos="fade-up" data-aos-duration="500">Our Impact</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8" data-aos="fade-up" data-aos-duration="500">
                <div class="text-center">
                    <h3 class="text-3xl font-bold">50K+</h3>
                    <p class="text-lg">Books in Collection</p>
                </div>
                <div class="text-center">
                    <h3 class="text-3xl font-bold">10K+</h3>
                    <p class="text-lg">Active Users</p>
                </div>
                <div class="text-center">
                    <h3 class="text-3xl font-bold">100+</h3>
                    <p class="text-lg">Community Events</p>
                </div>
                <div class="text-center">
                    <h3 class="text-3xl font-bold">50+</h3>
                    <p class="text-lg">Countries Served</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call-to-Action -->
    <section class="py-16 bg-white text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-gray-800 mb-6" data-aos="fade-up" data-aos-duration="500">Join Our Community</h2>
            <p class="text-lg text-gray-600 mb-8">Become part of eLibrary and explore a world of knowledge today!</p>
            <a href="/login" class="bg-blue-500 text-white px-8 py-4 rounded-full hover:bg-blue-600 transition text-lg">Get Started</a>
        </div>
    </section>
@endsection