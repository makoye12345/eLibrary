<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show authenticated user's profile
    public function show()
    {
        $user = Auth::user();
        return view('user.profile.index', [
            'user' => $user,
            'notificationCount' => $user->unread_notifications_count ?? Notification::where('user_id', $user->id)->where('is_read', false)->count(),
        ]);
    }

    // Show profile edit form
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', [
            'user' => $user,
            'notificationCount' => $user->unread_notifications_count ?? Notification::where('user_id', $user->id)->where('is_read', false)->count(),
        ]);
    }

    // Update authenticated user's profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:100'],
            'skills' => ['nullable', 'string', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'github' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'email_notifications' => ['boolean'],
            'available_for_hire' => ['boolean'],
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path && Storage::exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store and resize the new photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $image = Image::make(public_path("storage/{$path}"))
                ->fit(200, 200, function ($constraint) {
                    $constraint->upsize();
                });
            $image->save();

            $validated['profile_photo_path'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    // Show change password form
    public function changePassword()
    {
        return view('profile.change-password');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('profile.show')->with('success', 'Password updated successfully!');
    }
}