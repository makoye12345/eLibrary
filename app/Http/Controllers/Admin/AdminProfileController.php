<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminProfileController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $admin = Auth::user();

            if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
                // Delete old image if exists
                if ($admin->profile_image) {
                    Storage::disk('public')->delete($admin->profile_image);
                }

                // Store new image
                $path = $request->file('profile_image')->store('profile_images', 'public');
                Log::info('Image uploaded to: ' . $path);

                // Update user
                $admin->update(['profile_image' => $path]);
                Log::info('User profile_image updated for user ID: ' . $admin->id);
            } else {
                Log::error('No valid file uploaded');
                return redirect()->back()->withErrors(['profile_image' => 'No valid image file provided.']);
            }

            return redirect()->route('admin.profile.index')->with('success', 'Profile picture updated successfully.');
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['profile_image' => 'Failed to upload image. Please try again.']);
        }
    }

    // Other methods (index, edit, updateProfile) remain unchanged
    public function index()
    {
        $admin = Auth::user();
        return view('admin.profile.index', compact('admin'));
    }

    public function edit()
    {
        $admin = Auth::user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $admin = Auth::user();
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully.');
    }
}