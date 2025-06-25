<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminStatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        // Histogram data
        $totalBooks = Book::count();
        $borrowedBooks = Transaction::where('transaction_type', 'borrow')
            ->where('status', 'completed')
            ->count();
        $overdueBooks = Transaction::where('transaction_type', 'borrow')
            ->where('status', 'completed')
            ->where('transaction_date', '<', now()->subDays(14))
            ->count();
        $reservedBooks = Transaction::where('transaction_type', 'reservation')
            ->where('status', 'pending')
            ->count();

        // Daily borrowing (last 30 days for the line graph)
        $dailyBorrowings = Transaction::select(
            DB::raw('DATE(transaction_date) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('transaction_type', 'borrow')
            ->where('transaction_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Fill missing dates with zero
        $dates = [];
        $counts = [];
        for ($i = 0; $i < 30; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;
            $counts[] = $dailyBorrowings[$date] ?? 0;
        }
        $dates = array_reverse($dates);
        $counts = array_reverse($counts);

        // Summary statistics for the table
        $today = Transaction::where('transaction_type', 'borrow')
            ->whereDate('transaction_date', today())
            ->count();

        $thisWeek = Transaction::where('transaction_type', 'borrow')
            ->whereBetween('transaction_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $thisMonth = Transaction::where('transaction_type', 'borrow')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->count();

        $thisYear = Transaction::where('transaction_type', 'borrow')
            ->whereYear('transaction_date', now()->year)
            ->count();

        return view('admin.statistics.index', compact(
            'totalBooks',
            'borrowedBooks',
            'overdueBooks',
            'reservedBooks',
            'dates',
            'counts',
            'today',
            'thisWeek',
            'thisMonth',
            'thisYear'
        ));
    }
}