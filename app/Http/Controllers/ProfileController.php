<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Show user profile
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    // Edit profile form
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    // Update profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::delete('public/avatars/'.$user->avatar);
            }
            
            $avatarName = time().'.'.$request->avatar->extension();
            $request->avatar->storeAs('public/avatars', $avatarName);
            $validated['avatar'] = $avatarName;
        }

        $user->update($validated);

        return redirect()->route('profile.update')
            ->with('success', 'Profile updated successfully!');
    }

    // Change password form
    public function changePassword()
    {
        return view('profile.change-password');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password changed successfully!');
    }

    
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = auth()->user();
        
        // Futa picha ya zamani ikiwa ipo
        if ($user->profile_image) {
            Storage::delete('public/profile_images/'.$user->profile_image);
        }

        // Hifadhi picha mpya
        $imageName = time().'.'.$request->profile_image->extension();
        
        // Resize na save picha
        $image = Image::make($request->file('profile_image'))
            ->fit(300, 300)
            ->encode('jpg', 80);
        
        Storage::put('public/profile_images/'.$imageName, (string) $image);

        // Update record ya user
        $user->update(['profile_image' => $imageName]);

        return back()->with('success', 'Picha ya profaili imesahihishwa!');
    }
    
}