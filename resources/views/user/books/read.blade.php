@extends('layouts.user')

@section('title', isset($book) ? 'Reading: ' . $book->title : 'Reading')

@section('styles')
    <link href="{{ asset('css/read.css') }}" rel="stylesheet">
    <style>
        /* Additional in-page styles */
        .pdf-protected {
            pointer-events: none;
            user-select: none;
        }
        #pdfViewer {
            width: 100%;
            height: 800px;
            border: none;
        }
    </style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        @if (isset($book))
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">{{ $book->title }}</h1>
                @if (isset($borrow))
                    <span class="text-sm text-gray-500">Due: {{ $borrow->due_at->format('M d, Y') }}</span>
                @else
                    <span class="text-sm text-gray-500">No borrow information available</span>
                @endif
            </div>

            <div class="pdf-viewer-container">
                <iframe src="{{ route('user.books.stream', $book->id) }}" 
                        class="no-download"
                        id="pdfViewer"
                        allowfullscreen>
                    Your browser doesn't support PDF viewing.
                </iframe>
            </div>
        @else
            <p class="text-red-500">Error: Book not found.</p>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewer = document.getElementById('pdfViewer');
    
    // Disable right-click inside iframe
    viewer.addEventListener('load', function() {
        try {
            const iframeDoc = viewer.contentDocument || viewer.contentWindow.document;
            iframeDoc.addEventListener('contextmenu', e => e.preventDefault());
            
            // Add protected class to iframe body
            iframeDoc.body.classList.add('pdf-protected');
        } catch (e) {
            console.log('Security restrictions apply: ', e);
        }
    });

    // Block keyboard shortcuts (Ctrl+S, Ctrl+P)
    window.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && 
           .force, I can suggest ways to enhance PDF security or troubleshoot specific issues. Let me know!