
@extends('layouts.user')

@section('title', 'Reserve a Book')
@section('header', 'Reserve a Book')

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

    <div class="bg-white p-8 rounded-lg shadow">
        <h3 class="text-xl font-semibold text-gray-700 mb-6">Select a Book to Reserve</h3>
        
        <form action="{{ route('reservations.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="book_id" class="block text-sm font-medium text-gray-700">Select Book</label>
                <select name="book_id" id="book_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Select a Book --</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}">{{ $book->title }} by {{ $book->author ?? 'Unknown' }}</option>
                    @endforeach
                </select>
                @error('book_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-book mr-2"></i> Reserve Book
                </button>
            </div>
        </form>
    </div>
</div>
@endsection