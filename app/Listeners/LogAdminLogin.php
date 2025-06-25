<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\AccessLog;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Request;

class LogAdminLogin
{
    public function handle(Login $event)
    {
        // Check if the user is an admin using is_admin column
        if ($event->user->is_admin) {
            $agent = new Agent();
            $agent->setUserAgent(Request::header('User-Agent'));

            AccessLog::create([
                'timestamp' => now(),
                'username' => $event->user->name,
                'platform' => $agent->platform() ?: 'Unknown',
                'ip_address' => Request::ip(),
                'browser' => $agent->browser() ?: 'Unknown',
                'type' => 'login',
                'user_id' => $event->user->id,
            ]);
        }
    }
}