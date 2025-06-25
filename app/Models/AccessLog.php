<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $fillable = [
        'user_id',
        'guard',
        'action',
        'ip_address',
        'user_agent',
        'platform',
        'browser',
        'device',
    ];

    public function user()
    {
        if ($this->guard === 'admin') {
            return $this->belongsTo(Admin::class, 'user_id');
        }
        return $this->belongsTo(User::class, 'user_id');
    }
}