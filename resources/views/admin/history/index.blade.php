@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📚 Borrowing History</h2>
        <div class="d-flex">
            <form action="{{ route('admin.borrowing-history.index') }}" method="GET" class="me-2">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by user, book, or status..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            <a href="{{ route('admin.borrowing-history.index') }}" class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i> Reset
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>👤 User</th>
                            <th>📖 Book</th>
                            <th>📅 Borrow Date</th>
                            <th>📅 Return Date</th>
                            <th>📊 Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                        <tr>
                            <td>{{ $borrowing->id }}</td>
                            <td>
                                @if($borrowing->user)
                                    <a href="{{ route('admin.users.edit', $borrowing->user_id) }}">
                                        {{ $borrowing->user->name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($borrowing->book)
                                    {{ $borrowing->book->title }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $borrowing->borrow_date->format('Y-m-d') }}</td>
                            <td>{{ $borrowing->return_date ? $borrowing->return_date->format('Y-m-d') : 'N/A' }}</td>
                            <td>
                                <span class="badge 
                                    {{ $borrowing->status == 'borrowed' ? 'bg-primary' : 
                                       ($borrowing->status == 'returned' ? 'bg-success' : 'bg-danger') }}">
                                    {{ ucfirst($borrowing->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No borrowing records found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($borrowings->hasPages())
        <div class="card-footer">
            {{ $borrowings->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .table-dark {
        background-color: #343a40;
        color: white;
    }
    .table-responsive {
        min-height: 300px;
    }
    .badge {
        font-size: 0.9em;
        padding: 0.5em 1em;
    }
</style>
@endsection