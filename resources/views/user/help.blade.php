@extends('layouts.user')

@section('title', __('Help and Support'))
@section('header', __('Help and Support'))

@section('content')
<div class="container mx-auto px-8 py-6">
    <div class="bg-white px-8 py-6 rounded-lg shadow help-card">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Help and Support</h2>
        <p class="text-gray-600 mb-6">Please review our Frequently Asked Questions. We try to keep them updated. Below are direct links to the main FAQ categories.</p>

        <ul class="space-y-2 list-disc pl-8">
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-about">Learn About Library</a></li>
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-daisy">Learn About DAISY/Print Disabled Books on Library</a></li>
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-reading">How to Read Books on Library</a></li>
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-borrow">How to Borrow Books Through Library</a></li>
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-lists">How to Use the List and Reading Log Features</a></li>
            <a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-editing">How to Edit Library</a></li>
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-data">About Using Library Data</a></li>
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-trouble">Troubleshooting Common Problems</a></li>
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-account">How to Delete Your Account</a></li>
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-search">Library Search Tips</a></li>
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-audiobooks">How to Listen to Audiobooks</a></li>
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="faq-bookmarks">How to Create Bookmarks, Add Notes, and Search Text in Books</a></li>
        </ul>

        <h3 class="text-lg font-semibold text-gray-800 mt-8 mb-4">Contact Us</h3>
        <p class="text-gray-600 mb-4">There are several ways to stay connected with us:</p>
        <ul class="space-y-2 list-disc pl-8">
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="contact-blog">Read the Library Blog</a></li>
            <li><a href="#" class="text-blue-600 underline hover:text-blue-800" data-link="contact-twitter">Follow us on Twitter - @library</a></li>
            <li><a href="mailto:support@yourlibrary.com" class="text-blue-600 underline hover:text-blue-800" data-link="contact-email">Send an email to support@yourlibrary.com</a></li>
        </ul>
    </div>
</div>
@endsection

@section('styles')
<style>
    .help-card {
        transition: box-shadow 0.3s ease;
    }
    .help-card:hover {
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function() {
            var links = document.querySelectorAll('a[data-link]');
            links.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    console.log('Help link clicked: ' + link.getAttribute('data-link') + ' - URL: ' + link.href);
                });
            });
        });
</script>