@extends('layouts.admin')

@section('title', 'Invoice Details')
@section('header', 'Invoice Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Invoice Details</h2>
        <div class="bg-white p-6 rounded-lg shadow">
            <p><strong>User:</strong> {{ $invoice->user->name }} ({{ $invoice->user->reg_number }})</p>
            <p><strong>Invoice Number:</strong> {{ $invoice->invoice_no }}</p>
            <p><strong>Control Number:</strong> {{ $invoice->control_number }}</p>
            <p><strong>Description:</strong> {{ $invoice->description }}</p>
            <p><strong>Invoice Amount:</strong> {{ number_format($invoice->invoice_amount, 2) }} TZS</p>
            <p><strong>Paid Amount:</strong> {{ number_format($invoice->paid_amount, 2) }} TZS</p>
            <p><strong>Balance:</strong> {{ number_format($invoice->balance, 2) }} TZS</p>
            <p><strong>Status:</strong> 
                @if($invoice->balance <= 0)
                    <span class="text-green-600">Paid</span>
                @else
                    <span class="text-red-600">Pending</span>
                @endif
            </p>
            <p><strong>Statement:</strong> {{ $invoice->statement ?? 'No additional statement.' }}</p>

            @if($invoice->fines->isNotEmpty())
                <h3 class="text-lg font-semibold text-gray-800 mt-4 mb-2">Associated Fines</h3>
                <table class="w-full text-sm text-gray-600">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Book Title</th>
                            <th class="px-4 py-2 text-left">Due Date</th>
                            <th class="px-4 py-2 text-left">Fine Amount</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->fines as $fine)
                            <tr>
                                <td class="px-4 py-2">{{ $fine->borrow->book->title }}</td>
                                <td class="px-4 py-2">{{ $fine->borrow->due_at->format('M d, Y') }}</td>
                                <td class="px-4 py-2">{{ number_format($fine->amount, 2) }} TZS</td>
                                <td class="px-4 py-2">
                                    @if($fine->is_paid)
                                        <span class="text-green-600">Paid</span>
                                    @else
                                        <span class="text-red-600">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if($invoice->balance > 0)
                <h3 class="text-lg font-semibold text-gray-800 mt-4 mb-2">Record Payment</h3>
                <form action="{{ route('admin.payments.mark-as-paid', $invoice->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="paid_amount" class="block text-sm font-medium text-gray-700">Payment Amount (TZS)</label>
                        <input type="number" name="paid_amount" id="paid_amount" step="0.01" min="0" max="{{ $invoice->balance }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md inline-flex items-center hover:bg-blue-700">
                        <i class="fas fa-check mr-2"></i> Mark as Paid
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md inline-flex items-center hover:bg-blue-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Invoice List
        </a>
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush
@endsection