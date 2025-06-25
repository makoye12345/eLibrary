<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\ContactMessage;

class FetchNotifications
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            $this->shareAdminData(Auth::guard('admin')->user());
        } elseif (Auth::guard('web')->check()) {
            $this->shareUserData(Auth::guard('web')->user());
        } else {
            $this->shareEmptyCounts();
        }

        return $next($request);
    }

    protected function shareAdminData($user)
    {
        $isLibrarian = $user->role === 'librarian';

        $counts = [
            'overdueBooksCount' => $this->getOverdueBooksCount($user->id, 'admin'),
            'pendingReservationsCount' => $this->getPendingReservationsCount($user->id, 'admin'),
            'unreadMessagesCount' => $isLibrarian 
                ? ContactMessage::where('status', 'Pending')->count()
                : 0,
        ];

        $notifications = $user->notifications()
            ->with('recipients')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        View::share([
            'notificationCounts' => $counts,
            'notifications' => $notifications
        ]);
    }

    protected function shareUserData($user)
    {
        $counts = [
            'overdueBooksCount' => $this->getOverdueBooksCount($user->id, 'web'),
            'pendingReservationsCount' => $this->getPendingReservationsCount($user->id, 'web'),
            'unreadMessagesCount' => 0, // Regular users don't see message counts
        ];

        $notifications = $user->notifications()
            ->with('recipients')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        View::share([
            'notificationCounts' => $counts,
            'notifications' => $notifications
        ]);
    }

    protected function shareEmptyCounts()
    {
        View::share([
            'notificationCounts' => [
                'overdueBooksCount' => 0,
                'pendingReservationsCount' => 0,
                'unreadMessagesCount' => 0
            ],
            'notifications' => collect() // Empty collection for notifications
        ]);
    }

    protected function getOverdueBooksCount($userId, $guard)
    {
        // Implement your actual overdue books logic
        // Example:
        // return $guard === 'admin' 
        //     ? OverdueBook::count() 
        //     : OverdueBook::where('user_id', $userId)->count();
        return 0;
    }

    protected function getPendingReservationsCount($userId, $guard)
    {
        // Implement your actual reservations logic
        // Example:
        // return $guard === 'admin'
        //     ? Reservation::where('status', 'pending')->count()
        //     : Reservation::where('user_id', $userId)->where('status', 'pending')->count();
        return 0;
    }
}