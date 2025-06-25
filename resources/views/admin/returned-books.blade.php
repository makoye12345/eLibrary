<!-- resources/views/admin/returned-books.blade.php -->

@extends('layouts.user') <!-- Badilisha kama unatumia layout nyingine -->

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Returned Books</h1>

    @if($books->isEmpty())
        <p>No books have been returned yet.</p>
    @else
        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Title</th>
                    <th class="border px-4 py-2">Author</th>
                    <th class="border px-4 py-2">Returned At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $index => $book)
                    <tr>
                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border px-4 py-2">{{ $book->title }}</td>
                        <td class="border px-4 py-2">{{ $book->author }}</td>
                        <td class="border px-4 py-2">{{ $book->updated_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
