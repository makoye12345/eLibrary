@extends('layouts.admin')

@section('title', 'Manage Payments')
@section('header', 'Manage Payments')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Invoice List</h2>
        @if($invoices->isEmpty())
            <p class="text-gray-500">No invoices found.</p>
        @else
            <div class="bg-white p-6 rounded-lg shadow">
                <table class="w-full text-sm text-gray-600">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">S/No</th>
                            <th class="px-4 py-2 text-left">User</th>
                            <th class="px-4 py-2 text-left">Invoice No</th>
                            <th class="px-4 py-2 text-left">Control Number</th>
                            <th class="px-4 py-2 text-left">Invoice Amount</th>
                            <th class="px-4 py-2 text-left">Paid Amount</th>
                            <th class="px-4 py-2 text-left">Balance</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $index => $invoice)
                            <tr>
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $invoice->user->name }} ({{ $invoice->user->reg_number }})</td>
                                <td class="px-4 py-2">{{ $invoice->invoice_no }}</td>
                                <td class="px-4 py-2">{{ $invoice->control_number }}</td>
                                <td class="px-4 py-2">{{ number_format($invoice->invoice_amount, 2) }} TZS</td>
                                <td class="px-4 py-2">{{ number_format($invoice->paid_amount, 2) }} TZS</td>
                                <td class="px-4 py-2">{{ number_format($invoice->balance, 2) }} TZS</td>
                                <td class="px-4 py-2">
                                    @if($invoice->balance <= 0)
                                        <span class="text-green-600">Paid</span>
                                    @else
                                        <span class="text-red-600">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.payments.show', $invoice->id) }}" class="text-blue-600 hover:underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endpush
@endsection