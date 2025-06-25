@extends('layouts.user')

@section('title', 'Payments')
@section('header', 'Payments')

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
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Invoice List</h2>
        <form action="{{ route('user.payments.create-invoice') }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md inline-flex items-center hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Create Invoice
            </button>
        </form>

        @if($invoices->isEmpty())
            <p class="text-gray-500">No invoices found.</p>
        @else
            <div class="bg-white p-6 rounded-lg shadow">
                <table class="w-full text-sm text-gray-600">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">S/No</th>
                            <th class="px-4 py-2 text-left">Pick</th>
                            <th class="px-4 py-2 text-left">Invoice No</th>
                            <th class="px-4 py-2 text-left">Control Number</th>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-left">Invoice Amount</th>
                            <th class="px-4 py-2 text-left">Paid Amount</th>
                            <th class="px-4 py-2 text-left">Balance</th>
                            <th class="px-4 py-2 text-left">Statement</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $index => $invoice)
                            <tr>
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">
                                    <input type="checkbox" name="pick[]" value="{{ $invoice->id }}">
                                </td>
                                <td class="px-4 py-2">{{ $invoice->invoice_no }}</td>
                                <td class="px-4 py-2">{{ $invoice->control_number }}</td>
                                <td class="px-4 py-2">{{ $invoice->description }}</td>
                                <td class="px-4 py-2">{{ number_format($invoice->invoice_amount, 2) }} TZS</td>
                                <td class="px-4 py-2">{{ number_format($invoice->paid_amount, 2) }} TZS</td>
                                <td class="px-4 py-2">{{ number_format($invoice->balance, 2) }} TZS</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('payments.statement', $invoice->id) }}" class="text-blue-600 hover:underline">View</a>
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

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush
@endsection