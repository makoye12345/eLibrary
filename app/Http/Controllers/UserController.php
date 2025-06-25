<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated for all methods
    }
    

    public function dashboard()
    {
        $stats = [
            'borrowed_books' => 0,
            'returned_books' => 0,
            'overdue_books' => 0,
            'pending_fines' => 0,
        ];
        $recent_activities = collect([]);

        try {
            $user = Auth::user();

            $stats['borrowed_books'] = $user->borrowedBooks()->count();
            $stats['returned_books'] = $user->returnedBooks()->count();
            $stats['overdue_books'] = $user->overdueBooks()->count();
            $stats['pending_fines'] = $user->overdueBooks()->count() * 10000;

            $recent_activities = $user->loans()
                ->with(['book' => function ($query) {
                    $query->withTrashed();
                }])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($loan) {
                    return [
                        'title' => $loan->book ? $loan->book->title : 'Kitabu Hakipatikani',
                        'action' => $loan->status === 'borrowed' ? 'Kukopa' : 'Kurudisha',
                        'date' => $loan->borrowed_at ? $loan->borrowed_at->format('Y-m-d') : 'N/A',
                        'status' => $loan->status === 'borrowed' && $loan->due_date && $loan->due_date < now() ? 'Imepitiliza' : ucfirst($loan->status === 'borrowed' ? 'Inakopwa' : 'Imerejeshwa'),
                    ];
                });

            Log::info('User dashboard accessed successfully', [
                'user_id' => $user->id,
                'stats' => $stats,
                'recent_activities_count' => $recent_activities->count(),
                'timestamp' => now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading user dashboard: ' . $e->getMessage(), [
                'user_id' => auth()->id() ?? 'guest',
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            $stats = [
                'borrowed_books' => 0,
                'returned_books' => 0,
                'overdue_books' => 0,
                'pending_fines' => 0,
            ];
            $recent_activities = collect([]);
        }

        return view('user.dashboard', compact('stats', 'recent_activities'))->with('error', isset($e) ? 'Imeshindwa kupakia dashibodi. Tafadhali jaribu tena.' : null);
    }

    public function show()
    {
        try {
            $user = Auth::user();
            Log::info('User profile accessed successfully', [
                'user_id' => $user->id,
                'timestamp' => now()->toDateTimeString(),
            ]);

            return view('profile.show', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error loading user profile: ' . $e->getMessage(), [
                'user_id' => auth()->id() ?? 'guest',
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return redirect()->route('dashboard')->with('error', 'Imeshindwa kupakia profaili. Tafadhali jaribu tena.');
        }
    }

    public function edit()
    {
        try {
            $user = Auth::user();
            Log::info('User profile edit page accessed', [
                'user_id' => $user->id,
                'timestamp' => now()->toDateTimeString(),
            ]);

            return view('profile.edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error loading profile edit page: ' . $e->getMessage(), [
                'user_id' => auth()->id() ?? 'guest',
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return redirect()->route('profile')->with('error', 'Imeshindwa kupakia fomu ya kuhariri profaili. Tafadhali jaribu tena.');
        }
    }

    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
                'profile_photo' => ['nullable', 'image', 'max:2048'],
            ]);

            $data = $request->only('name', 'email', 'username');

            if ($request->hasFile('profile_photo')) {
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $data['profile_photo_path'] = $path;
            }

            $user->update($data);

            Log::info('User profile updated successfully', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($data),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return redirect()->route('profile')->with('success', 'Profaili imesasishwa kwa mafanikio.');
        } catch (\Exception $e) {
            Log::error('Error updating user profile: ' . $e->getMessage(), [
                'user_id' => auth()->id() ?? 'guest',
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return back()->withErrors(['error' => 'Imeshindwa kusasisha profaili. Tafadhali jaribu tena.']);
        }
    }

    public function reports(Request $request)
    {
        try {
            $user = Auth::user();
            $filter = $request->input('filter', 'all'); // Default to 'all'

            // Build query for loans
            $query = $user->loans()->with(['book' => function ($query) {
                $query->withTrashed(); // Include soft-deleted books
            }]);

            // Apply filter
            if ($filter === 'borrowed') {
                $query->where('status', 'borrowed')->where('due_date', '>=', now());
            } elseif ($filter === 'overdue') {
                $query->where('status', 'borrowed')->where('due_date', '<', now());
            } elseif ($filter === 'returned') {
                $query->where('status', 'returned');
            }

            $loans = $query->orderBy('created_at', 'desc')->get()->map(function ($loan) {
                $isOverdue = $loan->status === 'borrowed' && $loan->due_date && $loan->due_date < now();
                return [
                    'book_title' => $loan->book ? $loan->book->title : 'Kitabu Hakipatikani',
                    'borrow_date' => $loan->borrowed_at ? $loan->borrowed_at->format('Y-m-d') : 'N/A',
                    'due_date' => $loan->due_date ? $loan->due_date->format('Y-m-d') : 'N/A',
                    'return_date' => $loan->returned_at ? $loan->returned_at->format('Y-m-d') : 'N/A',
                    'status' => $isOverdue ? 'Imepitiliza' : ucfirst($loan->status === 'borrowed' ? 'Inakopwa' : 'Imerejeshwa'),
                    'fine' => $isOverdue ? 10000 : 0, // TZS 10,000 per overdue book
                ];
            });

            Log::info('User reports accessed successfully', [
                'user_id' => $user->id,
                'filter' => $filter,
                'loans_count' => $loans->count(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return view('user.reports', compact('loans', 'filter'));
        } catch (\Exception $e) {
            Log::error('Error loading user reports: ' . $e->getMessage(), [
                'user_id' => auth()->id() ?? 'guest',
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return redirect()->route('dashboard')->with('error', 'Imeshindwa kupakia ripoti. Tafadhali jaribu tena.');
        }
    }
   public function accessLogs()
{
    $logs = Auth::user()->accessLogs()
           ->with('user') // Eager load user relationship
           ->latest() // Order by most recent first
           ->paginate(15); // Paginate with 15 items per page
    
    return view('user.access-logs', compact('logs'));
}

public function index()
{
    $logs = AccessLog::with('user')->paginate(10); // Paginate with 10 logs per page
    return view('logs.index', compact('logs'));
}
public function clearAll()
{
    AccessLog::truncate(); // Deletes all records
    return redirect()->back()->with('success', 'All access logs cleared.');
}

}