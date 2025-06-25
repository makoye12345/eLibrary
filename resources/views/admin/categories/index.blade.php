@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manage Categories</h1>
        <a href="{{ route('admin.categories.create') }}"
           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition duration-200">
            Add New Category
        </a>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="p-4 mb-6 rounded bg-green-100 text-green-700 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="p-4 mb-6 rounded bg-red-100 text-red-700 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search and Filter -->
    <form class="mb-6 flex flex-col md:flex-row gap-4" method="GET" action="{{ route('admin.categories.index') }}">
        <div class="flex-grow">
            <input type="text" name="search" value="{{ old('search', $search ?? '') }}" 
                   placeholder="Search categories..."
                   class="w-full border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex items-center gap-4">
            <select name="filter" class="border border-gray-300 p-2 rounded focus:ring-2 focus:ring-blue-500">
                <option value="name" {{ ($filter ?? 'name') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                <option value="books" {{ ($filter ?? 'name') == 'books' ? 'selected' : '' }}>Sort by Book Count</option>
            </select>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-200">
                Search
            </button>
        </div>
    </form>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Books</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('categories.books', $category->id) }}"
                               class="text-blue-600 hover:text-blue-800 hover:underline font-medium">
                                {{ $category->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $category->parent ? $category->parent->name : 'None' }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-600 max-w-xs truncate">{{ $category->description }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $category->books_count > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $category->books_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $category->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                   class="text-yellow-600 hover:text-yellow-900">
                                    Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this category?')"
                                            class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No categories found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($categories instanceof \Illuminate\Pagination\LengthAwarePaginator && $categories->hasPages())
        <div class="mt-6">
            {{ $categories->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endsection