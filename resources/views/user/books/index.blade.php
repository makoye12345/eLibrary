@extends('layouts.user')

@section('title', 'Books List')
@section('header', 'All Books')

@section('content')
<div class="container mx-auto px-4 py-8 lg:ml-56">
    <!-- Flash Messages -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Form -->
    <div class="mb-6">
        <form action="{{ route('books.search') }}" method="GET" class="flex gap-2">
            <input type="text" name="query" value="{{ request('query') }}" placeholder="Search by title, author, or category" class="w-full px-4 py-2 rounded-md border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500">
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Search</button>
        </form>
    </div>

    <!-- Book List -->
    @if($books->isEmpty())
        <p class="text-gray-500">No books found.</p>
    @else
        @php
            $borrowedToday = auth()->user()->borrowedBooks()
                ->whereDate('created_at', today())
                ->where('returned', false)
                ->count();
            $maxBooksPerDay = 3;
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($books as $book)
                <div class="bg-white p-6 rounded-lg shadow flex">
                    <!-- Book Cover -->
                    <div class="mr-4">
                        @if($book->cover_image_path)
                            <a href="{{ route('books.show', $book->id) }}">
                                <img src="{{ asset('storage/' . $book->cover_image_path) }}" alt="{{ $book->title }}" class="w-24 h-32 object-cover rounded" onerror="this.src='https://via.placeholder.com/96x128?text=No+Image';">
                            </a>
                        @else
                            <a href="{{ route('books.show', $book->id) }}">
                                <div class="w-24 h-32 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            </a>
                        @endif
                    </div>
                    
                    <!-- Book Details -->
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">
                            <a href="{{ route('books.show', $book->id) }}" class="hover:underline">{{ $book->title }}</a>
                        </h3>
                        <p class="text-sm text-gray-600 mb-1">Author: {{ $book->author ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-600 mb-1">Publisher: {{ $book->publisher->name ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-600 mb-2">Category: {{ $book->category->name ?? 'Uncategorized' }}</p>
                        
                        @if($book->file_path)
                            <p class="text-sm text-green-600 mb-3">
                                <i class="fas fa-file-pdf mr-1"></i> PDF Available
                            </p>
                        @endif
                        
                        <!-- Borrow Button Section -->
                        <div class="mt-2">
                            @if($book->is_available && $book->status === 'available')
                                @if($borrowedToday >= $maxBooksPerDay)
                                    <!-- Disabled Button with Tooltip -->
                                    <button 
                                        onclick="showBorrowLimitMessage()"
                                        class="borrow-btn px-4 py-2 bg-gray-400 text-white rounded-md flex items-center w-full justify-center cursor-not-allowed relative group"
                                        data-book-id="{{ $book->id }}"
                                    >
                                        <i class="fas fa-book mr-2"></i> Borrow
                                        <span class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 bottom-full mb-2 whitespace-nowrap">
                                            You've borrowed {{ $borrowedToday }}/{{ $maxBooksPerDay }} books today
                                        </span>
                                    </button>
                                @else
                                    <!-- Active Borrow Button -->
                                    <form action="{{ route('user.books.borrow', $book->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md flex items-center w-full justify-center">
                                            <i class="fas fa-book mr-2"></i> Borrow
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span class="px-4 py-2 bg-gray-400 text-white rounded-md inline-block">Not Available</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    @endif
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    .borrow-btn:hover span {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Function to show borrow limit message
    function showBorrowLimitMessage() {
        Swal.fire({
            icon: 'warning',
            title: 'Limit Imefikwa',
            html: `
                <div class="text-left">
                    <p class="mb-2">Umefikia kikomo cha vitabu 3 kwa siku ya leo.</p>
                    <p class="text-sm text-gray-600">Tafadhali rudisha baadhi ya vitabu au subiri kesho.</p>
                </div>
            `,
            confirmButtonText: 'Sawa',
            confirmButtonColor: '#3085d6',
            customClass: {
                popup: 'text-left'
            }
        });
    }

    // Alternative for browsers without SweetAlert
    function fallbackBorrowLimitMessage() {
        alert("Umefikia kikomo cha vitabu 3 kwa siku ya leo. Tafadhali subiri kesho!");
    }
</script>
@endpush
@endsection