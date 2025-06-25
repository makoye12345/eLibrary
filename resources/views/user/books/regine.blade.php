@extends('layouts.user')

@section('title', $book->title)
@section('header', $book->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow flex flex-col md:flex-row">
        <div class="md:w-1/3 mb-4 md:mb-0">
            @if($book->cover_image_path)
                <img src="{{ asset('storage/' . $book->cover_image_path) }}" 
                     alt="{{ $book->title }}" 
                     class="w-full h-64 object-cover rounded">
            @else
                <div class="w-full h-64 bg-gray-200 rounded flex items-center justify-center">
                    <span class="text-gray-500">No Cover</span>
                </div>
            @endif
        </div>
        <div class="md:w-2/3 md:pl-6">
            <h2 class="text-2xl font-semibold text-gray-800">{{ $book->title }}</h2>
            <p class="text-gray-600">By {{ $book->author ?? 'Unknown' }}</p>
            <p class="text-gray-600">Publisher: {{ $book->publisher->name ?? 'Unknown' }}</p>
            <p class="text-gray-600">Category: {{ $book->category->name ?? 'Unknown' }}</p>
            <p class="text-gray-600 mt-2">{{ $book->description ?? 'No description available.' }}</p>
            <p class="text-gray-600 mt-2">Status: 
                @if($book->is_available)
                    <span class="text-green-600">Available</span>
                @else
                    <span class="text-red-600">Borrowed</span>
                @endif
            </p>

            <div class="mt-4 flex gap-2">
                @if($hasBorrowed || auth()->user()->role === 'admin')
                    <a href="{{ route('user.books.read', $book->id) }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        <i class="fas fa-book-reader mr-2"></i> Read
                    </a>
                @endif

                @if($book->is_available && !$hasBorrowed && auth()->user()->role !== 'admin')
                    <a href="{{ route('user.books.borrow', $book->id) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-book mr-2"></i> Borrow
                    </a>
                @elseif(!$book城乡->is_available && !$hasBorrowed && !$hasReserved && auth()->user()->role !== 'admin')
                    <form action="{{ route('user.books.reserve', $book->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                            <i class="fas fa-clock mr-2"></i> Reserve
                        </button>
                    </form>
                @elseif($hasReserved)
                    <p class="text-gray-600">You have reserved this book.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection