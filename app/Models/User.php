<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'membership_id',
        'membership_expiry',
        'is_active',
        'bio',
        'location',
        'title',
        'skills',
        'website_url',
        'portfolio_url',
        'available_for_hire',
        'profile_photo_path',
        'email_notifications',
        'reg_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'membership_expiry' => 'date',
        'is_active' => 'boolean',
        'available_for_hire' => 'boolean',
        'email_notifications' => 'boolean',
        'notifications_read_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
        'unread_notifications_count',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : 'https://placehold.co/128x128/a78bfa/ffffff?text=' . strtoupper(substr($this->name, 0, 2));
    }

    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
                   ->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function markNotificationsAsRead()
    {
        $this->unreadNotifications()->update(['read_at' => now()]);
        $this->notifications_read_at = now();
        $this->save();
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function hasBorrowed($bookId)
    {
        return $this->borrows()
            ->where('book_id', $bookId)
            ->whereNull('returned_at')
            ->where('status', 'borrowed')
            ->exists();
    }

    public function bookLoans()
    {
        return $this->hasMany(BookLoan::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function overdueBorrows()
    {
        return $this->borrows()
            ->with(['book'])
            ->whereNull('returned_at')
            ->where('status', 'borrowed')
            ->where('due_at', '<', now())
            ->get();
    }

    public function extensionRequests()
    {
        return $this->hasMany(ExtensionRequest::class);
    }

    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isLibrarian()
    {
        return $this->role === 'librarian' || $this->isAdmin();
    }

    public function isMember()
    {
        return $this->role === 'member';
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    public function canBorrowBooks()
    {
        return $this->is_active 
            && $this->hasVerifiedEmail()
            && $this->totalPendingFines() == 0
            && ($this->isMember() || $this->isLibrarian());
    }

    public function accessLogs()
    {
        return $this->hasMany(\App\Models\AccessLog::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function totalPendingFines()
    {
        return $this->fines()->where('is_paid', 0)->sum('amount');
    }

    public function borrowedBooks(): HasMany
    {
        return $this->hasMany(Borrow::class); // Assuming you have a Borrow model
    }
}