<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanNotifications extends Command
{
    protected $signature = 'notifications:clean';
    protected $description = 'Clean up old notifications';

    public function handle()
    {
        DB::table('notifications')
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        $this->info('Old notifications cleaned successfully.');
    }
}