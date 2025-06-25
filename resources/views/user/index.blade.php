@extends('layouts.user')

@section('title', 'Manage Books - Library System')

@section('content')
    <div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Manage Books</h2>

        <!-- Search Form -->
        <form action="{{ route('user.books_search.index') }}" method="GET" class="mb-8">
            <div class="flex items-center gap-4">
                <input type="text" name="query" id="search" value="{{ old('query', request('query')) }}" placeholder="Search by title, author, ISBN, or category..." class="flex-1 p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" aria-label="Search Books">Search</button>
            </div>
        </form>

        <!-- Books List -->
        <div class="bg-white shadow rounded overflow-hidden">
            @if($books->isEmpty())
                <p class="p-4 text-gray-600 text-center">No books available.</p>
            @else
                <table class="w-full text-left">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="p-4">Title</th>
                            <th class="p-4">Author</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">ISBN</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                            <tr class="border-t">
                                <td class="p-4">
                                    <a href="{{ route('book.details', $book->id) }}" class="hover:underline nav-link" aria-label="View {{ $book->title }}">{{ $book->title }}</a>
                                </td>
                                <td class="p-4">{{ $book->author }}</td>
                                <td class="p-4">{{ $book->category->name ?? 'N/A' }}</td>
                                <td class="p-4">{{ $book->isbn }}</td>
                                <td class="p-4 flex gap-2">
                                    <form action="{{ route('books.borrow', $book->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm" aria-label="Borrow {{ $book->title }}">Borrow</button>
                                    </form>
                                    <form action="{{ route('books.cart.add', $book->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-yellow-600 text-white rounded hover:bg-yellow-700 text-sm" aria-label="Add {{ $book->title }} to Cart">Add to Cart</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Add preloader for form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                showPreloader();
            });
        });
    </script>
@endsection