@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <h1 class="mb-4">Manage Borrowings</h1>

    <!-- Add New Borrowing Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5>Add New Borrowing</h5>
            <form action="{{ route('admin.borrowings.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <select class="form-control" name="book_id" required>
                        <option value="">Select Book</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}">{{ $book->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="borrower_id" required>
                        <option value="">Select Borrower</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="borrowed_at" required>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="returned_at">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>Add Borrowing
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Borrowings Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Book Title</th>
                        <th>Borrower</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowings as $borrowing)
                        <tr>
                            <td>{{ $borrowing->book ? $borrowing->book->title : 'Unknown Book' }}</td>
                            <td>{{ $borrowing->borrower ? $borrowing->borrower->name : 'Unknown Borrower' }}</td>
                            <td>{{ \Carbon\Carbon::parse($borrowing->borrowed_at)->format('Y-m-d') }}</td>
                            <td>{{ $borrowing->returned_at ? \Carbon\Carbon::parse($borrowing->returned_at)->format('Y-m-d') : 'Not Returned' }}</td>
                            <td>{{ $borrowing->returned_at ? 'Returned' : 'Borrowed' }}</td>
                            <td class="action-buttons">
                                <button onclick="editBorrowing({{ $borrowing->id }})" class="btn btn-sm btn-warning me-2">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('admin.borrowings.destroy', $borrowing->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this borrowing?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No borrowings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Borrowing Modal -->
    <div class="modal fade" id="editBorrowingModal" tabindex="-1" aria-labelledby="editBorrowingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBorrowingModalLabel">Edit Borrowing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="editBorrowingForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editBookId" class="form-label">Book</label>
                            <select class="form-control" name="book_id" id="editBookId" required>
                                <option value="">Select Book</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editBorrowerId" class="form-label">Borrower</label>
                            <select class="form-control" name="borrower_id" id="editBorrowerId" required>
                                <option value="">Select Borrower</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editBorrowDate" class="form-label">Borrow Date</label>
                            <input type="date" class="form-control" name="borrowed_at" id="editBorrowDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="editReturnDate" class="form-label">Return Date</label>
                            <input type="date" class="form-control" name="returned_at" id="editReturnDate">
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-control" name="status" id="editStatus" required>
                                <option value="Borrowed">Borrowed</option>
                                <option value="Returned">Returned</option>
                            </select>
                        </div>
                        <input type="hidden" name="id" id="editBorrowingId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="editBorrowingForm" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
    .action-buttons button {
        margin-right: 5px;
    }
    #editBorrowingModal .modal-content {
        background-color: #ffffff;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function editBorrowing(id) {
        const borrowing = @json($borrowings).find(b => b.id === id);
        if (!borrowing) return;

        // Populate modal fields
        document.getElementById('editBookId').value = borrowing.book_id;
        document.getElementById('editBorrowerId').value = borrowing.borrower_id;
        document.getElementById('editBorrowDate').value = borrowing.borrowed_at.split(' ')[0]; // Extract date part
        document.getElementById('editReturnDate').value = borrowing.returned_at ? borrowing.returned_at.split(' ')[0] : '';
        document.getElementById('editStatus').value = borrowing.returned_at ? 'Returned' : 'Borrowed';
        document.getElementById('editBorrowingId').value = borrowing.id;

        // Set the form action dynamically
        const form = document.getElementById('editBorrowingForm');
        form.action = "{{ route('admin.borrowings.update', ['borrowing' => ':id']) }}".replace(':id', borrowing.id);

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('editBorrowingModal'));
        modal.show();
    }
</script>
@endsection