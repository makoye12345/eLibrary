<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan; // Assuming you have a Loan model
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get filter from request
        $filter = $request->get('filter', 'all');
        
        // Base query
        $query = Loan::with(['book', 'user'])
            ->select('loans.*')
            ->join('books', 'loans.book_id', '=', 'books.id')
            ->orderBy('loans.created_at', 'desc');
        
        // Apply filters
        switch ($filter) {
            case 'borrowed':
                $query->whereNull('return_date');
                break;
            case 'overdue':
                $query->whereNull('return_date')
                    ->where('due_date', '<', Carbon::today());
                break;
            case 'returned':
                $query->whereNotNull('return_date');
                break;
        }
        
        // Get paginated results
        $loans = $query->paginate(10);
        
        // Format loan data for view
        $formattedLoans = $loans->map(function ($loan) {
            $status = 'Borrowed';
            $fine = 0;
            
            if ($loan->return_date) {
                $status = 'Returned';
            } elseif (Carbon::parse($loan->due_date)->isPast()) {
                $status = 'Overdue';
                // Calculate fine (example: 1000 TZS per day overdue)
                $daysOverdue = Carbon::today()->diffInDays($loan->due_date);
                $fine = $daysOverdue * 1000;
            }
            
            return [
                'id' => $loan->id,
                'book_title' => $loan->book->title,
                'borrow_date' => $loan->created_at->format('Y-m-d'),
                'due_date' => $loan->due_date->format('Y-m-d'),
                'return_date' => $loan->return_date ? Carbon::parse($loan->return_date)->format('Y-m-d') : null,
                'status' => $status,
                'fine' => $fine,
            ];
        });
        
        // Calculate summary
        $summary = [
            'total_borrowed' => Loan::whereNull('return_date')->count(),
            'total_overdue' => Loan::whereNull('return_date')
                                ->where('due_date', '<', Carbon::today())
                                ->count(),
            'total_fines' => Loan::whereNull('return_date')
                              ->where('due_date', '<', Carbon::today())
                              ->get()
                              ->sum(function ($loan) {
                                  $daysOverdue = Carbon::today()->diffInDays($loan->due_date);
                                  return $daysOverdue * 1000;
                              }),
        ];
        
        return view('reports', [
            'loans' => $formattedLoans,
            'summary' => $summary,
            'filter' => $filter,
        ]);
    }
}