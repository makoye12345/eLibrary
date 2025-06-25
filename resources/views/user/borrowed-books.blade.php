@extends('layouts.user')

@section('title', 'My Borrowed Books - Library System')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">My Borrowed Books</h2>

    <div>
        @if (!isset($borrows) || !method_exists($borrows, 'isEmpty') || $borrows->isEmpty())
            <p class="p-4 text-gray-600 text-center">No borrowed books found.</p>
        @else
            <div class="bg-white shadow rounded overflow-hidden">
                <table class="w-full text-left border">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="p-4 border-b">Cover</th>
                            <th class="p-4 border-b">Book Title</th>
                            <th class="p-4 border-b">Borrowed On</th>
                            <th class="p-4 border-b">Return Date</th>
                            <th class="p-4 border-b">Status</th>
                            <th class="p-4 border-b">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($borrows as $loan)
                            <tr class="border-t">
                                <td class="p-4">
                                    @if ($loan->book->cover_image_path)
                                        @if (!$loan->returned_at && $loan->book->file_path)
                                            <a href="{{ route('books.read', $loan->book->id) }}" target="_blank" class="nav-link" aria-label="Read {{ $loan->book->title }}">
                                                <img src="{{ asset('storage/' . $loan->book->cover_image_path) }}" alt="{{ $loan->book->title }}" class="w-16 h-24 object-cover rounded">
                                            </a>
                                        @else
                                            <img src="{{ asset('storage/' . $loan->book->cover_image_path) }}" alt="{{ $loan->book->title }}" class="w-16 h-24 object-cover rounded">
                                        @endif
                                    @else
                                        <div class="w-16 h-24 bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-gray-500 text-sm">No Image</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <a href="{{ route('book.details', $loan->book->id) }}" class="hover:underline nav-link" aria-label="View {{ $loan->book->title }}">{{ $loan->book->title }}</a>
                                </td>
                                <td class="p-4">{{ $loan->borrowed_at->format('Y-m-d') }}</td>
                                <td class="p-4">{{ $loan->due_at ? $loan->due_at->format('Y-m-d') : 'Not set' }}</td>
                                <td class="p-4">
                                    @if ($loan->returned_at)
                                        <span class="px-2 py-1 rounded bg-green-100 text-green-800">Returned</span>
                                    @else
                                        <span class="px-2 py-1 rounded bg-blue-100 text-blue-800">Borrowed</span>
                                    @endif
                                </td>
                                <td class="p-4 flex gap-2">
                                    @if (!$loan->returned_at && $loan->book->file_path)
                                        <a href="{{ route('books.read', $loan->book->id) }}" target="_blank" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm flex items-center nav-link" aria-label="Read {{ $loan->book->title }}">
                                            <i class="fas fa-book-reader mr-2"></i> Read
                                        </a>
                                    @endif
                                    @if (!$loan->returned_at)
                                        <form action="{{ route('borrowings.destroy', $loan->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to return this book?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm flex items-center" aria-label="Return {{ $loan->book->title }}">
                                                <i class="fas fa-undo mr-2"></i> Return
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $borrows->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush

@section('scripts')
<script>
    // Add preloader for form submissions and links
    document.querySelectorAll('form, .nav-link').forEach(element => {
        element.addEventListener('submit', (event) => {
            if (element.tagName === 'FORM') {
                showPreloader();
            }
        });
        if (element.classList.contains('nav-link')) {
            element.addEventListener('click', (event) => {
                event.preventDefault();
                showPreloader();
                setTimeout(() => {
                    window.location.href = element.href;
                }, 1000);
            });
        }
    });
</script>
@endsection