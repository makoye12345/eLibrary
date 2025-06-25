@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Library Reports</h2>

    <div class="row">
        <!-- Total Books -->
        <div class="col-md-4">
            <div class="card card-stats shadow-sm p-3">
                <h5>Total Books</h5>
                <h2>{{ $totalBooks }}</h2>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-md-4">
            <div class="card card-stats shadow-sm p-3">
                <h5>Total Users</h5>
                <h2>{{ $totalUsers }}</h2>
            </div>
        </div>

        <!-- Books Currently Borrowed -->
        <div class="col-md-4">
            <div class="card card-stats shadow-sm p-3">
                <h5>Books Currently Borrowed</h5>
                <h2>{{ $borrowedBooks }}</h2>
            </div>
        </div>

        <!-- Books Returned -->
        <div class="col-md-4">
            <div class="card card-stats shadow-sm p-3">
                <h5>Books Returned</h5>
                <h2>{{ $returnedBooks }}</h2>
            </div>
        </div>

        <!-- Overdue Books -->
        <div class="col-md-4">
            <div class="card card-stats shadow-sm p-3">
                <h5>Overdue Books</h5>
                <h2 class="text-danger">{{ $overdueBooks }}</h2>
            </div>
        </div>
    </div>

    <h4>Borrowing Trends (Past 7 Days)</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Number of Borrows</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowTrends as $trend)
                <tr>
                    <td>{{ $trend->date }}</td>
                    <td>{{ $trend->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
