<!-- resources/views/user/book_details.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="p-6 bg-white shadow rounded">
        <h2 class="text-2xl font-semibold">{{ $book->title }}</h2>
        <p class="text-gray-700">Author: {{ $book->author }}</p>
        <p class="text-gray-700">Category: {{ $book->category }}</p>
        <p class="text-gray-700">{{ $book->description }}</p>
    </div>
@endsection
