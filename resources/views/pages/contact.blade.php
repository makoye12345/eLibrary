<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eLibrary - Contact</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <style>
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .faq-item {
            transition: background-color 0.3s ease;
        }
        .faq-item:hover {
            background-color: #f1f5f9;
        }
        .live-chat {
            background: linear-gradient(90deg, #3498db, #2980b9);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <header class="bg-gray-900 text-white py-4 shadow-lg sticky top-0 z-50">
        <nav class="container mx-auto flex justify-between items-center px-4">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.jpg') }}" alt="eLibrary Logo" class="w-8 h-8">
                <span class="text-xl font-bold">eLibrary</span>
            </div>
            <div class="hidden md:flex space-x-6">
                <a href="/" class="py-2 hover:text-blue-400">Home</a>
                <a href="/about" class="py-2 hover:text-blue-400">About Us</a>
                <a href="/blog" class="py-2 hover:text-blue-400">Blog</a>
                <a href="/contact" class="py-2 hover:text-blue-400">Contact</a>
                <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Login</a>
            </div>
            <button class="md:hidden text-2xl" onclick="toggleMenu()">☰</button>
            <div class="hidden md:hidden flex-col absolute top-16 left-0 w-full bg-gray-900 text-white p-4" id="mobile-menu">
                <a href="/" class="py-2 hover:text-blue-400">Home</a>
                <a href="/about" class="py-2 hover:text-blue-400">About Us</a>
                <a href="/blog" class="py-2 hover:text-blue-400">Blog</a>
                <a href="/contact" class="py-2 hover:text-blue-400">Contact</a>
                <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-2">Login</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="bg-gray-50 py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6" data-aos="fade-up">Get in Touch</h1>
            <p class="text-lg md:text-xl text-gray-600 mb-8">We’re here to assist you with all your library needs. Reach out today!</p>
        </div>
    </section>

    <!-- Contact Information and Form -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div id="form-messages" class="mb-4"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8" data-aos="fade-up">
                <!-- Contact Information -->
                <div class="bg-gray-50 p-8 rounded-lg shadow-lg card-hover">
                    <h2 class="text-2xl font-semibold mb-4">Contact Information</h2>
                    <p class="text-gray-600 mb-4"><strong>Address:</strong>Tanzania,Dodoma</p>
                    <p class="text-gray-600 mb-4"><strong>Phone:</strong> +255745092307</p>
                    <p class="text-gray-600 mb-4"><strong>Email:</strong> <a href="mailto:makoyedeus@gmail.com" class="text-blue-500 hover:underline">makoyedeus@gmail.com</a></p>
                    <p class="text-gray-600 mb-4"><strong>Hours:</strong> Mon-Fri: 9 AM - 6 PM, Sat: 10 AM - 4 PM</p>
                    <div class="flex space-x-4 mb-6">
                        <a href="https://facebook.com" target="_blank" class="text-blue-500 hover:text-blue-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.88-11.71h-1.74c-.2 0-.39.08-.53.22-.14.14-.22.33-.22.53v1.74h2.49l-.33 2.49h-2.16v6.24h-2.49v-6.24H8.49V10.3h2.49V8.56c0-2.49 1.51-3.85 3.71-3.85h1.74v2.49z"/></svg>
                        </a>
                        <a href="https://twitter.com" target="_blank" class="text-blue-500 hover:text-blue-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.83-12.55c-.45.2-.9.35-1.39.42.5-.3.89-.77 1.07-1.34-.47.28-1 .48-1.56.59-.46-.49-1.11-.79-1.83-.79-1.39 0-2.52 1.13-2.52 2.52 0 .2.02.39.06.58-2.1-.11-3.96-1.11-5.2-2.64-.22.38-.34.83-.34 1.3 0 .9.46 1.69 1.15 2.15-.42-.01-.82-.13-1.17-.33v.03c0 1.26.89 2.31 2.07 2.55-.22.06-.45.09-.69.09-.17 0-.33-.02-.49-.05.33 1.03 1.29 1.78 2.43 1.8-1.12.88-2.53 1.41-4.06 1.41-.26 0-.52-.02-.78-.06 1.44.92 3.15 1.46 4.99 1.46 5.99 0 9.27-4.96 9.27-9.27 0-.14 0-.28-.01-.42.64-.46 1.19-1.03 1.63-1.68-.59.26-1.22.43-1.88.51.68-.41 1.2-1.04.97-2.34.45-3.34z"/></svg>
                        </a>
                        <a href="https://linkedin.com" target="_blank" class="text-blue-500 hover:text-blue-600">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-2.5-12.5h-2v8h2v-8zm-.75 1.5c-.69 0-1.25.56-1.25 1.25s.56 1.25 1.25 1.25 1.25-.56 1.25-1.25-.56-1.25-1.25-1.25zm7.75 7.25c0 1.38-.94 2.5-2.5 2.5h-2.5v-8h2.5c1.38 0 2.5 1.12 2.5 2.5zm-2.5-.5c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z"/></svg>
                        </a>
                    </div>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.019189165556!2d-122.419415684681!3d37.77492977975918!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMznCsDQ2JzI5LjciTiAxMjLCsDI1JzA5LjkiVw!5e0!3m2!1sen!2sus!4v1634567891234" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy" class="rounded-lg"></iframe>
                </div>

                <!-- Contact Form -->
                <div class="bg-gray-50 p-8 rounded-lg shadow-lg card-hover" data-aos="fade-up">
                    <h2 class="text-2xl font-semibold mb-4">Send Us a Message</h2>
                    <form id="contact-form" action="/contact/submit" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="mb-6">
                            <label for="name" class="block text-gray-700 font-semibold mb-2">Full Name</label>
                            <input type="text" id="name" name="name" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your name" required>
                            <p class="text-red-500 text-sm mt-1 hidden" id="name-error">Please enter a valid name (minimum 2 characters).</p>
                        </div>
                        <div class="mb-6">
                            <label for="email" class="block text-gray-700 font-semibold mb-2">Email Address</label>
                            <input type="email" id="email" name="email" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your email" required>
                            <p class="text-red-500 text-sm mt-1 hidden" id="email-error">Please enter a valid email address.</p>
                        </div>
                        <div class="mb-6">
                            <label for="subject" class="block text-gray-700 font-semibold mb-2">Subject</label>
                            <input type="text" id="subject" name="subject" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter subject" required>
                            <p class="text-red-500 text-sm mt-1 hidden" id="subject-error">Please enter a subject (minimum 3 characters).</p>
                        </div>
                        <div class="mb-6">
                            <label for="message" class="block text-gray-700 font-semibold mb-2">Message</label>
                            <textarea id="message" name="message" rows="6" class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your message" required></textarea>
                            <p class="text-red-500 text-sm mt-1 hidden" id="message-error">Please enter a message (minimum 10 characters).</p>
                        </div>
                        <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition" id="submit-button">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-gray-800 text-center mb-12" data-aos="fade-up">Frequently Asked Questions</h2>
            <div class="max-w-3xl mx-auto">
                <div class="faq-item bg-white p-6 rounded-lg shadow-lg mb-4">
                    <h3 class="text-xl font-semibold mb-2 cursor-pointer" onclick="toggleFAQ(this, 'How do I access digital books?', 'I would like more information on accessing digital books and audiobooks.')">How do I access digital books?</h3>
                    <p class="text-gray-600 hidden">Sign up for a free account, log in, and browse our digital library section to access e-books and audiobooks instantly.</p>
                </div>
                <div class="faq-item bg-white p-6 rounded-lg shadow-lg mb-4">
                    <h3 class="text-xl font-semibold mb-2 cursor-pointer" onclick="toggleFAQ(this, 'Can I reserve physical books?', 'I need assistance with reserving physical books online.')">Can I reserve physical books?</h3>
                    <p class="text-gray-600 hidden">Yes, you can reserve physical books online and pick them up at your nearest library branch.</p>
                </div>
                <div class="faq-item bg-white p-6 rounded-lg shadow-lg mb-4">
                    <h3 class="text-xl font-semibold mb-2 cursor-pointer" onclick="toggleFAQ(this, 'What are the membership fees?', 'Please provide details about the membership fees and premium options.')">What are the membership fees?</h3>
                    <p class="text-gray-600 hidden">Basic membership is free, with premium options available for extended access. Visit our pricing page for details.</p>
                </div>
                <div class="faq-item bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-semibold mb-2 cursor-pointer" onclick="toggleFAQ(this, 'How can I join community events?', 'I am interested in joining your community events and book clubs.')">How can I join community events?</h3>
                    <p class="text-gray-600 hidden">Check our events calendar on the blog page and register for upcoming book clubs or author talks.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Live Chat Placeholder -->
    <section class="py-16 live-chat text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">Need Instant Help?</h2>
            <p class="text-lg mb-8">Chat with our support team for quick assistance with your library needs.</p>
            <a href="#live-chat" class="bg-white text-blue-500 px-8 py-4 rounded-full hover:bg-gray-100 transition text-lg">Start Live Chat</a>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-4">© 2025 eLibrary. All rights reserved.</p>
            <div class="space-x-4">
                <a href="/" class="py-2 hover:text-blue-400">Home</a>
                <a href="/about" class="py-2 hover:text-blue-400">About Us</a>
                <a href="/blog" class="py-2 hover:text-blue-400">Blog</a>
                <a href="/contact" class="py-2 hover:text-blue-400">Contact</a>
                <a href="/privacy" class="hover:text-blue-400">Privacy Policy</a>
                <a href="/terms" class="hover:text-blue-400">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 1000 });

        function toggleMenu() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        }

        function toggleFAQ(element, subject, message) {
            const answer = element.nextElementSibling;
            answer.classList.toggle('hidden');
            document.getElementById('subject').value = subject;
            document.getElementById('message').value = message;
            document.getElementById('contact-form').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        document.getElementById('contact-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Reset error messages
            document.getElementById('name-error').classList.add('hidden');
            document.getElementById('email-error').classList.add('hidden');
            document.getElementById('subject-error').classList.add('hidden');
            document.getElementById('message-error').classList.add('hidden');
            document.getElementById('form-messages').innerHTML = '';

            // Get form values
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const subject = document.getElementById('subject').value.trim();
            const message = document.getElementById('message').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Client-side validation
            let isValid = true;

            if (!name || name.length < 2) {
                document.getElementById('name-error').textContent = 'Please enter a valid name (minimum 2 characters).';
                document.getElementById('name-error').classList.remove('hidden');
                isValid = false;
            }
            if (!email || !emailRegex.test(email)) {
                document.getElementById('email-error').textContent = 'Please enter a valid email address.';
                document.getElementById('email-error').classList.remove('hidden');
                isValid = false;
            }
            if (!subject || subject.length < 3) {
                document.getElementById('subject-error').textContent = 'Please enter a subject (minimum 3 characters).';
                document.getElementById('subject-error').classList.remove('hidden');
                isValid = false;
            }
            if (!message || message.length < 10) {
                document.getElementById('message-error').textContent = 'Please enter a message (minimum 10 characters).';
                document.getElementById('message-error').classList.remove('hidden');
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            // Disable submit button and show loading state
            const submitButton = document.getElementById('submit-button');
            const originalButtonHTML = submitButton.innerHTML;
            submitButton.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline-block" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3.5-3.5L12 2a10 10 0 00-10 10h4z"></path></svg> Sending...';
            submitButton.disabled = true;

            // Send form data to server
            fetch('/contact/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ name, email, subject, message })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || `HTTP error! Status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showMessage('bg-green-100 border-green-400 text-green-700', data.message || 'Message sent successfully!');
                    document.getElementById('contact-form').reset();
                } else {
                    showMessage('bg-red-100 border-red-400 text-red-700', data.message || 'Failed to send message.');
                    if (data.errors) {
                        for (const [field, messages] of Object.entries(data.errors)) {
                            const errorElement = document.getElementById(`${field}-error`);
                            if (errorElement) {
                                errorElement.textContent = messages[0]; // Display first error message
                                errorElement.classList.remove('hidden');
                            }
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                showMessage('bg-red-100 border-red-400 text-red-700', error.message || 'An error occurred while sending your message.');
            })
            .finally(() => {
                submitButton.innerHTML = originalButtonHTML;
                submitButton.disabled = false;
            });
        });

        function showMessage(className, message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `border px-4 py-3 rounded mb-4 ${className}`;
            messageDiv.textContent = message;
            document.getElementById('form-messages').prepend(messageDiv);
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>