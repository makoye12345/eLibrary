@extends('layouts.user')

@section('title', 'Invoice Statement')
@section('header', 'Invoice Statement')

@section('content')
<div class="container mx-auto px-4 py-8 lg:ml-56">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Invoice Statement</h2>
        <div class="bg-white p-6 rounded-lg shadow">
            <p><strong>Invoice Number:</strong> {{ $invoice->invoice_no }}</p>
            <p><strong>Control Number:</strong> {{ $invoice->control_number }}</p>
            <p><strong>Description:</strong> {{ $invoice->description }}</p>
            <p><strong>Invoice Amount:</strong> {{ number_format($invoice->invoice_amount, 2) }} TZS</p>
            <p><strong>Paid Amount:</strong> {{ number_format($invoice->paid_amount, 2) }} TZS</p>
            <p><strong>Balance:</strong> {{ number_format($invoice->balance, 2) }} TZS</p>
            <p><strong>Statement:</strong> {{ $invoice->statement ?? 'No additional statement.' }}</p>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('user.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md inline-flex items-center hover:bg-blue-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Invoice List
        </a>
    </div>
</div>
@endsection