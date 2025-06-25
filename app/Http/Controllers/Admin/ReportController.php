<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrow;

class ReportController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $totalUsers = User::count();
        $borrowedBooks = Borrow::whereNull('returned_at')->count();
        $returnedBooks = Borrow::whereNotNull('returned_at')->count();
        $overdueBooks = Borrow::whereNull('returned_at')->where('due_date', '<', now())->count();

        // Optional: Chart Data for Borrowing Trends
        $borrowTrends = Borrow::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                              ->groupBy('date')
                              ->orderBy('date', 'desc')
                              ->take(7)
                              ->get();

        return view('admin.reports.index', compact(
            'totalBooks', 'totalUsers', 'borrowedBooks', 'returnedBooks', 'overdueBooks', 'borrowTrends'
        ));
    }
}
