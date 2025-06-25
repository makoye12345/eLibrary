<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    // Show login form for admin
    public function showLoginForm()
    {
        return view('admin.auth.login'); // hakikisha view hii ipo
    }

    // Handle login request
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
        return redirect()->intended('/admin/dashboard');
    }

    return back()->withErrors(['email' => 'Credentials hazifanani!']);
}

    // Handle logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
