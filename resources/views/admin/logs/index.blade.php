@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Admin Access Logs</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>🕒 TIMESTAMP</th>
                <th>👤 USERNAME</th>
                <th>💻 PLATFORM</th>
                <th>🌐 IP ADDRESS</th>
                <th>🛠️ BROWSER</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at }}</td>
                    <td>{{ optional($log->user)->name ?? (auth()->check() ? auth()->user()->name : 'Guest') }}</td>
                    <td>{{ $log->platform }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->browser }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No logs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $logs->links() }}
    </div>
</div>
@endsection