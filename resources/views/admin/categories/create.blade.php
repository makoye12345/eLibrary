@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Add New Category</h1>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="p-2 mb-4 rounded bg-green-100 text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="p-2 mb-4 rounded bg-red-100 text-red-700">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.categories.store') }}" class="max-w-md">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Category Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full border p-2 rounded @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1">Parent Category (Optional)</label>
            <select name="category_id" class="w-full border p-2 rounded @error('category_id') border-red-500 @enderror">
                <option value="" {{ old('category_id') === null ? 'selected' : '' }}>None</option>
                @foreach (\App\Models\Category::whereNull('category_id')->orderBy('name')->get() as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1">Description</label>
            <textarea name="description" class="w-full border p-2 rounded @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex space-x-4">
            <button type="submit"
                    class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition duration-200">
                Add Category
            </button>
            <a href="{{ route('admin.categories.index') }}"
               class="bg-gray-500 text-white p-2 rounded hover:bg-gray-600 transition duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endsection