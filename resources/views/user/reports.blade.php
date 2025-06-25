@extends('layouts.user')

@section('content')
<div class="bg-white shadow-xl rounded-xl p-6 sm:p-8 lg:p-10 max-w-4xl w-full mx-auto">
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-3xl font-bold text-gray-900 mb-6">Book Loan Reports</h1>

    <!-- Filter Form -->
    <form action="{{ route('reports') }}" method="GET" class="mb-6">
        <div class="flex items-center gap-4">
            <label for="filter" class="text-sm font-medium text-gray-700">Filter Reports:</label>
            <select name="filter" id="filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2"
                    onchange="this.form.submit()">
                <option value="all" {{ isset($filter) && $filter == 'all' ? 'selected' : '' }}>All</option>
                <option value="borrowed" {{ isset($filter) && $filter == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                <option value="overdue" {{ isset($filter) && $filter == 'overdue' ? 'selected' : '' }}>Overdue</option>
                <option value="returned" {{ isset($filter) && $filter == 'returned' ? 'selected' : '' }}>Returned</option>
            </select>
        </div>
    </form>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-blue-800">Total Borrowed</h3>
            <p class="text-2xl font-semibold text-blue-900">{{ $summary['total_borrowed'] ?? 0 }}</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-yellow-800">Overdue Books</h3>
            <p class="text-2xl font-semibold text-yellow-900">{{ $summary['total_overdue'] ?? 0 }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg shadow">
            <h3 class="text-sm font-medium text-green-800">Total Fines</h3>
            <p class="text-2xl font-semibold text-green-900">{{ number_format($summary['total_fines'] ?? 0, 0) }} TZS</p>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrow Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Return Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fine (TZS)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if (isset($loans) && $loans->isNotEmpty())
                    @foreach ($loans as $loan)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loan['book_title'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $loan['borrow_date'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $loan['due_date'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $loan['return_date'] ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ 
                                    ($loan['status'] ?? '') == 'Overdue' ? 'bg-red-100 text-red-800' : 
                                    (($loan['status'] ?? '') == 'Returned' ? 'bg-green-100 text-green-800' : 
                                    'bg-blue-100 text-blue-800') }}">
                                    {{ $loan['status'] ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ number_format($loan['fine'] ?? 0, 0) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if(($loan['status'] ?? '') == 'Overdue')
                                    <a href="{{ route('loans.pay', $loan['id']) }}" class="text-indigo-600 hover:text-indigo-900">Pay Fine</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No loans found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(isset($loans) && method_exists($loans, 'links'))
        <div class="mt-4">
            {{ $loans->links() }}
        </div>
    @endif

    <!-- Back to Dashboard -->
    <div class="mt-6 text-right">
        <a href="{{ route('user.dashboard') }}"
           class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection