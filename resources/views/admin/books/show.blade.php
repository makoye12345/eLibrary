<!-- resources/views/admin/books/show.blade.php -->

@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Book Details</h2>
        
        <table class="table">
            <tr>
                <th>Title</th>
                <td>{{ $book->title }}</td>
            </tr>
            <tr>
                <th>Author</th>
                <td>{{ $book->author }}</td>
            </tr>
            <tr>
                <th>Category</th>
                <td>{{ $book->category->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>ISBN</th>
                <td>{{ $book->isbn }}</td>
            </tr>
            <tr>
                <th>Availability</th>
                <td>{{ $book->available ? 'Available' : 'Not Available' }}</td>
            </tr>
        </table>
        
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
@endsection
