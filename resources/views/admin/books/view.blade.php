@extends('layouts.admin')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">View Book: {{ $book->title }}</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if ($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }} cover" class="img-fluid mb-3" style="max-width: 200px; max-height: 300px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default-book-cover.png') }}" alt="No cover" class="img-fluid mb-3" style="max-width: 200px; max-height: 300px; object-fit: cover;">
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3>{{ $book->title }}</h3>
                        <p><strong>Author:</strong> {{ $book->author }}</p>
                        <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
                        <p><strong>Category:</strong> {{ $book->category ? $book->category->name : 'Not assigned' }}</p>
                        <p><strong>Description:</strong> {{ $book->description ?? 'No description provided' }}</p>
                        @if ($book->file_path)
                            <p><strong>Book File:</strong>
                                <a href="{{ asset('storage/' . $book->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View PDF</a>
                            </p>
                        @else
                            <p><strong>Book File:</strong> No file uploaded</p>
                        @endif
                        <div class="mt-4">
                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-secondary">Edit Book</a>
                            <a href="{{ route('admin.books.index') }}" class="btn btn-outline-primary">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection