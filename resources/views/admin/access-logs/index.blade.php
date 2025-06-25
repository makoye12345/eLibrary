@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📜 Access Log History</h2>
        <div class="d-flex">
            <form action="{{ route('admin.access-logs.index') }}" method="GET" class="me-2">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search logs..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            <a href="{{ route('admin.access-logs.index') }}" class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i> Reset
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>🕒 TIMESTAMP</th>
                            <th>👤 USER</th>
                            <th>💻 PLATFORM</th>
                            <th>🌐 IP ADDRESS</th>
                            <th>🛠️ BROWSER</th>
                            <th>📱 DEVICE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td title="{{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : 'N/A' }}">
                                {{ $log->created_at ? $log->created_at->diffForHumans() : 'N/A' }}
                            </td>
                            <td>
                                @if($log->user_id && $log->user)
                                    <a href="{{ route('admin.users.edit', $log->user_id) }}">
                                        {{ $log->username }}
                                    </a>
                                @else
                                    {{ $log->username }}
                                @endif
                            </td>
                            <td>{{ $log->platform ?? 'N/A' }}</td>
                            <td>{{ $log->ip_address ?? 'N/A' }}</td>
                            <td>{{ $log->browser ?? 'N/A' }}</td>
                            <td>{{ $log->device ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No access logs found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($logs->hasPages())
        <div class="card-footer">
            {{ $logs->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .table-dark {
        background-color: #343a40;
        color: white;
    }
    .table-responsive {
        min-height: 300px;
    }
</style>
@endsection