<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'reg_number' => 'required|string',
            'password' => 'required|string',
        ]);

        Log::info('Login attempt', ['reg_number' => $request->reg_number]);

        $credentials = $request->only('reg_number', 'password');

        // Jaribu admin guard kwanza
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $admin = Auth::guard('admin')->user();
            
            Log::info('Admin login successful', ['reg_number' => $admin->reg_number]);
            return redirect()->route('admin.dashboard');
        }

        // Jaribu user guard
        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user();
            
            Log::info('User login successful', ['reg_number' => $user->reg_number]);
            return redirect()->route('user.dashboard');
        }

        Log::error('Login failed for credentials', ['reg_number' => $request->reg_number]);

        return back()->withErrors([
            'reg_number' => 'The provided credentials are incorrect.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        // Logout kutoka kwa guard yoyote iliyowahi
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::guard('web')->logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}