<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix for older versions of MySQL
        Schema::defaultStringLength(191);

        // Use Bootstrap 5 pagination views
        Paginator::defaultView('vendor.pagination.bootstrap-5');

        // Share notifications with user layout views if user is authenticated
        View::composer('layouts.user', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $notificationCount = $user->unreadNotifications()->count();
                $notifications = $user->notifications()->latest()->take(5)->get();

                $view->with(compact('notificationCount', 'notifications'));
            }
        });

        
    }

    /**
     * Helper to detect platform from user agent.
     */
    public static function getPlatform(string $userAgent): string
    {
        if (str_contains($userAgent, 'Windows')) return 'Windows';
        if (str_contains($userAgent, 'Macintosh')) return 'Mac';
        if (str_contains($userAgent, 'Linux')) return 'Linux';
        if (str_contains($userAgent, 'Android')) return 'Android';
        if (str_contains($userAgent, 'iPhone')) return 'iOS';
        return 'Unknown';
    }

    /**
     * Helper to detect browser from user agent.
     */
    public static function getBrowser(string $userAgent): string
    {
        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) return 'Safari';
        if (str_contains($userAgent, 'Edge')) return 'Edge';
        if (str_contains($userAgent, 'MSIE') || str_contains($userAgent, 'Trident')) return 'Internet Explorer';
        return 'Unknown';
    }
}
