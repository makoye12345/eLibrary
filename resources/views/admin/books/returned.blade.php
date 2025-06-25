@extends('layouts.admin')

@section('title', 'Returned Books - Admin Library System')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Returned Books</h1>

        <!-- Search and Filter Section -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <form action="{{ route('admin.books.returned') }}" method="GET" class="col-span-2">
                <div class="flex">
                    <input type="text" name="query" value="{{ request('query') }}" 
                           placeholder="Search by book title, user, or ISBN..." 
                           class="flex-grow px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <div class="flex justify-end">
                <a href="{{ route('admin.books.index') }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to All Books
                </a>
            </div>
        </div>

        <!-- Returned Books Table -->
        <div class="overflow-x-auto">
            @if($borrows->isEmpty())
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                No returned books found in the system.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book Details</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Kept</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($borrows as $borrow)
                        <tr class="hover:bg-gray-50">
                            <!-- Book Cover -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex-shrink-0 h-16 w-12">
                                    @if($borrow->book->cover_image_path)
                                        <img class="h-16 w-12 object-cover rounded" 
                                             src="{{ asset('storage/' . $borrow->book->cover_image_path) }}" 
                                             alt="{{ $borrow->book->title }}">
                                    @else
                                        <div class="h-16 w-12 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-book text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Book Details -->
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $borrow->book->title }}</div>
                                <div class="text-sm text-gray-500">{{ $borrow->book->author }}</div>
                                <div class="text-xs text-gray-400 mt-1">
                                    ISBN: {{ $borrow->book->isbn ?? 'N/A' }}
                                </div>
                            </td>
                            
                            <!-- User -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $borrow->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $borrow->user->email }}</div>
                            </td>
                            
                            <!-- Dates -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $borrow->borrowed_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $borrow->returned_at->format('M d, Y') }}
                            </td>
                            
                            <!-- Days Kept -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($borrow->days_kept > 30) bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $borrow->days_kept }} days
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $borrows->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }
    .pagination li {
        margin: 0 0.25rem;
    }
    .pagination .active span {
        background-color: #3b82f6;
        color: white;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
    }
    .pagination a {
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        color: #4b5563;
    }
    .pagination a:hover {
        background-color: #f3f4f6;
    }
</style>
@endpush

@push('scripts')
<script>
    // Add loading indicator for page transitions
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            // Show loading indicator if leaving current page
            if (link.href && !link.href.includes('#') && 
                !link.classList.contains('no-loader')) {
                document.getElementById('page-loader').classList.remove('hidden');
            }
        });
    });
</script>
@endpush