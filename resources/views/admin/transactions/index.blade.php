@extends('layouts.admin')

@section('title', 'Admin - Transactions')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">User Transactions</h1>
    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">User</th>
                <th class="py-2 px-4 border-b">Book</th>
                <th class="py-2 px-4 border-b">Fine Amount</th>
                <th class="py-2 px-4 border-b">Transaction Type</th>
                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $transaction->id }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaction->user->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaction->book->title }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaction->fine_amount ? number_format($transaction->fine_amount, 2) : 'N/A' }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaction->transaction_type }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaction->status }}</td>
                    <td class="py-2 px-4 border-b">{{ $transaction->transaction_date ? $transaction->transaction_date->format('Y-m-d') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
