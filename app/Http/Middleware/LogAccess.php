<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\AccessLog;
use Jenssegers\Agent\Agent;

class LogAccess
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $userId = null;
            $guard = null;

            if (Auth::guard('web')->check()) {
                $userId = Auth::guard('web')->id();
                $guard = 'web';
            } elseif (Auth::guard('admin')->check()) {
                $userId = Auth::guard('admin')->id();
                $guard = 'admin';
            }

            if ($userId) {
                $agent = new Agent();
                $agent->setUserAgent($request->userAgent());
                
                AccessLog::create([
                    'user_id' => $userId,
                    'guard' => $guard,
                    'action' => $request->method() . ':' . $request->path(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'platform' => $agent->platform() ?? $this->getPlatform($request->userAgent()),
                    'browser' => $agent->browser() ?? $this->getBrowser($request->userAgent()),
                    'device' => $this->determineDeviceType($agent),
                    'device_model' => $agent->device(),
                    'is_mobile' => $agent->isMobile(),
                    'is_tablet' => $agent->isTablet(),
                    'is_desktop' => $agent->isDesktop(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Access log failed: ' . $e->getMessage(), [
                'path' => $request->path(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent() ?? 'null'
            ]);
        }

        return $response;
    }

    protected function determineDeviceType(Agent $agent): string
    {
        if ($agent->isMobile()) {
            return 'Mobile';
        }
        if ($agent->isTablet()) {
            return 'Tablet';
        }
        return 'Desktop';
    }

    protected function getPlatform($userAgent): string
    {
        if (stripos($userAgent, 'Windows') !== false) {
            return 'Windows';
        }
        if (stripos($userAgent, 'Mac') !== false) {
            return 'Mac';
        }
        if (stripos($userAgent, 'Linux') !== false) {
            return 'Linux';
        }
        if (stripos($userAgent, 'Android') !== false) {
            return 'Android';
        }
        if (stripos($userAgent, 'iOS') !== false) {
            return 'iOS';
        }
        return 'Unknown';
    }

    protected function getBrowser($userAgent): string
    {
        if (stripos($userAgent, 'OPR') !== false || stripos($userAgent, 'Opera') !== false) {
            return 'Opera';
        }
        if (stripos($userAgent, 'Edg') !== false) {
            return 'Edge';
        }
        if (stripos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        }
        if (stripos($userAgent, 'Safari') !== false) {
            return 'Safari';
        }
        if (stripos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        }
        if (stripos($userAgent, 'MSIE') !== false || stripos($userAgent, 'Trident') !== false) {
            return 'Internet Explorer';
        }
        return 'Unknown';
    }
}