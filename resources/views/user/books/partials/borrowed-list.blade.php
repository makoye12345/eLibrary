@if($borrows->isEmpty())
    <div class="bg-white p-8 rounded-lg shadow text-center">
        <i class="fas fa-book-open text-4xl text-gray-400 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700">No Borrowed Books</h3>
        <p class="text-gray-500 mt-2">You currently don't have any borrowed books.</p>
        <a href="{{ route('books.search') }}" 
           class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Browse Available Books
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($borrows as $borrow)
            <div class="book-card bg-white p-6 rounded-lg shadow flex flex-col @if($borrow->due_at->isPast()) border-l-4 border-red-500 @endif">
                <div class="flex mb-4">
                    @if($borrow->book->cover_image_path)
                        <img src="{{ asset('storage/' . $borrow->book->cover_image_path) }}" 
                             alt="{{ $borrow->book->title }}" 
                             class="w-24 h-32 object-cover mr-4 rounded">
                    @else
                        <div class="w-24 h-32 bg-gray-200 mr-4 rounded flex items-center justify-center">
                            <span class="text-gray-500">No Image</span>
                        </div>
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
                    
                    <!-- Return Button -->
                    <form action="{{ route('user.books.return', $borrow->id) }}" 
                          method="POST" 
                          class="flex-1 return-form"
                          data-borrow-id="{{ $borrow->id }}">
                        @csrf
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            <i class="fas fa-undo mr-2"></i> Return
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-6">
        {{ $borrows->links() }}
    </div>
@endif