<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\AccessLog;

class LogUserAccess
{
    public function handle(Login $event)
    {
        $request = request();

        AccessLog::create([
            'user_id'   => $event->user->id,
            'platform'  => $request->header('User-Agent'),
            'ip_address'=> $request->ip(),
            'browser'   => $this->getBrowser($request->header('User-Agent')),
        ]);
    }

    protected function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        elseif (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        elseif (strpos($userAgent, 'Safari') !== false) return 'Safari';
        elseif (strpos($userAgent, 'Opera') !== false) return 'Opera';
        elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) return 'Internet Explorer';
        return 'Unknown';
    }
}
