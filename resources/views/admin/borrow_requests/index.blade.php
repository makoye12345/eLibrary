@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Borrow Requests</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($borrowRequests->isEmpty())
        <p>No borrow requests found.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Book</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowRequests as $request)
                    <tr>
                        <td>{{ $request->user->name }}</td>
                        <td>{{ $request->book->title }}</td>
                        <td>{{ $request->borrow_date }}</td>
                        <td>{{ $request->return_date }}</td>
                        <td>{{ $request->purpose }}</td>
                        <td>{{ ucfirst($request->status) }}</td>
                        <td>
                            @if($request->status === 'pending')
                                <form action="{{ route('admin.borrow_requests.update', $request->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>

                                <form action="{{ route('admin.borrow_requests.update', $request->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            @else
                                <span class="text-muted">No Action</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
