@extends('layouts.user')

@section('title', $book->title)
@section('header', 'Book Details')

@section('content')
<div class="container mx-auto px-4 py-8 lg:ml-56">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Book Cover Image -->
            <div class="w-full md:w-1/3">
                @if($book->cover_image_path)
                    <img src="{{ asset('storage/' . $book->cover_image_path) }}" 
                         alt="{{ $book->title }}" 
                         class="w-full h-auto rounded-lg shadow-md">
                @else
                    <div class="bg-gray-200 w-full h-64 rounded-lg flex items-center justify-center">
                        <span class="text-gray-500">No Cover Image</span>
                    </div>
                @endif
            </div>

            <!-- Book Details -->
            <div class="w-full md:w-2/3">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $book->title }}</h1>
                <p class="text-lg text-gray-600 mb-4">by {{ $book->author ?? 'Unknown Author' }}</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <h3 class="font-semibold text-gray-700">Publisher</h3>
                        <p>{{ $book->publisher->name ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Category</h3>
                        <p>{{ $book->category->name ?? 'Uncategorized' }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">ISBN</h3>
                        <p>{{ $book->isbn ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700">Published Date</h3>
                        <p>{{ $book->published_at?->format('F j, Y') ?? 'Unknown' }}</p>
                    </div>
                </div>

                @if($book->description)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2">Description</h3>
                        <p class="text-gray-600">{{ $book->description }}</p>
                    </div>
                @endif

                <!-- Availability Status -->
                <div class="mb-6">
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                        {{ $book->is_available && $book->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $book->is_available && $book->status === 'available' ? 'Available' : 'Not Available' }}
                    </span>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-4">
                    @if($book->is_available && $book->status === 'available')
                        <a href="{{ route('user.books.borrow', $book->id) }}" 
                           class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center">
                            <i class="fas fa-book mr-2"></i> Borrow Book
                        </a>
                    @endif

                    <a href="{{ route('books.search') }}" 
                       class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection