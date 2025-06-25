<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated with the 'admin' guard
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Please log in as an admin.');
        }

        // Get the authenticated admin user
        $admin = Auth::guard('admin')->user();

        // Check if the user has admin privileges
        if (!$admin->isAdmin()) {
            // Log unauthorized access attempt
            \Log::warning('Unauthorized admin access attempt', [
                'user_id' => $admin->id,
                'ip' => $request->ip(),
                'route' => $request->fullUrl(),
            ]);

            return redirect()->route('home')->with('error', 'You do not have admin privileges.');
        }

        // Add admin user to request for easy access
        $request->attributes->add(['admin_user' => $admin]);

        return $next($request);
    }
}