@extends('layouts.user')

@section('title', 'Your Fines')
@section('header', 'Your Fines')

@section('content')
<div class="container mx-auto px-4 py-8 lg:ml-56">
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Fines</h2>
        @php
            $totalFines = auth()->user()->totalPendingFines();
            foreach ($overdueBorrows as $borrow) {
                $totalFines += $borrow->calculateFine();
            }
        @endphp
        @if($fines->isEmpty() && $overdueBorrows->isEmpty())
            <p class="text-gray-500">You have no fines or overdue books.</p>
        @else
            <p class="text-lg font-semibold text-gray-800 mb-4">Total Pending Fines: TSh {{ number_format($totalFines, 2) }}</p>
            <div class="bg-white p-6 rounded-lg shadow">
                @if(!$fines->isEmpty())
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Fines from Late Returns</h3>
                    <table class="w-full text-sm text-gray-600 mb-4">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Book Title</th>
                                <th class="px-4 py-2 text-left">Fine Amount</th>
                                <th class="px-4 py-2 text-left">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fines as $fine)
                                <tr>
                                    <td class="px-4 py-2">{{ $fine->borrow->book->title }}</td>
                                    <td class="px-4 py-2">TSh {{ number_format($fine->amount, 2) }}</td>
                                    <td class="px-4 py-2">{{ $fine->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                @if(!$overdueBorrows->isEmpty())
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Overdue Books (Not Yet Returned)</h3>
                    <table class="w-full text-sm text-gray-600">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Book Title</th>
                                <th class="px-4 py-2 text-left">Fine Amount</th>
                                <th class="px-4 py-2 text-left">Overdue Days</th>
                                <th class="px-4 py-2 text-left">Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overdueBorrows as $borrow)
                                @if($borrow->is_overdue)
                                    @php
                                        $overdueDays = $borrow->overdueDays();
                                        $fineAmount = $borrow->calculateFine();
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-2">{{ $borrow->book->title }}</td>
                                        <td class="px-4 py-2">TSh {{ number_format($fineAmount, 2) }}</td>
                                        <td class="px-4 py-2">{{ $overdueDays }} days</td>
                                        <td class="px-4 py-2">{{ $borrow->due_at->format('M d, Y') }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endif
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Borrowed Books</h2>
        @if($borrowedBooks->isEmpty())
            <p class="text-gray-500">You haven't borrowed any books yet.</p>
        @else
            <div class="bg-white p-6 rounded-lg shadow">
                <table class="w-full text-sm text-gray-600">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Book Title</th>
                            <th class="px-4 py-2 text-left">Author</th>
                            <th class="px-4 py-2 text-left">Category</th>
                            <th class="px-4 py-2 text-left">Due Date</th>
                            <th class="px-4 py-2 text-left">Days Remaining</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrowedBooks as $borrow)
                            <tr>
                                <td class="px-4 py-2">
                                    @if($borrow->book && $borrow->book->id)
                                        <a href="{{ route('books.show', $borrow->book->id) }}" class="hover:underline">{{ $borrow->book->title }}</a>
                                    @else
                                        {{ $borrow->book->title }} <span class="text-red-500">(Book not found)</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $borrow->book->author ?? 'Unknown' }}</td>
                                <td class="px-4 py-2">{{ $borrow->book->category->name ?? 'Uncategorized' }}</td>
                                <td class="px-4 py-2">{{ $borrow->due_at->format('M d, Y') }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $daysRemaining = $borrow->due_at->diffInDays(now(), false);
                                        $isOverdue = $daysRemaining < 0;
                                        $fineAmount = $borrow->calculateFine();
                                        $overdueDays = $borrow->overdueDays();
                                        $hasUnpaidFine = \App\Models\Fine::where('borrow_id', $borrow->id)->where('is_paid', 0)->exists();
                                        $isReturnableWithoutFinePopup = !$isOverdue || ($isOverdue && !$hasUnpaidFine);
                                    @endphp
                                    <span class="{{ $isOverdue ? 'text-red-600' : 'text-gray-600' }}">
                                        {{ $daysRemaining >= 0 ? $daysRemaining : abs($daysRemaining) }} {{ abs($daysRemaining) == 1 ? 'day' : 'days' }} {{ $isOverdue ? '(Overdue)' : '' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex gap-2">
                                        @if($borrow->book && $borrow->book->file_path)
                                            <a href="{{ route('user.books.read', $borrow->book->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded-md flex items-center text-sm">
                                                <i class="fas fa-book-open mr-1"></i> Read
                                            </a>
                                        @endif
                                        @if($isOverdue)
                                            <button type="button"
                                                onclick="showFineDetails({{ $borrow->id }}, '{{ addslashes($borrow->book->title) }}', {{ $fineAmount }}, {{ $overdueDays }}, '{{ $borrow->due_at->format('M d, Y') }}', {{ json_encode($hasUnpaidFine) }})"
                                                class="px-3 py-1 bg-red-600 text-white rounded-md flex items-center text-sm hover:bg-red-700">
                                                <i class="fas fa-undo mr-1"></i> Return
                                            </button>
                                        @else
                                            <form action="{{ route('books.return', $borrow->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-md flex items-center text-sm hover:bg-red-700">
                                                    <i class="fas fa-undo mr-1"></i> Return
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-4">
        <a href="{{ route('user.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md inline-flex items-center hover:bg-blue-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div>
</div>

<div id="fineDetailsModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Overdue Book Details</h3>
        <p><strong>Book Title:</strong> <span id="modalBookTitle"></span></p>
        <p><strong>Fine Amount:</strong> TSh <span id="modalFineAmount"></span></p>
        <p><strong>Overdue Days:</strong> <span id="modalOverdueDays"></span> days</p>
        <p><strong>Due Date:</strong> <span id="modalDueDate"></span></p>
        
        <p id="paymentRequiredMessage" class="text-red-600 mt-2 hidden">Please contact the admin to pay the fine.</p>
        <p id="finePaidMessage" class="text-green-600 mt-2 hidden">Fine has been paid. You can now return the book.</p>

        <div id="returnDirectlyActions" class="mt-4 hidden">
            <form id="directReturnForm" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-md flex items-center text-sm hover:bg-blue-700">
                    <i class="fas fa-undo mr-1"></i> Return Book Now
                </button>
            </form>
        </div>

        <button type="button" onclick="closeModal()" class="mt-4 px-3 py-1 bg-gray-600 text-white rounded-md flex items-center text-sm hover:bg-gray-700">
            <i class="fas fa-times mr-1"></i> Close
        </button>
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    .modal-open {
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
let currentBorrowId = null;

function showFineDetails(borrowId, bookTitle, fineAmount, overdueDays, dueDate, hasUnpaidFine) {
    currentBorrowId = borrowId;

    document.getElementById('modalBookTitle').textContent = bookTitle;
    document.getElementById('modalFineAmount').textContent = fineAmount.toFixed(2);
    document.getElementById('modalOverdueDays').textContent = overdueDays;
    document.getElementById('modalDueDate').textContent = dueDate;

    document.getElementById('paymentRequiredMessage').classList.add('hidden');
    document.getElementById('finePaidMessage').classList.add('hidden');
    document.getElementById('returnDirectlyActions').classList.add('hidden');

    if (hasUnpaidFine) {
        document.getElementById('paymentRequiredMessage').classList.remove('hidden');
    } else {
        document.getElementById('finePaidMessage').classList.remove('hidden');
        document.getElementById('returnDirectlyActions').classList.remove('hidden');
        document.getElementById('directReturnForm').action = `/books/return/${currentBorrowId}`;
    }

    document.getElementById('fineDetailsModal').classList.remove('hidden');
    document.body.classList.add('modal-open');
}

function closeModal() {
    document.getElementById('fineDetailsModal').classList.add('hidden');
    document.body.classList.remove('modal-open');
    currentBorrowId = null;
}

document.addEventListener('click', function(event) {
    const modal = document.getElementById('fineDetailsModal');
    if (event.target === modal) {
        closeModal();
    }
});
</script>
@endpush
@endsection