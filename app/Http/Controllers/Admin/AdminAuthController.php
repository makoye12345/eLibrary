<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        // Check if admin is already logged in
        if (Auth::guard('admin')->check()) {
            Log::info('Admin already logged in, redirecting to dashboard', ['admin_id' => Auth::guard('admin')->id()]);
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login'); // Ensure this view exists at resources/views/admin/auth/login.blade.php
    }

    /**
     * Handle an admin login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate the login request
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Attempt to log the admin in
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $adminId = Auth::guard('admin')->id();
            Log::info('Admin logged in successfully', ['admin_id' => $adminId, 'ip' => $request->ip()]);
            
            return redirect()->intended(route('admin.dashboard'))
                           ->with('success', 'Welcome back!');
        }

        // If login attempt fails
        Log::warning('Failed admin login attempt', ['email' => $request->email, 'ip' => $request->ip()]);
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the admin out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Log::info('Admin logged out', ['admin_id' => $adminId, 'ip' => $request->ip()]);
        
        return redirect()->route('admin.login')
                       ->with('status', 'You have been logged out successfully.');
    }
}