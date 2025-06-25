<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Notifications\DatabaseNotification;


class DashboardController extends Controller
{
    public function __construct()
    {
        // Middleware ya kuzuia kuingia bila ku-login, isipokuwa kwa showProfile
        $this->middleware('auth')->except('showProfile');
    }

    // Dashboard view kwa user aliyeingia
    public function index()
    {
        $userId = Auth::id();

        // Hesabu vitabu vilivyochukuliwa na bado havijarejeshwa
        $borrowedBooks = Borrowing::where('user_id', $userId)
            ->where('status', 'borrowed')
            ->count();

        // Hesabu vitabu vilivyo rejeshwa
        $returnedBooks = Borrowing::where('user_id', $userId)
            ->where('status', 'returned')
            ->count();

        // Hesabu faini za vitabu vilivyochukuliwa na marejesho bado hayajafanyika (due date imepita)
        $pendingFines = Borrowing::where('user_id', $userId)
            ->where('status', 'borrowed')
            ->where('return_date', '<', now())
            ->sum('fine_amount');

        // Pata taarifa mpya zisizosomwa
       $userId = Auth::id(); // au $user->id

$notifications = DatabaseNotification::where('notifiable_id', $userId)
    ->where('notifiable_type', 'App\\Models\\User') // kama unatumia App\Models\User
    ->whereNull('read_at') // unread notifications
    ->latest()
    ->take(10)
    ->get();
        // Pata vitabu hivi karibuni vilivyochukuliwa (last 5), zikiruhusu hata vitabu vilivyofutwa (soft deleted)
        $recentBorrowings = Borrowing::where('user_id', $userId)
            ->with(['book' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->take(5)
            ->get();

        // Hakikisha variable $books ipo, inachukua vitabu kutoka recentBorrowings (ikiondoa null)
        $books = $recentBorrowings->pluck('book')->filter();

        // Return view na data zote
        return view('user.dashboard', compact(
            'borrowedBooks',
            'returnedBooks',
            'pendingFines',
            'notifications',
            'recentBorrowings',
            'books'
        ));
    }

    // Search method (hakuna implementation sasa)
    public function search(Request $request)
    {
        return view('user.dashboard');
    }

    // Futa taarifa zote za user kuwa zimenaswa kama kusomwa
    public function clearNotifications(Request $request)
    {
        $userId = Auth::id();
        Notification::where('user_id', $userId)->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Notifications cleared successfully.');
    }

    // Profile ya user mwenyewe
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // Fomu ya kuhariri profile
    public function editProfile()
    {
        return view('edit_profile');
    }

    // Update profile info na picha
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'title' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:100'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store and resize the new photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            // Resize and fit for circular display
            $image = Image::make(public_path("storage/{$path}"))
                ->fit(200, 200, function ($constraint) {
                    $constraint->upsize();
                });
            $image->save();

            $validated['profile_photo_path'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    // Show profile ya mtu mwingine kwa jina lake
    public function showProfile($name)
    {
        $user = User::where('name', $name)->firstOrFail();
        return view('profile', compact('user'));
    }

    // Upload picha mpya ya profile (kwa form tofauti)
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store and resize the new photo
        $path = $request->file('profile_photo')->store('profile_photos', 'public');
        $image = Image::make(public_path("storage/{$path}"))->fit(128, 128);
        $image->save();

        // Update user record
        $user->update(['profile_photo_path' => $path]);

        return redirect()->route('edit_profile')->with('success', 'Profile photo updated successfully!');
    }
}
