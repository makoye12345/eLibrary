<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasBorrows;

class Admin extends Authenticatable
{
    use Notifiable, HasBorrows;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Determine if the admin can bypass borrowing restrictions
     * 
     * @return bool
     */
    public function canBypassBorrowRestrictions()
    {
        return true; // Admins can always access books
    }
    public function admin()
{
    return $this->hasOne(Admin::class);
}
}