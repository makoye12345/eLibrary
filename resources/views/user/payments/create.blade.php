 @extends('layouts.user')

   @section('title', 'Create Invoice')
   @section('header', 'Create Invoice')

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
           <h2 class="text-2xl font-bold text-gray-800 mb-4">Create Invoice</h2>
           <div class="bg-white p-6 rounded-lg shadow">
               <form action="{{ route('user.payments.create-invoice') }}" method="POST">
                   @csrf
                   <p class="text-gray-600 mb-4">Click the button below to generate an invoice for your pending fines.</p>
                   <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md inline-flex items-center hover:bg-blue-700">
                       <i class="fas fa-file-invoice mr-2"></i> Generate Invoice
                   </button>
               </form>
           </div>
       </div>

       <div class="mt-4">
           <a href="{{ route('user.payments.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md inline-flex items-center hover:bg-blue-700">
               <i class="fas fa-arrow-left mr-2"></i> Back to Invoice List
           </a>
       </div>
   </div>

   @push('styles')
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
   @endpush
   @endsection
