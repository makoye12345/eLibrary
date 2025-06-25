@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Pending Borrow Requests</h1>

    @foreach ($borrowRequests as $request)
        <div class="bg-white p-4 rounded shadow mb-4">
            <p><strong>Book:</strong> {{ $request->book->title }}</p>
            <p><strong>User:</strong> {{ $request->user->name }}</p>
            <p><strong>Requested at:</strong> {{ $request->created_at->format('d M Y') }}</p>

            <div class="flex space-x-4 mt-2">
                <form action="{{ route('admin.borrow.requests.approve', $request->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="bg-green-600 text-white px-4 py-2 rounded">Approve</button>
                </form>

                <form action="{{ route('admin.borrow.requests.reject', $request->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="bg-red-600 text-white px-4 py-2 rounded">Reject</button>
                </form>
            </div>
        </div>
    @endforeach
@endsection
