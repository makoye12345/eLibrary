@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
    <!-- Terms of Service Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold text-gray-800 text-center mb-12" data-aos="fade-up" data-aos-duration="500">Terms of Service</h1>
            <div class="bg-gray-50 p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">1. Introduction</h2>
                <p class="text-gray-600 mb-6">Welcome to eLibrary’s Terms of Service. These terms govern your use of our Library Management System (LMS), including borrowing books, accessing digital resources, and participating in library events. By using our services, you agree to comply with these terms.</p>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">2. User Responsibilities</h2>
                <p class="text-gray-600 mb-6">As a user of the eLibrary LMS, you agree to:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li>Provide accurate information during account registration.</li>
                    <li>Return borrowed physical books by their due dates to avoid late fees.</li>
                    <li>Use digital resources (e.g., e-books, audiobooks) for personal, non-commercial purposes only.</li>
                    <li>Respect other users during community events, such as book clubs and author talks.</li>
                </ul>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">3. Account Usage</h2>
                <p class="text-gray-600 mb-6">Your LMS account is personal and non-transferable. You are responsible for:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li>Maintaining the confidentiality of your login credentials.</li>
                    <li>Notifying us immediately of any unauthorized account access at <a href="mailto:support@elibrary.com" class="text-blue-500 hover:underline">makoyedeus@gmail.com</a>.</li>
                    <li>Updating your account information to ensure accurate borrowing and event notifications.</li>
                </ul>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">4. Borrowing and Reservations</h2>
                <p class="text-gray-600 mb-6">The LMS allows you to borrow physical and digital books, subject to:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li>A maximum borrowing limit of 5 physical books and 10 digital resources at a time.</li>
                    <li>Due dates for physical books (typically 14 days) and digital resources (21 days).</li>
                    <li>Reservation holds, which expire after 3 days if not collected from your library branch.</li>
                    <li>Late fees for overdue physical books, as outlined in our borrowing policy.</li>
                </ul>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">5. Community Events</h2>
                <p class="text-gray-600 mb-6">Participation in LMS-managed events (e.g., book clubs, workshops) requires:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li>Registration through your LMS account.</li>
                    <li>Adherence to event guidelines, such as respectful communication.</li>
                    <li>Cancellation of event registrations at least 24 hours in advance, if applicable.</li>
                </ul>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">6. Prohibited Activities</h2>
                <p class="text-gray-600 mb-6">You may not:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li>Share your LMS account credentials with others.</li>
                    <li>Use the LMS to distribute copyrighted material without permission.</li>
                    <li>Engage in disruptive behavior during community events.</li>
                    <li>Attempt to access or modify the LMS’s backend systems or data.</li>
                </ul>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">7. Termination of Access</h2>
                <p class="text-gray-600 mb-6">We reserve the right to suspend or terminate your LMS account if you:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li>Violate these Terms of Service.</li>
                    <li>Fail to return overdue books or pay associated fees.</li>
                    <li>Engage in prohibited activities or misuse the LMS.</li>
                </ul>
                <p class="text-gray-600 mb-6">You may terminate your account at any time via the LMS settings or by contacting us.</p>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">8. Limitation of Liability</h2>
                <p class="text-gray-600 mb-6">eLibrary is not liable for:</p>
                <ul class="list-disc list-inside text-gray-600 mb-6">
                    <li>Loss or damage to personal property during library visits.</li>
                    <li>Interruptions in LMS access due to maintenance or technical issues.</li>
                    <li>Errors in book availability or event scheduling information.</li>
                </ul>
                <p class="text-gray-600 mb-6">Our services are provided "as is," and we strive to ensure a reliable user experience.</p>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">9. Updates to These Terms</h2>
                <p class="text-gray-600 mb-6">We may update these Terms of Service to reflect changes in our LMS or legal requirements. Updates will be posted here, and registered users will be notified via email. Last updated: June 28, 2025.</p>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">10. Contact Us</h2>
                <p class="text-gray-600">For questions about these Terms of Service, contact us at <a href="mailto:support@elibrary.com" class="text-blue-500 hover:underline">makoyedeus@gmail.com</a> or via our <a href="/'contact'" class="text-blue-500 hover:underline">Contact</a> page.</p>
            </div>
        </div>
    </section>
@endsection