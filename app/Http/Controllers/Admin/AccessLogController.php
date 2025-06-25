<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use Illuminate\Http\Request;

class AccessLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AccessLog::with('user')->orderBy('created_at', 'desc');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('platform', 'like', "%{$search}%")
                  ->orWhere('browser', 'like', "%{$search}%")
                  ->orWhere('device', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
            \Log::info('Access logs search performed', [
                'search_term' => $search,
                'user_id' => auth()->id(),
            ]);
        }

        $logs = $query->paginate(10)->appends(['search' => $search]);

        \Log::info('Admin accessed logs', [
            'user_id' => auth()->id(),
            'logs_count' => $logs->total(),
        ]);

        return view('admin.access-logs.index', compact('logs', 'search'));
    }
}