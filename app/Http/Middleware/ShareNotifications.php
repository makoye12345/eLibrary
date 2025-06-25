<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareNotifications
{
    /**
     * Handle an incoming request and share notifications with views.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $unreadNotifications = $user->unreadNotifications()
                ->select('id', 'data', 'created_at', 'read_at') // Select only needed columns
                ->limit(5)
                ->get();

            view()->share([
                'unreadNotifications' => $unreadNotifications,
                'unreadNotificationsCount' => $user->unreadNotifications()->count(),
            ]);
        }

        return $next($request);
    }
}