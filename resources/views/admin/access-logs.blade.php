@extends('layouts.admin')

@section('content')
    @php
        use App\Models\AccessLog;
        use Jenssegers\Agent\Agent;

        $logs = AccessLog::with('user')->orderBy('created_at', 'desc')->paginate(10);
        $agent = new Agent();
    @endphp

    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>User Access Logs</h3>
                <form action="{{ route('access-logs.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all access logs?');">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        🗑️ Clear All Logs
                    </button>
                </form>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>🕒 TIMESTAMP</th>
                            <th>👤 USER</th>
                            <th>💻 PLATFORM</th>
                            <th>🌐 IP ADDRESS</th>
                            <th>🛠️ BROWSER</th>
                            <th>📱 DEVICE TYPE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            @php
                                $agent->setUserAgent($log->user_agent ?? '');

                                $deviceType = 'Desktop';
                                if ($agent->isMobile()) {
                                    $deviceType = 'Mobile';
                                } elseif ($agent->isTablet()) {
                                    $deviceType = 'Tablet';
                                }

                                $deviceModel = $agent->device();
                                $device = $deviceModel ? "{$deviceModel} ({$deviceType})" : $deviceType;
                            @endphp
                            <tr>
                                <td>{{ $logs->firstItem() + $index }}</td>
                                <td>{{ $log->created_at ? $log->created_at->format('d M Y H:i') : 'N/A' }}</td>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td>{{ $agent->platform() ?? 'Unknown OS' }}</td>
                                <td>{{ $log->ip_address }}</td>
                                <td>{{ $agent->browser() ?? 'Unknown Browser' }}</td>
                                <td>{{ $device }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No access logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
