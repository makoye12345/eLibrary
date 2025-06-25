<!DOCTYPE html>
<html>
<head>
    <title>Manage Borrowings</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Manage Borrowings</h1>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Status Filter -->
    <form method="GET" action="{{ route('admin.borrowings.index') }}">
        <label for="status">Filter by Status:</label>
        <select name="status" id="status" onchange="this.form.submit()">
            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All</option>
            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="overdue" {{ $status == 'overdue' ? 'selected' : '' }}>Overdue</option>
            <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
            <option value="returned" {{ $status == 'returned' ? 'selected' : '' }}>Returned</option>
        </select>
    </form>

    <!-- Borrowings Table -->
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Book</th>
                <th>Status</th>
                <th>Borrowed At</th>
                <th>Due Date</th>
                <th>Returned At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowings as $borrowing)
                <tr>
                    <td>{{ $borrowing->user->name }}</td>
                    <td>{{ $borrowing->book->title }}</td>
                    <td>
                        @if ($borrowing->status == 'approved' && $borrowing->due_date < now())
                            <span style="color: red;">Overdue</span>
                        @else
                            {{ ucfirst($borrowing->status) }}
                        @endif
                    </td>
                    <td>{{ $borrowing->borrowed_at ? $borrowing->borrowed_at->format('Y-m-d') : '-' }}</td>
                    <td>{{ $borrowing->due_date ? $borrowing->due_date->format('Y-m-d') : '-' }}</td>
                    <td>{{ $borrowing->returned_at ? $borrowing->returned_at->format('Y-m-d') : '-' }}</td>
                    <td>
                        @if ($borrowing->status == 'pending')
                            <form action="{{ route('admin.borrowings.approve', $borrowing) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Approve</button>
                            </form>
                            <form action="{{ route('admin.borrowings.reject', $borrowing) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Reject</button>
                            </form>
                        @elseif ($borrowing->status == 'approved')
                            <form action="{{ route('admin.borrowings.return', $borrowing) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit">Mark Returned</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $borrowings->links() }}
</body>
</html>