@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
    <!-- Privacy Policy Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold text-gray-800 text-center mb-12" data-aos="fade-up" data-aos-duration="500">Privacy Policy</h1>
            <div class="bg-gray-50 p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">1. Introduction</h2>
                <p class="text-gray-600 mb-6">At eLibrary, we are committed to protecting your privacy. This Privacy Policy explains how our Library Management System (LMS) collects, uses, and safeguards your personal information when you use our services, including borrowing books, accessing digital resources, and participating in events.</p>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">2. Information We Collect</h2>
                <p class="text-gray-600 mb-6">We collect the following information through our LMS:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li><strong>Personal Information</strong>: Name, email address, phone number, and address provided during account registration.</li>
                    <li><strong>Borrowing Data</strong>: Books borrowed, borrowing history, due dates, and reservation details.</li>
                    <li><strong>Usage Data</strong>: Interactions with the LMS, such as searches, event registrations, and digital resource access.</li>
                    <li><strong>Device Information</strong>: IP address, browser type, and device details for security and analytics.</li>
                </ul>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">3. How We Use Your Information</h2>
                <p class="text-gray-600 mb-6">Your information is used to:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li>Manage your LMS account and borrowing activities.</li>
                    <li>Provide access to digital resources and event registrations.</li>
                    <li>Send notifications about due dates, reservations, and upcoming events.</li>
                    <li>Improve our services through analytics and user feedback.</li>
                </ul>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">4. Data Sharing and Security</h2>
                <p class="text-gray-600 mb-6">We do not sell or share your personal information with third parties, except:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li>With library branches to facilitate book reservations.</li>
                    <li>With service providers for analytics and email notifications, under strict confidentiality agreements.</li>
                    <li>When required by law or to protect our rights.</li>
                </ul>
                <p class="text-gray-600 mb-6">We use industry-standard encryption to protect your data during transmission and storage.</p>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">5. Your Rights</h2>
                <p class="text-gray-600 mb-6">As an eLibrary user, you have the right to:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li>Access and update your personal information via your LMS account.</li>
                    <li>Request deletion of your account and associated data.</li>
                    <li>Opt out of non-essential notifications, such as newsletters.</li>
                </ul>
                <p class="text-gray-600 mb-6">To exercise these rights, contact us at <a href="mailto:support@elibrary.com" class="text-blue-500 hover:underline">makoyedeus@gmail.com</a>.</p>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">6. Cookies and Tracking</h2>
                <p class="text-gray-600 mb-6">Our LMS uses cookies to enhance your experience, such as remembering your login session and preferences. You can disable cookies in your browser, but this may affect LMS functionality.</p>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">7. Updates to This Policy</h2>
                <p class="text-gray-600 mb-6">We may update this Privacy Policy to reflect changes in our LMS or legal requirements. Updates will be posted here, and registered users will be notified via email. Last updated: June 28, 2025.</p>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">8. Contact Us</h2>
                <p class="text-gray-600">For questions about this Privacy Policy, contact us at <a href="mailto:support@elibrary.com" class="text-blue-500 hover:underline">makoyedeus@gmail.com</a> or via our <a href="/'contact" class="text-blue-500 hover:underline">Contact</a> page.</p>
            </div>
        </div>
    </section>
@endsection