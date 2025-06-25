<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LibraryController extends Controller
{
    public function index()
    {
        // Total books
        $totalBooks = DB::table('books')->count();

        // Borrowed books (status = 'borrowed')
        $borrowedBooks = DB::table('books')->where('status', 'borrowed')->count();

        // Overdue books (status = 'overdue')
        $overdueBooks = DB::table('books')->where('status', 'overdue')->count();

        // Reserved books (status = 'reserved')
        $reservedBooks = DB::table('books')->where('status', 'reserved')->count();

        // Borrowing data for the last 30 days
        $endDate = Carbon::today();
        $startDate = $endDate->copy()->subDays(29); // 30 days including today
        $borrowings = DB::table('borrowings')
            ->select(DB::raw('DATE(borrowed_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('borrowed_at', [$startDate, $endDate])
            ->whereNull('returned_at') // Only active borrowings
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare dates and counts for the line chart
        $dates = [];
        $counts = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->toDateString();
            $dates[] = $dateStr;
            $count = $borrowings->firstWhere('date', $dateStr)?->count ?? 0;
            $counts[] = $count;
            $currentDate->addDay();
        }

        // Borrowing summary
        $today = DB::table('borrowings')
            ->whereDate('borrowed_at', Carbon::today())
            ->whereNull('returned_at')
            ->count();

        $thisWeek = DB::table('borrowings')
            ->whereBetween('borrowed_at', [Carbon::today()->startOfWeek(), Carbon::today()])
            ->whereNull('returned_at')
            ->count();

        $thisMonth = DB::table('borrowings')
            ->whereBetween('borrowed_at', [Carbon::today()->startOfMonth(), Carbon::today()])
            ->whereNull('returned_at')
            ->count();

        $thisYear = DB::table('borrowings')
            ->whereBetween('borrowed_at', [Carbon::today()->startOfYear(), Carbon::today()])
            ->whereNull('returned_at')
            ->count();

        return view('admin.library', compact(
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