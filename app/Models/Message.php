<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'name',
        'email',
        'subject',
        'message',
        'is_broadcast',
        'read_at'
    ];

    protected $dates = ['read_at'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    // Scope to get messages for a user
    public function scopeForUser($query, $userId)
    {
        return $query->where('recipient_id', $userId)->latest();
    }

    // Mark message as read
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }
}
