<?php
// app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'message', 'data', 'user_id', 'admin_id', 'read'
    ];

    protected $casts = [
        'read' => 'boolean',
        'data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public static function notifyAdmins($type, $message, $userId, $data = [])
    {
        $admins = User::where('is_admin', true)->get();
        $notifications = [];

        foreach ($admins as $admin) {
            $notifications[] = self::create([
                'type' => $type,
                'message' => $message,
                'data' => $data,
                'user_id' => $userId,
                'admin_id' => $admin->id
            ]);
        }

        try {
            event(new NotificationCreated($notifications));
        } catch (\Exception $e) {
            \Log::error('Notification broadcast failed: ' . $e->getMessage());
        }

        return $notifications;
    }
}