@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Return Books</h1>

    @foreach ($borrowedBooks as $borrow)
        <div class="bg-white p-4 rounded shadow mb-3">
            <p><strong>Book:</strong> {{ $borrow->book->title }}</p>
            <p><strong>User:</strong> {{ $borrow->user->name }}</p>
            <p><strong>Borrowed at:</strong> {{ $borrow->borrowed_at }}</p>

            <form action="{{ route('return.books.update', $borrow->id) }}" method="POST" class="mt-2">
                @csrf
                @method('PUT')
                <button class="px-4 py-2 bg-green-600 text-white rounded">Mark as Returned</button>
            </form>
        </div>
    @endforeach
@endsection
