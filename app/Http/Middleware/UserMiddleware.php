<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
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
        // Check if user is authenticated via web guard
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check if authenticated user has 'user' role
        if (Auth::guard('web')->user()->role !== 'user') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->with('error', 'Access denied. User privileges required.');
        }

        return $next($request);
    }
}