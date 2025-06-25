@extends('layouts.user')

@section('title', 'My Borrowed Books')
@section('header', 'Books I Have Borrowed')

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

    @if($borrows->isEmpty())
        <div class="bg-white p-8 rounded-lg shadow text-center">
            <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700">No Borrowed Books</h3>
            <p class="text-gray-500 mt-2">You currently don't have any borrowed books.</p>
            <a href="{{ route('books.search') }}" 
               class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Browse Available Books
            </a>
        </div>
    @else
        <div id="borrowedBooksContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($borrows as $borrow)
                <div id="borrow-{{ $borrow->id }}" class="bg-white p-6 rounded-lg shadow flex flex-col transition-all duration-300 @if($borrow->due_at->isPast()) border-l-4 border-red-500 @endif">
                    <div class="flex mb-4">
                        @if($borrow->book->cover_image_path)
                            <a href="{{ route('user.books.read', $borrow->book->id) }}" class="block">
                                <img src="{{ asset('storage/' . $borrow->book->cover_image_path) }}" 
                                     alt="{{ $borrow->book->title }}" 
                                     class="w-24 h-32 object-cover mr-4 rounded hover:opacity-80 transition cursor-pointer">
                            </a>
                        @else
                            <a href="{{ route('user.books.read', $borrow->book->id) }}" class="block">
                                <div class="w-24 h-32 bg-gray-200 mr-4 rounded flex items-center justify-center hover:bg-gray-300 transition cursor-pointer">
                                    <span class="text-gray-500">No Cover</span>
                                </div>
                            </a>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $borrow->book->title }}
                            </h3>
                            <p class="text-sm text-gray-600">By {{ $borrow->book->author ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-600">Borrowed: {{ $borrow->borrowed_at->format('M d, Y') }}</p>
                            <p class="text-sm @if($borrow->due_at->isPast()) text-red-600 font-medium @else text-gray-600 @endif">
                                Due: {{ $borrow->due_at->format('M d, Y') }}
                                @if($borrow->due_at->isPast()) (Overdue) @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-auto flex gap-2">
                        <!-- Read Button -->
                        <a href="{{ route('user.books.read', $borrow->book->id) }}" 
                           class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md text-center hover:bg-green-700 transition">
                            <i class="fas fa-book-reader mr-2"></i> Read
                        </a>
                        
                        <!-- Return Button with AJAX -->
                        <button onclick="returnBook({{ $borrow->id }})" 
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-undo mr-2"></i> Return
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $borrows->links() }}
        </div>
    @endif
</div>

<script>
function returnBook(borrowId) {
    if (!confirm('Are you sure you want to return this book?')) {
        return;
    }

    const bookCard = document.getElementById(`borrow-${borrowId}`);
    if (!bookCard) {
        console.error('Book card not found for borrow ID:', borrowId);
        return;
    }

    const returnButton = bookCard.querySelector('button');
    const originalButtonHTML = returnButton.innerHTML;

    // Show loading state
    returnButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Returning...';
    returnButton.disabled = true;
    bookCard.classList.add('opacity-50', 'cursor-not-allowed');

    // Make AJAX request
    fetch(`/user/books/return/${borrowId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Animate card removal
            bookCard.style.transition = 'all 0.3s ease';
            bookCard.style.opacity = '0';
            bookCard.style.transform = 'translateY(-20px)';
            bookCard.style.height = '0';
            bookCard.style.margin = '0';
            bookCard.style.padding = '0';
            bookCard.style.overflow = 'hidden';

            setTimeout(() => {
                bookCard.remove();

                // Check if container is empty
                const container = document.getElementById('borrowedBooksContainer');
                if (container && container.children.length === 0) {
                    // Create success message
                    const successDiv = document.createElement('div');
                    successDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
                    successDiv.textContent = data.message || 'Book returned successfully!';

                    // Create empty state
                    const emptyState = document.createElement('div');
                    emptyState.className = 'bg-white p-8 rounded-lg shadow text-center';
                    emptyState.innerHTML = `
                        <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700">No Borrowed Books</h3>
                        <p class="text-gray-500 mt-2">You currently don't have any borrowed books.</p>
                        <a href="{{ route('books.search') }}" 
                           class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Browse Available Books
                        </a>
                    `;

                    // Replace container content
                    container.replaceWith(emptyState);
                    emptyState.before(successDiv);

                    // Remove success message after 5 seconds
                    setTimeout(() => {
                        successDiv.remove();
                    }, 5000);
                }
            }, 300);
        } else {
            throw new Error(data.message || 'Failed to return book');
        }
    })
    .catch(error => {
        console.error('Error returning book:', error);

        // Show error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
        errorDiv.textContent = `Error: ${error.message}`;
        document.querySelector('.container').prepend(errorDiv);

        // Reset button and card state
        returnButton.innerHTML = originalButtonHTML;
        returnButton.disabled = false;
        bookCard.classList.remove('opacity-50', 'cursor-not-allowed');

        // Remove error message after 5 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    });
}
</script>
@endsection