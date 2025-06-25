@extends('layouts.user')

@section('title', 'Borrow Book')
@section('header', 'Borrow Book Confirmation')

@section('content')
<div class="container mx-auto px-4 py-8 lg:ml-56">
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

    <div class="bg-white p-6 rounded-lg shadow max-w-2xl mx-auto">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-book mr-2 text-green-600"></i> Borrow Book Confirmation
        </h2>

        <form action="{{ route('user.books.borrow.store', $book->id) }}" method="POST">
            @csrf

            <!-- Book Details -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-book-open mr-2"></i> Book Details
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Title</label>
                        <input type="text" value="{{ $book->title }}" readonly
                               class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Author</label>
                        <input type="text" value="{{ $book->author ?? 'Unknown' }}" readonly
                               class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Category</label>
                        <input type="text" value="{{ $book->category->name ?? 'Uncategorized' }}" readonly
                               class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-user mr-2"></i> Your Information
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Name</label>
                        <input type="text" value="{{ auth()->user()->name }}" readonly
                               class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" readonly
                               class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Borrowing Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-calendar mr-2"></i> Borrowing Information
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Borrow Date</label>
                        <input type="text" value="{{ now()->format('M d, Y') }}" readonly
                               class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Return Date</label>
                        <input type="text" value="{{ now()->addDays(14)->format('M d, Y') }}" readonly
                               class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <i class="fas fa-comment mr-2"></i> Purpose (Optional)
                </h3>
                <textarea name="remarks" rows="4" placeholder="E.g., I need this for my semester project research."
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md flex items-center">
                    <i class="fas fa-check mr-2"></i> Confirm Borrowing
                </button>
                <a href="{{ route('books.search') }}" class="px-4 py-2 bg-red-600 text-white rounded-md flex items-center">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush
@endsection