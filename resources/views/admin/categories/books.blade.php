<!DOCTYPE html>
<html>
<head>
    <title>Books in Category</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Books in {{ $category->name }}</h1>

        <a href="{{ route('categories.index') }}"
           class="bg-gray-500 text-white p-2 rounded mb-4 inline-block">Back to Categories</a>

        @if ($books->isNotEmpty())
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Book Title</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        <tr>
                            <td class="border p-2">{{ $book->title }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No books in this category.</p>
        @endif
    </div>
</body>
</html>