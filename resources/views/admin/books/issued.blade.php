@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="dashboard-container">
            <h2 class="welcome-message">Issued Books</h2>

            <!-- Issued Books Table -->
            <div class="books-table-container">
                @if($borrows->isEmpty())
                    <div class="table-error">
                        No books are currently issued.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Book Title</th>
                                    <th>Borrower</th>
                                    <th>Borrow Date</th>
                                    <th>Due Date</th>
                                    <th>Overdue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrows as $index => $borrow)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $borrow->book ? $borrow->book->title : 'Book Deleted' }}</td>
                                        <td>{{ $borrow->user ? $borrow->user->name : 'User Deleted' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($borrow->borrowed_at)->format('m/d/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($borrow->due_at)->format('m/d/Y') }}</td>
                                        <td>
                                            @if(\Carbon\Carbon::parse($borrow->due_at)->isPast())
                                                <span class="badge bg-danger">Overdue</span>
                                            @else
                                                <span class="badge bg-secondary">Not Overdue</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Reuse dashboard styles */
        .content {
            flex-grow: 1;
            padding: 30px 20px 30px 10px;
            background-color: white !important;
            min-height: calc(100vh - 140px);
        }
        .dashboard-container {
            max-width: 2000px;
            margin: 0 auto;
        }
        .welcome-message {
            font-size: 1.75rem;
            color: #1e3c72 !important;
            margin-bottom: 30px;
            font-weight: 600;
            text-align: center;
        }
        .books-table-container {
            background: white !important;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            animation: slideDown 0.8s ease-out forwards;
        }
        .table-error {
            color: #dc3545 !important;
            text-align: center;
            padding: 20px;
            font-size: 1rem;
        }
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table th, .table td {
            padding: 12px;
            vertical-align: middle;
            text-align: left;
        }
        .table thead th {
            background: #1e3c72;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            border: none;
        }
        .table tbody tr {
            transition: background 0.3s ease;
        }
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        .table tbody td {
            border-bottom: 1px solid #dee2e6;
        }
        .badge {
            font-size: 0.85rem;
            padding: 6px 12px;
        }
        /* Animation */
        @keyframes slideDown {
            0% {
                transform: translateY(-20px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .table th, .table td {
                font-size: 0.85rem;
                padding: 8px;
            }
            .welcome-message {
                font-size: 1.5rem;
            }
        }
        @media (max-width: 576px) {
            .table-responsive {
                font-size: 0.8rem;
            }
            .table th, .table td {
                padding: 6px;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animate table container
            const container = document.querySelector('.books-table-container');
            container.style.opacity = '1';
        });
    </script>
@endsection
