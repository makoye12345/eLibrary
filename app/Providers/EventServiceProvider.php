<?php
// app/Providers/EventServiceProvider.php
namespace App\Providers;

use App\Events\UserActionEvent;
use App\Listeners\CreateAdminNotifications;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserActionEvent::class => [
            CreateAdminNotifications::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}